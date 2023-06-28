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

/**
 * Description of InputGroup
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */

class InputGroup extends AbstractComponent
{
    protected $textBox;
    protected $postfix;
    protected $prefix;

    public function __construct($name, $prefix = null, $postfix = null, $dimension = null)
    {
        parent::__construct('div');
        $this->addClass('input-group');
        $this->prepend($prefix);
        if (is_object($name)) {
            $this->textBox = $name;
        } else {
            $this->textBox = new TextBox($name);
            $this->textBox->attribute('aria-describedby', $name.'_prefix');
        }
        $this->append($postfix);
        $this->setDimension($dimension);
    }

    public function preBuild(): void
    {
        if (!empty($this->prefix)) {
            $this->add($this->prefix);
        }
        $this->add($this->textBox);
        if (!empty($this->postfix)) {
            $this->add($this->postfix);
        }
    }

    public function prepend($prefix)
    {
        if (empty($prefix)) {
            return;
        }
        if (is_object($prefix)) {
            return $this->getPrefix()->add($prefix);
        }
        return $this->getPrefix()->add(new Tag('span', null, 'input-group-text'))->add($prefix);
    }

    public function append($postfix)
    {
        if (empty($postfix)) {
            return;
        }
         if (is_object($postfix)) {
             return $this->getPostfix()->add($postfix);
         }
        $this->getPostfix()->add(new Tag('span', null, 'input-group-text'))->add($postfix);
    }

    public function getTextBox()
    {
        return $this->textBox;
    }

    public function getPostfix()
    {
        if (empty($this->postfix)) {
            $this->postfix = new Tag('div', null, 'input-group-append');
        }
        return $this->postfix;
    }

    public function getPrefix()
    {
        if (empty($this->prefix)) {
            $this->prefix = new Tag('div', null, 'input-group-prepend');
        }
        return $this->prefix;
    }

    public function setDimension($dimension)
    {
        if (empty($dimension)) {
            return;
        }
        $this->setClass('input-group-'.$dimension);
    }

    public function setSmallSize()
    {
        $this->setDimension('sm');
    }
}

