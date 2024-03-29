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
use Osynapsy\Html\Component\InputHidden;

class Label extends AbstractComponent
{
    protected $hiddenBox;

    public function __construct($id, $label, $type='info', $dim='3')
    {
        parent::__construct('h'.$dim, $id.'_label');
        $this->hiddenBox = $this->add(new InputHidden($id));
        $this->add(new Tag('span'))
             ->attribute('class','label label-'.$type)
             ->add($label);
    }

    public function setValue($value)
    {
        $this->hiddenBox->att('value',$value);
    }
}
