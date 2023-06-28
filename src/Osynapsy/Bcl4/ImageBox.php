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

use Osynapsy\Html\Component\AbstractComponent;
use Osynapsy\Html\Tag;
use Osynapsy\Html\DOM;
use Osynapsy\Html\Component\InputHidden;

class ImageBox extends AbstractComponent
{
    const ACTION_CROP_IMAGE = 'cropImage';
    const ACTION_DELETE_IMAGE = 'deleteFile';

    private $image = [
        'object' => null,
        'webPath' => null,
        'diskPath' => null,
        'dimension' => null,
        'width' => null,
        'height' => null,
        'maxwidth' => 0,
        'maxheight' => 0,
        'domain' => ''
    ];
    protected $rawId;
    protected $cropActive = false;
    protected $dummy;
    protected $fileBox;

    public function __construct($id)
    {
        $this->rawId = $id;
        parent::__construct('div', $id.'_box');
        $this->requireCss('Lib/rcrop/style.css');
        $this->requireJs('Lib/rcrop/script.js');
        $this->requireCss('Bcl4/ImageBox/style.css');
        $this->requireJs('Bcl4/ImageBox/script.js');
        $this->addClass('osy-imagebox-bcl text-center');
        $this->attribute('data-action', 'upload');
        $this->attribute('data-preserve-aspect-ratio', 0);
        $this->add(new InputHidden($id));
        $this->fileBoxFactory();
    }

    public function preBuild()
    {
        try {
            $this->loadImagePaths();
            $this->setImageData();
            $this->setCropState();
            if ($this->cropActive) {
                $this->addClass('crop text-center');
                $this->add($this->imageWithCropActiveFactory());
                $this->add($this->toolbarFactory());
            } else {
                $this->add($this->placeholderImageFactory($this->imageFactory()));
                $this->add($this->buttonDeleteImageFactory('osy-imagebox-bcl-image-delete'));
            }
        } catch (\DomainException $e) {
            $this->add($this->placeholderImageFactory($this->iconCameraFactory()));
        }
    }

    protected function toolbarFactory()
    {
        $toolbar = new Tag('div', null, 'osy-imagebox-bcl-cmd text-center');
        $toolbar->add('<button type="button" class="crop-command btn btn-info btn-sm"><span class="fa fa-crop"></span></button> ');
        $toolbar->add('<button type="button" class="zoomin-command btn btn-info btn-sm"><span class="fa fa-search-plus"></span></button> ');
        $toolbar->add('<button type="button" class="zoomout-command btn btn-info btn-sm mr-5"><span class="fa fa-search-minus"></span></button>');
        $toolbar->add($this->buttonUploadImageFactory());
        if ($this->image['diskPath']) {
            $toolbar->add($this->buttonDeleteImageFactory());
        }
        return $toolbar;
    }

    protected function buttonDeleteImageFactory($class = '')
    {
        $button = new Button($this->id.'DeleteImage', '<i class="fa fa-trash"></i>', 'btn-danger btn-sm '.$class);
        $button->setAction(self::ACTION_DELETE_IMAGE, [$this->image['webPath'],$this->rawId], 'Sei sicuro di voler eliminare l\'immagine?');
        return $button;
    }

    protected function buttonUploadImageFactory()
    {
        $button = new Tag('label', null,'btn btn-warning btn-sm mt-2');
        $button->attribute('for', $this->rawId)->add('<i class="fa fa-upload"></i>');
        return $button;
    }

    protected function placeholderImageFactory($content)
    {
        $dummy = new Tag('label', null, 'osy-imagebox-dummy');
        $dummy->attribute('for', $this->rawId);
        $dummy->add($content);
        if ($this->image['maxwidth']) {
            $dummy->attribute('style', sprintf('max-width : %spx; max-height : %spx;', $this->image['maxwidth'], $this->image['maxheight']));
        }
        return $dummy;
    }

    protected function fileBoxFactory()
    {
        $this->fileBox = $this->add(new Tag('input', $this->rawId));
        $this->fileBox->attributes([
            'type' => 'file',
            'accept' => 'image/*;',
            'capture' => 'camera',
            'name' => $this->rawId,
            'style' => 'display: none;'
        ]);
    }

    protected function iconCameraFactory()
    {
        return new Tag('span', null, 'fa fa-camera glyphicon glyphicon-camera');
    }

    protected function imageWithCropActiveFactory()
    {
        $img = new Tag('img', null, 'imagebox-main');
        $img->attribute([
            'src' => $this->image['domain'].$this->image['webPath'],
            'data-action' => self::ACTION_CROP_IMAGE
        ]);
        return $img;
    }

    private function imageFactory()
    {
        $img = new Tag('img');
        $img->attribute('src', $this->image['domain'].$this->image['webPath']);
        return $img;
    }

    protected function loadImagePaths()
    {
        if (empty($_REQUEST[$this->rawId])) {
            throw new \DomainException('Field is empty', 404);
        }
        $this->image['webPath'] = $_REQUEST[$this->rawId];
        $this->image['diskPath'] = filter_input(\INPUT_SERVER , 'DOCUMENT_ROOT') . $this->image['webPath'];
    }

    protected function setImageData()
    {
        if (!file_exists($this->image['diskPath'])) {
            return;
        }
        $this->image['dimension'] = getimagesize($this->image['diskPath']);
        if (empty($this->image['dimension'])) {
            throw new \Exception('File not found', 404);
        }
        $this->image['width'] = $this->image['dimension'][0];
        $this->image['height'] = $this->image['dimension'][1];
        $this->image['formFactor'] = $this->image['width'] / $this->image['height'];
    }

    protected function setCropState()
    {
        if (empty($this->image['maxwidth'])){
            return;
        }
        if ($this->image['width'] <= $this->image['maxwidth'] && $this->image['height'] <= $this->image['maxheight']) {
            return;
        }
        $this->cropActive = true;
        $this->attribute('data-max-width', $this->image['maxwidth']);
        $this->attribute('data-max-height', $this->image['maxheight']);
        $this->attribute('data-img-width', $this->image['width']);
        $this->attribute('data-img-height', $this->image['height']);
        $this->attribute('data-zoom','1');
    }

    public function setDomain($domain)
    {
        $this->image['domain'] = $domain;
    }

    public function setMaxDimension($width, $height)
    {
        $this->image['maxwidth'] = $width;
        $this->image['maxheight'] = $height;
        $this->image['formFactorIdeal'] = $width / $height;
        return $this;
    }

    public function setPreserveAspectRatio($value)
    {
        $this->attribute('data-preserve-aspect-ratio', empty($value) ? 0 : 1);
    }
}
