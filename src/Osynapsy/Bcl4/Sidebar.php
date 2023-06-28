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

/**
 * Description of Sidebar
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Sidebar extends AbstractComponent
{
    protected $button;
    protected $sidebar;

    public function __construct($id)
    {
        parent::__construct('dummy', 'dummy'.$id);
        $this->setClass('sidebar');
        $this->requireCss('Bcl4/Sidebar/style.css');
        $this->requireJs('Bcl4/Sidebar/script.js');
        $this->button = $this->buttonOpenSidebarFactory($id);
        $this->sidebar = parent::add($this->sidebarFactory($id));
    }

    protected function buttonOpenSidebarFactory($id)
    {
        $button = new Tag('button', 'btnOpen'.$id, 'btn btn-xs btn-default bcl4-sidebar-command');
        $button->att(['data-target' => $id, 'type' => 'button']);
        $button->add('&#9776;');
        return $button;
    }

    protected function sidebarFactory($id)
    {
        $sidebar = new Tag('div', $id, 'sidebar d-none');
        $sidebar->att('data-is-open', '0');
        $sidebar->add($this->buttonCloseFactory($id));
        return $sidebar;
    }

    public function buttonCloseFactory($id)
    {
        $button = new Tag('span', null, 'fa fa-times text-secondary float-right bcl4-sidebar-command mr-4');
        $button->att(['data-target' => $id]);
        return $button;
    }

    public function add($child)
    {
        return $this->getSidebar()->add($child);
    }

    public function getButton()
    {
        return $this->button;
    }

    public function getSidebar()
    {
        return $this->sidebar;
    }
}
