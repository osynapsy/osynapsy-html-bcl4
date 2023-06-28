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

/**
 * Description of FormCommands
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
trait FormCommands
{
    public function buttonBackFactory()
    {
        return new Button('btn_back', '<span class="fa fa-arrow-left"></span> Indietro', 'cmd-back btn-default btn-secondary');
    }

    public function buttonCloseModalFactory()
    {
        $button = new Button('btn_close', '<span class="fa fa-times"></span> Chiudi', 'cmd-close btn btn-default btn-secondary');
        return $button->attribute('onclick', "parent.$('#amodal').modal('hide');");
    }

    public function buttonDeleteFactory($label = true, $alert = 'Sei sicuro di voler procedere con l\'eliminazione ?')
    {
        $btnDelete = new Button('btn_delete', $label === true ? '<span class="fa fa-trash-o"></span> Elimina' : $label, 'btn-danger');
        $btnDelete->setAction('delete', [], $alert);
        return $btnDelete;
    }

    public function buttonSaveFactory($label = true)
    {
        if ($label === true) {
            $label = '<span class="fa fa-floppy-o"></span> Salva';
        }
        $btnSave = new Button('btn_save', $label, 'btn-primary');
        $btnSave->setAction('save');
        return $btnSave;
    }
}
