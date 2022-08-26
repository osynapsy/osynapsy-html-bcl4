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
use Osynapsy\Html\Component;
use Osynapsy\Ocl\CheckBox;

/**
 * Description of Switcher
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Switcher extends Component
{
    private $checkBox;
    private $label;

    public function __construct($id, $label)
    {
        parent::__construct('div', $id.'_container');
        $this->setClass('custom-control custom-switch');
        $this->checkBox = $this->add(new CheckBox($id, 'dummy'))->getCheckBox();
        $this->checkBox->att('class', 'custom-control-input');
        $this->label = $this->add(new Tag('label', null, "custom-control-label"))->att('for', $id);
        $this->label->add($label);
    }

    public function getCheckBox()
    {
        return $this->checkBox;
    }

    public function setAction($action, $parameters = null, $class = 'click-execute', $confirmMessage = null)
    {
        $this->checkBox->att(['data-action' => $action, 'data-action-parameters' => $parameters]);
        $this->checkBox->addClass($class);
        if (!empty($confirmMessage)) {
            $this->checkBox->att('data-action-confirm', $confirmMessage);
        }
        return $this;
    }

    public function disable()
    {
        $this->checkBox->att('disabled');
    }
}
