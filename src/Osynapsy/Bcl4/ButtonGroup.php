<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4;

use Osynapsy\Html\Component;
use Osynapsy\Html\Tag;
/**
 * Impelementation of Bootstrap ButtonGroup
 *
 * @author Pietro Celeste
 */
class ButtonGroup extends Component
{
    protected $ul;
    protected $b1;
    protected $b2;

    public function __construct($name, $label, $class='')
    {
        parent::__construct('div', $name);
        $this->att('class','btn-group');
        $this->b1 = $this->add(new Button('btn1'.$name, $label, $class));
        $this->b2 = $this->add(new Button('btn2'.$name, '<span class="caret"></span>', "btn dropdown-toggle $class"))
        ->att('data-toggle','dropdown')
        ->att('aria-haspopup','true')
        ->att('aria-expandend','false');
        $this->b2->add('<span class="sr-only">Toggle Dropdown</span>');

        //Menu container
        $this->ul = $this->add(new Tag('ul'))->att('class','dropdown-menu');
    }

    public function push($item)
    {
        $li = $this->ul->add(new Tag('li'));
        $li->add($item);
        return is_string($item) ? $this : $item;
    }

    public function addSeparator()
    {
        $this->ul->add(new Tag('li'))->att('class','divider')->att('role','separator');
    }
}
