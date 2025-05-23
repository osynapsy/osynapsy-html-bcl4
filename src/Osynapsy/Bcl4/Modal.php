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

class Modal extends AbstractComponent
{
    public $content;
    public $header;
    public $title;
    public $body;
    public $panelBody;
    public $panelFoot;
    private $columnCommandLeft;
    private $columnCommandRight;
    public $footer;

    public function __construct($id, $title = '', $type = '')
    {
        parent::__construct('div',$id);
        $this->attribute('class','modal fade')->attribute('tabindex','-1')->attribute('role','dialog');
        $this->content = $this->add(new Tag('div', null, trim('modal-dialog '.$type)))
                              ->add(new Tag('div', null, 'modal-content'));
        $this->header = $this->content->add(new Tag('div', null, 'modal-header'));
        $this->title = $this->header->add(new Tag('h5'))->attribute('class','modal-title');
        $this->header->add('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
        $this->title->add($title);
        $this->body = $this->content->add(new Tag('div', null, 'modal-body'));
        $this->footer = $this->content->add(new Tag('div', null, 'modal-footer d-block'));
    }

    public function addFooter($content)
    {
        $this->footer->add($content);
        return $content;
    }

    public function addBody($content)
    {
        $this->body->add($content);
        return $content;
    }

    public function getPanelBody()
    {
        if (empty($this->panelBody)){
            $this->panelBody = $this->addBody(new Panel($this->id.'PanelBody'));
            $this->panelBody->resetClass();
        }
        return $this->panelBody;
    }

    public function getPanelFoot()
    {
        if (empty($this->panelFoot)){
            $this->panelFoot = $this->addFooter(new Panel($this->id.'PanelFoot'));
        }
        return $this->panelFoot;
    }

    public function addCommand(array $left = [],array $right = [], $addCloseCommand = true)
    {
        if (empty($this->columnCommandLeft)) {
            $this->columnCommandLeft = $this->getPanelFoot()->addColumn(6)->setXs(6)->addClass('text-left');
        }
        if (empty($this->columnCommandRight)) {
            $this->columnCommandRight = $this->getPanelFoot()->addColumn(6)->setXs(6)->addClass('text-right');
        }
        if ($addCloseCommand){
            $ButtonClose = new Button('btnClose'.$this->id, 'Chiudi', 'btn-secondary');
            $ButtonClose->attribute('onclick',"\$('#{$this->id}').modal('hide');");
            array_push($left, $ButtonClose);
        }
        $this->columnCommandLeft->append($left);
        $this->columnCommandRight->append($right);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->getPanelBody(), $method], $args);
    }
}
