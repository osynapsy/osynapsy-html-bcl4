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

class FormGroup extends AbstractComponent
{
    public $label;
    public $object;
    protected $info;
    protected $labelClass;

    public function __construct($object, $label = '&nbsp;', $class = 'form-group', $labelClass = 'font-weight-500 text-nowrap')
    {
        parent::__construct('div');
        $this->attribute('class', $class);
        $this->label = $label;
        $this->labelClass = $labelClass;
        $this->object = $object;
    }

    public function preBuild()
    {
        if (!empty($this->label)) {
            $this->add($this->labelFactory());
        }
        $this->add($this->object);
        if (!empty($this->info)) {
            $this->add(sprintf('<div class="form-group-infobox">%s</div>', $this->info));
        }
    }

    protected function labelFactory()
    {
        $div = new Tag('div', null, 'd-flex');
        $label = $div->add(new Tag('label', null, sprintf('%s mr-auto', $this->labelClass)));
        if (is_object($this->object)) {
            $label->attribute('for',$this->object->id);
        }
        if (!is_array($this->label)) {
            $label->add($this->label);
        } else {
            $label->add($this->label[0]);
        }
        if (is_array($this->label) && array_key_exists(1, $this->label)) {
            $div->add($this->label[1]);
        }
        return $div;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }
}
