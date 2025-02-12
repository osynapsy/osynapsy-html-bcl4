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
            $this->add($this->labelContainerFactory($this->label, $this->object));
        }
        $this->append($this->object);
        if (!empty($this->info)) {
            $this->add(sprintf('<div class="form-group-infobox">%s</div>', $this->info));
        }
    }

    protected function labelContainerFactory($rawlabel, $object)
    {
        $div = new Tag('div', null, 'd-flex');
        $div->add($this->labelFactory(!is_array($rawlabel) ? $rawlabel : $rawlabel[0], $object));
        if (is_array($rawlabel) && array_key_exists(1, $rawlabel)) {
            $div->append($rawlabel[1]);
        }
        return $div;
    }

    protected function labelFactory($rawlabel, $object)
    {
        $label = new Tag('label', null, sprintf('%s mr-auto', $this->labelClass));
        if (is_a($object, 'Tag') && $object->id) {
            $label->attribute('for', $object->id);
        }
        $label->add($rawlabel);
        return $label;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }
}
