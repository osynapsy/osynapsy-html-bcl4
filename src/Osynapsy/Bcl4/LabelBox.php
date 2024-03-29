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
use Osynapsy\Html\Component\InputHidden;

class LabelBox extends AbstractComponent
{
    protected $hiddenBox;
    protected $label;

    public function __construct($id)
    {
        parent::__construct('div', $id.'_labelbox');
        $this->requireCss('bcl4/labelbox/style.css');
        $this->addClass('osynapsy-labelbox');
        $this->hiddenBox = $this->add(new InputHidden($id));
    }

    public function setValue($value)
    {
        $this->hiddenBox->setValue($value);
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function preBuild()
    {
        $this->add(sprintf('<span>%s</span>', $this->label ?? $this->hiddenBox->getValue()));
    }
}
