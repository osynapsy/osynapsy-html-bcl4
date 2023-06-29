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
use Osynapsy\Html\Component\Link;

class ContextMenu extends AbstractComponent
{    
    private $dropdown;

    public function __construct($id, $class='')
    {        
        parent::__construct('div', $id);
        $this->requireCss('bcl4/contextmenu/style.css');
        $this->requireJs('bcl4/contextmenu/script.js');
        $this->addClass('BclContextMenu dropdown clearfix ' . $class);
        $this->dropdown = $this->add($this->dropdownFactory());
    }

    protected function dropdownFactory()
    {
        $Dropdown = new Tag('ul', null,'dropdown-menu');
        $Dropdown->attributes([
            'role' => 'menu',
            'aria-labelledby' => 'dropdownMenu',
            'style' => 'display: block; position: static; margin-bottom: 5px;'
        ]);
        return $Dropdown;
    }

    public function addAction($label, $action, array $parameters = [])
    {
        $Link = $this->dropdown->add(new Tag('li'))->add(new Link(false, false, $label));
        $Link->attributes(['data-action' => $action,'data-action-param' => implode(',', $parameters)]);
        return $Link;
    }
}