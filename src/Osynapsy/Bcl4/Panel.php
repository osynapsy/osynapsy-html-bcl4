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

class Panel extends AbstractComponent
{
    private $sections = array(
        'head' => null,
        'body' => null,
        'foot' => null
    );

    private $classCss = [
        'main' => 'panel',
        'head' => 'panel-heading clearfix',
        'body' => 'panel-body',
        'foot' => 'panel-footer',
        'title' => 'panel-title'
    ];

    private $currentRow = null;
    private $currentRowLength = 0;
    private $currentColumn = null;
    private $title;
    private $commands = [];

    public function __construct($id, $title='', $class = ' panel-default', $tag = 'div')
    {
        parent::__construct($tag, $id);
        $this->classCss['main'] = 'panel'.$class;
        $this->sections['body'] = new Tag('div');
        $this->title = $title;
    }

    public function addCommands(array $commands = [])
    {
        $this->commands = array_merge($this->commands, $commands);
        return $this;
    }

    public function preBuild()
    {
        if (!empty($this->title)) {
            $this->getHead()->add($this->titleFactory($this->title, $this->classCss['title']));
        }
        if (!empty($this->commands)) {
            $this->getHead()->add($this->commandsFactory($this->commands));
        }
        $this->addClass($this->classCss['main']);
        foreach ($this->sections as $key => $section){
            if (!empty($section)) {
                $this->add($section->attribute('class', $this->classCss[$key]));
            }            
        }
    }

    protected function titleFactory($title, $class)
    {
        return sprintf('<div class="%s pull-left">%s</div>', $class, $title);
    }

    protected function commandsFactory($commands)
    {        
        $container = new Tag('div', null, 'panel-commands pull-right');
        foreach($commands as $command) {
            $container->add($command);
        }
        return $container;
    }
    
    public function addRow($class = 'row')
    {
        $this->currentRow = $this->sections['body']->add(new Tag('div', null, $class));
        $this->currentRowLength = 0;
        return $this->currentRow;
    }

    public function addColumn($colspan = 12, $offset = 0)
    {
        if (empty($this->currentRow) || ($colspan + $offset + $this->currentRowLength) > 12) {
            $this->addRow();
        }
        $this->currentRowLength += ($colspan + $offset);
        return $this->currentColumn = $this->currentRow->add(new Column($colspan, $offset));
    }

    public function getColumn()
    {
        return $this->currentColumn;
    }
    
    public function getBody()
    {
        return $this->sections['body'];
    }

    public function getHead()
    {
        if (empty($this->sections['head'])) {
            $this->sections['head'] = new Tag('div');
        }
        return $this->sections['head'];
    }

    public function getRow()
    {
        return $this->currentRow ?? $this->addRow();
    }
    
    public function resetClass()
    {
        $this->setClass('','','','');
    }

    public function pushHorizontalField($label, $field, $info = '', $labelColumnWidth = 3)
    {
        $this->classCss['main'] = 'form-horizontal';
        $row = $this->addRow()->attribute('class', 'form-group');
        $offset = $labelColumnWidth;
        if (!empty($label)) {
            $row->add(new Tag('label', null, sprintf('col-sm-%s control-label', $labelColumnWidth)))->add($label);
            $offset = 0;
        }
        if (!empty($label) && is_object($field)) {
            $field->attribute('data-label', $label);
        }
        $fieldContainer = $row->add(new Tag('div', null, sprintf('col-sm-%s col-sm-offset-%s', 12 - $labelColumnWidth, $offset)));
        $fieldContainer->add($field);
        if (!empty($info)) {
            $fieldContainer->add(new Tag('div',null,'form-group-infobox'))->add($info);
        }
    }

    public function setClass($body, $head = null, $foot = null, $main = null, $title = null)
    {
        $this->classCss['body'] = $body;
        if (!is_null($head)) {
            $this->classCss['head'] = $head;
        }
        if (!is_null($foot)) {
            $this->classCss['foot'] = $foot;
        }
        if (!is_null($main)) {
            $this->classCss['main'] = $main;
        }
        if (!is_null($title)) {
            $this->classCss['title'] = $title;
        }
        return $this;
    }
    
    public function getFoot()
    {
        if (empty($this->sections['foot'])) {
            $this->sections['foot'] = new Tag('div');
        }
        return $this->sections['foot'];
    }
}
