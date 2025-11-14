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

class Gallery extends AbstractComponent
{
    protected $cellWidth = 4;
    protected $actions = [];
    protected $defaultPhoto;
    protected $contextMenu;
    protected $modalViewerDimension;    
    protected $deleteAction;
    protected $uploadAction;
    protected $saveDescriptionAction;

    public function __construct($id, $cellWidth = 4)
    {
        parent::__construct('div', $id);        
        $this->requireJs('bcl4/filebox/script.js');
        $this->add(new Tag('span', null, 'gallery'));
        $this->cellWidth = $cellWidth;
    }

    public function preBuild()
    {
        $this->add($this->modalUploadFactory());
        $this->add($this->modalViewerFactory());
        $row = $this->add(new Tag('div', null, 'row mb-2'));
        foreach ($this->dataset as $photo) {
            $row->add($this->cellFactory($photo));
        }
        if ($this->uploadAction) {
            $this->add($this->buttonOpenModalFactory());
        }
    }

    protected function cellFactory($photo)
    {
        $div = new Tag('div', null, sprintf('text-center col-%s', $this->cellWidth));
        $div->add($this->thumbnailFactory($photo));
        $div->add($this->labelFactory($photo));
        return $div;
    }

    protected function thumbnailFactory($photo)
    {
        $img = new Tag('img', null, 'img-thumbnail mt-2');
        $img->attributes(['data-toggle' => 'modal', 'data-target' => sprintf('#%sModalViewer', $this->id)]);
        $img->attribute('onclick', sprintf("let src = $(this).attr('src'); $('#%sViewer').attr('src', src); $('#%sDeleteImage').attr('data-action-parameters', src); $('#%sSaveDescription').attr('data-action-parameters', $(this).data('id'));", $this->id, $this->id, $this->id));
        $img->attribute('src', $photo['url']);
        $img->attribute('data-id', $photo['id'] ?? $photo['url'] ?? null);
        return $img;
    }

    protected function labelFactory($photo)
    {
        if (empty($photo['label'])) {
            return '';
        }
        $div = new Tag('div', null, 'gallery-photo-label');
        $div->add(sprintf('<small>%s</small>', $photo['label']));
        return $div;
    }

    protected function cellAddFactory()
    {
        $div = new Tag('div', null, sprintf('text-center col-%s mt-2', $this->cellWidth));
        $div->add(new Tag('div', null, 'img-thumbnail'))->add(new Tag('span', null, 'fa fa-plus'));
        return $div;
    }

    protected function buttonOpenModalFactory()
    {
        $Button = new Button($this->id.'Add', 'Aggiungi foto', 'btn btn-primary btn-block');
        $Button->attributes(['data-toggle' => 'modal', 'data-target' => '#'.$this->id.'ModalUpload']);
        return $Button;
    }

    protected function modalUploadFactory()
    {
        $modalId = $this->id.'ModalUpload';
        $Modal = new Modal($modalId, 'Aggiungi foto');
        $Modal->getPanelBody()->addColumn()->push('Seleziona l\'immagine da aggiungere alla gallery', $this->fileUploadFactory());
        if ($this->saveDescriptionAction) {
            $Modal->getPanelBody()->addColumn()->push('Didascalia / Descrizione', new TextBox($this->fieldIDFactory('Description')));
        }  
        $Modal->addCommand([], [$this->buttonSendPhotoToGalleryFactory()]);
        return $Modal;
    }

    protected function fileUploadFactory()
    {
        return new FileBox($this->id.'File');
    }

    protected function buttonSendPhotoToGalleryFactory()
    {
        $actionParameters = [$this->fieldIDFactory('File')];
        if (!empty($this->saveDescriptionAction)) {
            $actionParameters[] = '#'.$this->fieldIDFactory('Description');
        }
        $Button = new Button('btnSendImageTo'.$this->id, 'Invia foto');
        $Button->setAction($this->uploadAction, $actionParameters);
        return $Button;
    }

    protected function modalViewerFactory()
    {        
        $Modal = new Modal($this->fieldIDFactory('ModalViewer'), 'Foto', $this->modalViewerDimension);
        $Modal->getPanelBody()->addColumn()->addClass('text-center')->add(new Tag('img', $this->fieldIDFactory('Viewer'), 'img-thumbnail'));
        if ($this->saveDescriptionAction) {
            $Modal->getPanelBody()->addColumn()->push('Didascalia / Descrizione', $this->fieldDescriptionFactory());
        }
        $Modal->addCommand([], $this->deleteAction ? [$this->buttonDeletePhotoFactory()] : []);
        return $Modal;
    }

    protected function fieldIDFactory($postfix)
    {
        return $this->id . $postfix;
    }
    
    protected function fieldDescriptionFactory()
    {
        return new InputGroup($this->id.'Description', null, $this->buttonSaveDescriptionFactory());        
    }
    
    protected function buttonSaveDescriptionFactory()
    {
        $fieldId = $this->id . 'SaveDescription';
        $Button = new Button($fieldId, '', 'btn-primary fa fa-floppy-o');
        $Button->setAction($this->saveDescriptionAction, ['']);
        return $Button;
    }
    
    protected function buttonDeletePhotoFactory()
    {
        $Button = new Button($this->id.'DeleteImage', 'Elimina foto', 'btn-danger');
        $Button->setAction($this->deleteAction, [], 'Sei sicuro di voler eliminare la foto corrente (L\'operazione non è reversibile)? ');
        return $Button;
    }
    
    public function setActions($uploadActionClass, $deleteActionClass, $saveDescriptionActionClass)
    {
        $this->uploadAction = $uploadActionClass;
        $this->deleteAction = $deleteActionClass;
        $this->saveDescriptionAction = $saveDescriptionActionClass;
    }
}
