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

class ContextMenu extends AbstractComponent
{
    private $actions = array();
    private $ul;

    public function __construct($id, $link, $label, $class='')
    {
        $this->requireCss('Bcl4/ContextMenu/style.css');
        $this->requireJs('Bcl4/ContextMenu/script.js');
        parent::__construct('div', $id);
        $this->att('class', 'BclContextMenu dropdown clearfix');
        $this->ul = $this->add(new Tag('ul'))
                         ->att('class','dropdown-menu')
                         ->att('role','menu')
                         ->att('aria-labelledby','dropdownMenu')
                         ->att('style','display: block; position: static; margin-bottom: 5px;');

    }

    public function addAction($label, $action, $params='')
    {
        $this->ul
             ->add(new Tag('li'))
             ->add(new Tag('a'))
             ->att('href','javascript:void(0);')
             ->att('data-action',$action)
             ->att('data-action-param',$params)
             ->add($label);
    }
}