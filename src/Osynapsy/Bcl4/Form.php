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

/**
 * Represents a Html Form.
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Form extends AbstractComponent
{
    use FormCommands;

    protected $head;
    protected $headCommandWidth = 12;
    public  $headClass = 'row';
    protected $alert;
    protected $alertCount=0;
    protected $body;
    protected $foot;
    protected $footClass;
    protected $footStyle;
    protected $footLeft;
    protected $footRight;
    protected $headCommand;

    public function __construct($name, $mainComponentClass = PanelTk::class, $tag = 'form')
    {
        parent::__construct($tag, $name);
        $this->attributes(['name' => $name, 'method' => 'post', 'role' => 'form']);
        $this->body = $this->mainComponentFactory($mainComponentClass);
    }

    public function preBuild()
    {
        if ($this->head) {
            $this->add($this->head);
        }
        if ($this->alert) {
            $this->add($this->alert);
        }
        $this->add($this->body);
        if ($this->foot) {
            $this->add($this->foot);
        }
    }

    protected function mainComponentFactory($mainComponentClass)
    {
        return new $mainComponentClass($this->id.'_panel');
    }

    public function addHeadCommand($object, $space = 1)
    {
        if (empty($this->headCommand)) {
            $this->headCommand = $this->head($this->headCommandWidth);
            $this->headCommand->attribute('style','padding-top: 10px');
        }
        if ($space > 0) {
            $this->headCommand->add(str_repeat('&nbsp;', $space));
        }
        $this->headCommand->add($object);
    }

    public function head($width = null, $offset = 0)
    {
        if (empty($this->head)) {
            $this->head = empty($width) ? new Tag('div', null, 'd-flex flex-md-row flex-column block-header m-b') : new Tag('div', null, 'row');
        }
        $column = $this->head->add(empty($width) ? new Tag('div', null, 'p-2') : new Column($width, $offset));
        return $column;
    }

    public function alert($label = null, $type = 'danger')
    {
        if (empty($this->alert)) {
            $this->alert = new Tag('div');
            $this->alert->attribute('class','transition animated fadeIn m-b-sm');
        }
        $alert = new Alert('alert_'.$this->alertCount, $label, $type);
        $alert->setDismissible(true);
        $alert->showIcon(true);
        $this->alert->add($alert);
        $this->alertCount++;
        return $alert;
    }

    public function fixCommandBar($class = 'fixed-bottom p-2 b-light')
    {
        if (empty($this->foot)) {
            $this->footClass = $class;
            $this->footStyle = 'background-color: rgba(255,255,255,0.8); border-top: 1px solid #ddd;';
            $this->addClass('mb-5');
            return;
        }
        $this->foot->addClass($class);
        $this->foot->attribute('style', 'background-color: rgba(255,255,255,0.8); border-top: 1px solid #ddd;');
    }

    public function foot($obj, $right = false)
    {
        if (empty($this->foot)) {
            $this->foot = new Tag('div', null, trim('d-flex mt-2 '.$this->footClass));
            $this->foot->style = $this->footStyle;
            $this->footLeft = $this->foot->add(new Tag('div', null, 'p-1 mr-auto'));
            $this->footRight = $this->foot->add(new Tag('div', null, 'p-1'));
        }
        $column = $right ? $this->footRight : $this->footLeft;
        $column->add($obj);
        return is_object($obj) ? $obj : $column;
    }

    public function getPanel()
    {
        return $this->body;
    }

    public function setCommand($delete = false, $save = true, $back = true, $closeModal = false, $fixbar = false)
    {
        if ($back) {
            $this->foot($this->buttonBackFactory());
        }
        if ($closeModal) {
            $this->foot($this->buttonCloseModalFactory());
        }
        if ($delete) {
            $this->foot(is_object($delete) ? $delete : $this->buttonDeleteFactory($delete), true);
        }
        if ($save) {
            $this->foot(is_object($save) ? $save : $this->buttonSaveFactory($save), true);
        }
        if ($fixbar) {
            $this->fixCommandBar();
        }
    }

    public function setType($type)
    {
        if ($type == 'horizontal') {
            $this->attribute('class','form-horizontal',true);
        }
        $this->body->setType($type);
    }

    public function setTitle($title, $subTitle = null, $size = null, $hsize = 'h2')
    {
        $objTitle = new Tag($hsize);
        $objTitle->add($title);
        $column = $this->head($size);
        $column->addClass('mr-auto')->add($objTitle);
        if (!empty($size) && $size < 8) {
            $this->headCommandWidth -= $size;
        }
        if (!empty($subTitle)) {
            $column->add(is_scalar($subTitle) ? '<h4><i>'.$subTitle.'</i></h4>' : $subTitle);
        }
        return $column;
    }

    public function resetClass()
    {
        if (method_exists($this->body, 'setClasses')) {
            $this->body->setClasses('', '', '', '');
        }
    }

    public function showBackground()
    {
        if (method_exists($this->body, 'setClasses')) {
            $this->body->setClasses('card', 'card-header', 'card-body', 'card-footer');
        }
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->getPanel(), $method], $arguments);
    }
}
