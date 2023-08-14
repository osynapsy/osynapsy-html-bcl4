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
use Osynapsy\Html\Component\InputCheckBox;

/**
 * Description of Switcher
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Switcher extends AbstractComponent
{
    private $checkBox;
    private $label;

    public function __construct($id, $label)
    {
        parent::__construct('div', $id.'_container');
        $this->addClass('custom-control custom-switch');
        $this->add(sprintf('<input type="hidden" name="%s" value="0">', $id));
        $this->checkBox = $this->add($this->inputCheckFactory($id));
        $this->label = $this->add($this->labelFactory($label, $id));
    }

    protected function inputCheckFactory($id)
    {
        $checkBox = new InputCheckBox($id);
        $checkBox->addClass('custom-control-input');
        return $checkBox;
    }

    protected function labelFactory($label, $id)
    {
        $Label = new Tag('label', null, "custom-control-label");
        $Label->attribute('for', $id);
        $Label->add($label);
        return $Label;
    }

    public function getCheckBox()
    {
        return $this->checkBox;
    }

    public function disable()
    {
        $this->checkBox->attribute('disabled', 'disabled');
    }
}
