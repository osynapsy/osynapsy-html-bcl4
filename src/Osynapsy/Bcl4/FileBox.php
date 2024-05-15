<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4;

use Osynapsy\Html\Tag;
use Osynapsy\Html\Component\AbstractComponent;
use Osynapsy\Html\Component\IFrame;

class FileBox extends AbstractComponent
{
    protected $fileBox;
    protected $deleteButton;
    protected $uploadPath;
    protected $prefix;
    protected $postfix;
    protected $pdfPreview = [];
    public $showImage = false;
    public $span;

    public function __construct($name, $postfix = false, $prefix = true)
    {
        parent::__construct('div', $name);
        $this->requireJs('bcl4/filebox/script.js');
        $this->prefix = $prefix;
        $this->postfix = $postfix;
    }

    public function preBuild()
    {
        $span = $this->add(new Tag('span'));
        $component = $this->add($this->fileComponentFactory());
        if (empty($_REQUEST[$this->id])) {
            return;
        }
        if ($this->showImage) {
            $span->add(new Tag('img'))->attribute('src', $_REQUEST[$this->id]);
            return;
        }
        $span->add($this->downloadFileBoxFactory($_REQUEST[$this->id]));
    }

    protected function fileComponentFactory()
    {
        $component = new Tag('div', null, 'input-group');
        $component->add($this->prefixFactory());
        $component->add('<input type="text" class="form-control" readonly>');
        if (!empty($this->postfix)) {
            $component->add($this->postfixFactory($this->postfix));
        }
        return $component;
    }

    protected function prefixFactory()
    {
        $Button = new Tag('span', null, 'btn btn-primary btn-file');
        $Button->add('<input type="file" name="'.$this->id.'">');
        $Button->add('<span class="fa fa-folder-open"></span>');
        $Prefix = new Tag('span', null, 'input-group-btn input-group-prepend');
        $Prefix->add($Button);
        return $Prefix;
    }

    protected function postfixFactory($postfix)
    {
        $postfixContainer = new Tag('span', null, 'input-group-btn input-group-append');
        $postfixContainer->add(is_scalar($postfix) ? $this->buttonSendFileFactory($postfix) : $postfix);
        return $postfixContainer;
    }

    protected function buttonSendFileFactory($label)
    {
        $Button = new Button('btnSend'.$this->id, is_bool($label) ? 'Send' : $label, 'btn-primary');
        $Button->attribute('type', 'submit');
        return $Button;
    }

    protected function downloadFileBoxFactory($filePath)
    {
        $pathinfo = pathinfo($filePath);
        $dummy = new Tag('dummy');
        if (!empty($this->pdfPreview) && strtolower($pathinfo['extension']) === 'pdf') {
            $dummy->add($this->previewPdfFactory($filePath));
        }
        $dummy->add($this->labelBoxFileFactory($pathinfo['basename']));
        return $dummy;
    }

    protected function previewPdfFactory($documentWebPath)
    {
        $IFrame = new IFrame('preview', $documentWebPath);
        $IFrame->attribute('style', sprintf('width: %s; min-height: %s; border: 1px solid #ddd', $this->pdfPreview['width'], $this->pdfPreview['height']));
        return $IFrame;
    }

    protected function labelBoxFileFactory($filename)
    {
        $LabelBox = new LabelBox('donwload_'.$this->id);
        $LabelBox->attribute('style','padding: 10px; background-color: #ddd; margin-bottom: 10px;');
        $LabelBox->setLabel($this->buttonDownloadFileFactory($filename) . $this->deleteButton);
        return $LabelBox;
    }

    protected function buttonDownloadFileFactory($filename)
    {
        $Button = new Link(false, $_REQUEST[$this->id], $filename.' <span class="fa fa-download"></span>');
        $Button->attribute('target','_blank');
        return $Button;
    }

    public function setDeleteAction($action, $parameters = [], $confirmMessage = null)
    {
        $Button = new Link(false, false, '', 'fa fa-trash float-right');
        $Button->setAction($action, $parameters, $confirmMessage);
        $this->deleteButton = $Button;
    }

    public function enablePdfPreview($width = '100%', $height = '640px')
    {
        $this->pdfPreview = ['width' => $width, 'height' => $height];
    }
}
