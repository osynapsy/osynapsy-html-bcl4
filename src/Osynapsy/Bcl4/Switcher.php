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
use Osynapsy\Html\Component\CheckBox;

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
        $this->checkBox = $this->add(new CheckBox($id))->getCheckBox();
        $this->checkBox->addClass('custom-control-input');
        $this->label = $this->add(new Tag('label', null, "custom-control-label"))->attribute('for', $id);
        $this->label->add($label);
    }

    public function getCheckBox()
    {
        return $this->checkBox;
    }

    public function setAction($action, $parameters = null, $class = 'click-execute', $confirmMessage = null)
    {
        $this->checkBox->attributes(['data-action' => $action, 'data-action-parameters' => $parameters]);
        $this->checkBox->addClass($class);
        if (!empty($confirmMessage)) {
            $this->checkBox->attribute('data-action-confirm', $confirmMessage);
        }
        return $this;
    }

    public function disable()
    {
        $this->checkBox->attribute('disabled', 'disabled');
    }
}
