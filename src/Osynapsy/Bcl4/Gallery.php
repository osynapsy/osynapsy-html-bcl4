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
use Osynapsy\Html\Component\InputHidden;
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
    protected $modalCommands = [];
    protected $modalFields = [];

    public function __construct($id, $cellWidth = 4)
    {
        parent::__construct('div', $id);
        $this->requireJs('bcl4/filebox/script.js');
        $this->requireJs('bcl4/gallery/script.js');
        $this->add(new Tag('span', null, 'gallery'));
        $this->addClass('osy-bcl4-gallery');
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
        $img = new Tag('img', null, 'img-thumbnail mt-2 osy-bcl4-gallery-thumbnail');
        $img->attributes(['data-toggle' => 'modal', 'data-target' => sprintf('#%sModalViewer', $this->id)]);
        //$img->attribute('onclick', sprintf("let src = $(this).attr('src'); $('#%sViewer').attr('src', src); $('#%sDeleteImage').attr('data-action-parameters', src); $('#%sSaveDescription').attr('data-action-parameters', $(this).data('id')); $('#%sPhotoId').val($(this).data('id'));", $this->id, $this->id, $this->id, $this->id));
        $img->attribute('src', $photo['url']);
        $img->attribute('data-id', $photo['id'] ?? $photo['url'] ?? null);
        $img->attribute('data-fields', json_encode($photo, JSON_HEX_APOS | JSON_HEX_QUOT));
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
        foreach($this->modalFields as $field) {
            $clonedField = clone($field['field']);
            $Modal->getPanelBody()->addColumn($field['width'])->push($field['label'], $clonedField->removeAttribute('data-action'));
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
        foreach($this->modalFields as $field) {
            $actionParameters[] = '#'.$field['field']->getAttribute('id');
        }
        $Button = new Button('btnSendImageTo'.$this->id, 'Invia foto');
        $Button->setAction($this->uploadAction, $actionParameters);
        return $Button;
    }

    protected function modalViewerFactory()
    {
        $Modal = new Modal($this->fieldIDFactory('ModalViewer'), 'Foto', $this->modalViewerDimension);
        $Modal->addClass('modalImageViewer');
        $Modal->getPanelBody()->add(new InputHidden($this->fieldIDFactory('PhotoId')))->addClass('osy-bcl4-gallery-image-id');
        $Modal->getPanelBody()->addColumn()->addClass('text-center')->add(new Tag('img', $this->fieldIDFactory('Viewer'), 'osy-bcl4-gallery-viewer'))->attribute('width', '100%');
        foreach($this->modalFields as $field) {
            $field['field']->addClass('osy-bcl4-gallery-field');
            $Modal->getPanelBody()->addColumn($field['width'])->push($field['label'], $field['field']);
        }
        if ($this->deleteAction) {
            $this->modalCommands[] = $this->buttonDeletePhotoFactory();
        }
        $Modal->addCommand([], $this->modalCommands);
        return $Modal;
    }

    protected function fieldIDFactory($postfix)
    {
        return $this->id . $postfix;
    }

    protected function fieldDescriptionFactory()
    {
        $this->addModalField('Didascalia / Descrizione', new InputGroup($this->id.'Description', null, $this->buttonSaveDescriptionFactory()), 12);
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
        $Button = new Button($this->id.'DeleteImage', 'Elimina foto', 'osy4-bcl-gallery-image-delete btn-danger');
        $Button->setAction($this->deleteAction, [], 'Sei sicuro di voler eliminare la foto corrente (L\'operazione non è reversibile)? ');
        return $Button;
    }

    public function setActions($uploadActionClass, $deleteActionClass, $saveDescriptionActionClass)
    {
        $this->uploadAction = $uploadActionClass;
        $this->deleteAction = $deleteActionClass;
        $this->saveDescriptionAction = $saveDescriptionActionClass;
    }

    public function addModalCommand($cmd)
    {
        $this->modalCommands[] = $cmd;
    }

    public function addModalField($label, $field, $columnWidth = 12)
    {
        $this->modalFields[] = ['label' => $label, 'field' => $field, 'width' => $columnWidth];
    }
}
