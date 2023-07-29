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
use Osynapsy\Html\Component\InputHidden;
use Osynapsy\Html\Component\Image;
use Osynapsy\Helper\Upload;

class ImageBox extends AbstractComponent
{
    const ACTION_CROP_IMAGE = 'cropImage';
    const ACTION_DELETE_IMAGE = 'deleteFile';
    const ACTION_UPLOAD = 'uploadImage';

    private $imageData = [
        'object' => null,
        'webPath' => null,
        'diskPath' => null,
        'dimension' => null,
        'width' => null,
        'height' => null,
        'maxwidth' => 0,
        'maxheight' => 0,
        'domain' => '',
        'cropActive' => false
    ];
    protected $rawId;
    protected $cropActive = false;
    protected $dummy;
    protected $fileBox;

    public function __construct($id)
    {
        $this->rawId = $id;
        parent::__construct('div', $id.'_box');
        $this->requireCss('lib/rcrop/style.css');
        $this->requireJs('lib/rcrop/script.js');
        $this->requireCss('bcl4/imagebox/style.css');
        $this->requireJs('bcl4/imagebox/script.js');
        $this->addClass('osy-imagebox-bcl text-center');
        $this->attribute('data-action', self::ACTION_UPLOAD);
        $this->attribute('data-action-parameters', $id);
        $this->attribute('data-preserve-aspect-ratio', 0);
    }

    public function preBuild()
    {
        $id = $this->rawId;
        $imageData = $this->initImageData($this->imageData, $id);
        $this->add(new InputHidden($id));
        $this->add($this->fileBoxFactory($id));
        if (empty($imageData['webPath'])) {
            $this->imageBoxEmptyFactory();
        } elseif ($imageData['cropActive']) {
            $this->imageBoxWithCropFactory($imageData);
        } else {
            $this->imageBoxFactory($imageData);
        }
    }

    protected function initImageData(&$imageData, $id)
    {
        if (empty($_REQUEST[$id])) {
            return $imageData;
        }
        $webPath = $_REQUEST[$id];
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $diskPath = $documentRoot . $webPath;
        if (!file_exists($diskPath)) {
            $_REQUEST[$id] = '';
            return $imageData;
        }
        $dimensions = getimagesize($diskPath);
        if (empty($dimensions)) {
            $_REQUEST[$id] = '';
            return $imageData;
        }
        $imageData['webPath'] = $webPath;
        $imageData['diskPath'] = $diskPath;
        $imageData['dimension'] = $dimensions;
        $imageData['width'] = $dimensions[0];
        $imageData['height'] = $dimensions[1];
        $imageData['formFactor'] = $imageData['width'] / $imageData['height'];
        if (!empty($imageData['maxwidth']) && ($imageData['width'] > $imageData['maxwidth'] || $imageData['height'] > $imageData['maxheight'])){
            $imageData['cropActive'] = true;
        }
        return $imageData;
    }

    protected function imageBoxEmptyFactory()
    {
        $this->add($this->placeholderImageFactory($this->iconCameraFactory()));
    }

    protected function imageBoxWithCropFactory($imageData)
    {
        $this->enableCrop($imageData['maxwidth'], $imageData['maxheight'], $imageData['width'], $imageData['height']);
        $this->add($this->imageWithCropActiveFactory($imageData['domain'], $imageData['webPath']));
        $this->add($this->toolbarFactory($imageData['webPath']));
    }

    protected function imageBoxFactory($imageData)
    {
        $this->add($this->placeholderImageFactory($this->imageFactory($imageData['domain'], $imageData['webPath'])));
        $this->add($this->buttonDeleteImageFactory('osy-imagebox-bcl-image-delete'));
    }

    protected function enableCrop($maxWidth, $maxHeight, $imageWidth, $imageHeight)
    {
        $this->addClass('crop text-center');
        $this->attribute('data-max-width', $maxWidth);
        $this->attribute('data-max-height', $maxHeight);
        $this->attribute('data-img-width', $imageWidth);
        $this->attribute('data-img-height', $imageHeight);
        $this->attribute('data-zoom','1');
    }

    protected function imageWithCropActiveFactory($domain, $imageWebPath)
    {
        return $this->imageFactory($domain, $imageWebPath)->attributes([
            'class' => 'imagebox-main',
            'data-action' => self::ACTION_CROP_IMAGE
        ]);
    }

    protected function imageFactory($siteDomain, $imageWebPath)
    {
        return new Image(false, $siteDomain.$imageWebPath);
    }

    protected function toolbarFactory($imageWebPath)
    {
        $ButtonGroup = new Tag('div', null, 'osy-imagebox-bcl-cmd btn-group btn-group-sm mt-1');
        $ButtonGroup->add('<button type="button" class="crop-command btn btn-primary"><span class="fa fa-crop"></span></button> ');
        $ButtonGroup->add('<button type="button" class="zoomin-command btn btn-primary"><span class="fa fa-search-plus"></span></button> ');
        $ButtonGroup->add('<button type="button" class="zoomout-command btn btn-primary"><span class="fa fa-search-minus"></span></button>');
        $ButtonGroup->add($this->buttonUploadImageFactory());
        if ($imageWebPath) {
            $ButtonGroup->add($this->buttonDeleteImageFactory($imageWebPath));
        }
        return $ButtonGroup;
    }

    protected function buttonDeleteImageFactory($imageWebPath, $class = '')
    {
        $button = new Button($this->id.'DeleteImage', '<i class="fa fa-trash"></i>', 'btn-danger '.$class);
        $button->setAction(self::ACTION_DELETE_IMAGE, [$imageWebPath, $this->rawId], 'Sei sicuro di voler eliminare l\'immagine?');
        return $button;
    }

    protected function buttonUploadImageFactory()
    {
        $Button = new Button(false, '<i class="fa fa-upload"></i>', 'btn-warning');
        $Button->attribute('onclick', sprintf("document.getElementById('%s').click()", '_'.$this->rawId));
        return $Button;
    }

    protected function placeholderImageFactory($content)
    {
        $dummy = new Tag('label', null, 'osy-imagebox-dummy');
        $dummy->attribute('for', '_'.$this->rawId);
        $dummy->add($content);
        if ($this->imageData['maxwidth']) {
            $dummy->attribute('style', sprintf('max-width : %spx; max-height : %spx;', $this->imageData['maxwidth'], $this->imageData['maxheight']));
        }
        return $dummy;
    }

    protected function fileBoxFactory($id)
    {
        $fileBox = new Tag('input', '_'.$id);
        $fileBox->attributes([
            'type' => 'file',
            'accept' => 'image/*;',
            'capture' => 'camera',
            'name' => $id,
            'style' => 'display: none;'
        ]);
        return $fileBox;
    }

    protected function iconCameraFactory()
    {
        return new Tag('span', null, 'fa fa-camera glyphicon glyphicon-camera');
    }

    public function setDomain($domain)
    {
        $this->imageData['domain'] = $domain;
    }

    public function setMaxDimension($width, $height)
    {
        $this->imageData['maxwidth'] = $width;
        $this->imageData['maxheight'] = $height;
        $this->imageData['formFactorIdeal'] = $width / $height;
        return $this;
    }

    public function setPreserveAspectRatio($value)
    {
        $this->attribute('data-preserve-aspect-ratio', empty($value) ? 0 : 1);
    }

    public static function uploadAction($response, $componentId, $repoPath = '/upload')
    {
        $filename = (new Upload($componentId, $repoPath))->save();
        $response->js(sprintf("document.getElementById('%s').value = '%s'", $componentId, $filename));
        $response->js(sprintf("Osynapsy.refreshComponents(['%s_box']);", $componentId));
    }
}
