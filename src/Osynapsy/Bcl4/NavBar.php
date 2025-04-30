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

/**
 * Build a Bootstrap NavBar
 *
 */
class NavBar extends AbstractComponent
{
    protected $brand;
    protected $navbarToggler;
    protected $containerClass;

    /**
     * Constructor require dom id of component
     *
     * @param string $id
     */
    public function __construct($id, $class = null)
    {
        parent::__construct('nav', $id);
        $this->requireCss('bcl4/navbar/style.css');
        $this->requireJs('bcl4/navbar/script.js');
        $this->setDataset([],[]);
        if (!empty($class)) {
            $this->addClass($class);
        }
    }

    /**
     * Main builder of navbar
     *
     */
    public function preBuild()
    {
        $this->addClass('osy-bcl4-navbar navbar navbar-expand-sm');
        $this->buildHeader();
        $collapsable = $this->add(new Tag('div', $this->id.'Content', 'collapse navbar-collapse'));
        if (empty($this->dataset)) {
            return;
        }
        if (!empty($this->dataset['primary'])) {
            $collapsable->add($this->buildUlMenu($this->dataset['primary'])->addClass('mr-auto'));
        }
        if (!empty($this->dataset['secondary'])) {
            $ul = $this->buildUlMenu($this->dataset['secondary'], 0 ,'dropdown-menu dropdown-menu-lg-right');
            $ul->addClass('justify-content-end');
            $collapsable->add($ul);
        }
        if (!empty($this->dataset['custom_right'])) {
            $collapsable->add($this->dataset['custom_right']);
        }
    }

    /**
     * Internal method for build header part of navbar
     *
     * @param type $container
     * @return type
     */
    private function buildHeader()
    {
        if (!empty($this->brand)) {
            $this->add($this->brand);
        }
        if (!empty($this->navbarToggler)) {
            $this->add($this->buttonNavBarTogglerFactory());
        }
    }

    protected function buttonNavBarTogglerFactory()
    {
        $Button = new Tag('button', null, 'navbar-toggler');
        $Button->attributes([
            'type' => "button",
            'data-toggle' => "collapse",
            'data-target' => "#".$this->id.'Content',
            'aria-controls' => $this->id.'Content',
            'aria-expanded' => "false",
            'aria-label' => "Toggle navigation"
        ]);
        $Button->add('<span class="navbar-toggler-icon fa fa-bars text-white"></span>');
        return $Button;
    }

    /**
     * Internal method for build a unordered list menù (recursive)
     *
     * @param array $data
     * @param int $level
     * @param string $dropdownMenuClass class to apply drowdownMenu
     * @return type
     */
    private function buildUlMenu(array $data, $level = 0, $dropdownMenuClass = 'dropdown-menu')
    {
        //Add ul menù container;
        $ul = new Tag('ul', null, empty($level) ? 'navbar-nav nav' : $dropdownMenuClass);
        if (empty($data) || !is_array($data)) {
            return $ul;
        }
        foreach($data as $label => $menu){
            $li = $ul->add(new Tag('li', null, 'nav-item'));
            $li->attribute('role', 'navigation');
            if ($menu === 'hr'){
                $li->add($this->getNavDivider());
                continue;
            }
            if (!is_array($menu)) {
                $li->add($this->getNavLink($label, $menu, $level));
                continue;
            }
            $li->add($this->getNavDropdownLink($label, $level));
            $li->addClass(empty($level) ? 'dropdown' : 'dropdown-submenu')->add(
                $this->buildUlMenu($menu, $level + 1, $dropdownMenuClass)
            );
        }
        return $ul;
    }

    private function getNavDropdownLink($label, $level)
    {
        $a = new Tag('a', null, 'dropdown-toggle '.(empty($level) ? 'nav-link' : 'dropdown-item'));
        $a->attributes([
            'href' => '#',
            'data-toggle' => 'dropdown',
            'aria-expanded' => 'false',
            'aria-haspopup' => 'true'
        ])->add($label);
        return $a;
    }

    private function getNavLink($label, $url, $level)
    {
        $a = new Tag('a', null, empty($level) ? 'nav-link' : 'dropdown-item');
        $a->attribute('href', $url)->add($label);
        return $a;
    }

    private function getNavDivider()
    {
        return new Tag('div', null, 'dropdown-divider');
    }

    /**
     * Decide if use fluid (true) or static container (false)
     *
     * @param type $bool
     * @return $this
     */
    public function setContainerFluid($bool = true)
    {
        $this->containerClass = 'container'.($bool ? '-fluid' : '');
        return $this;
    }

    /**
     * Set brand identity (logo, promo etc) to start menù
     *
     * @param string $label is visual part of brand
     * @param string $href is url where user will be send if click brand
     * @return $this
     */
    public function setBrand($label, $href = '#', $prefix = null)
    {
        $this->brand = new Tag('dummy');
        if (!empty($prefix)) {
            $this->brand->add($prefix);
        }
        return $this->brand->add(new Link(false, $href, $label, 'navbar-brand'));
    }

    /**
     * Set data necessary for build NavBar.
     *
     * @param array $primary set main menu data (near brand)
     * @param array $secondary set second menù aligned to right
     * @param string $custom_right custom menu aligned to right
     * @return $this Navbar component
     */
    public function setDataMenu(array $primary, array $secondary = [], $custom_right = null)
    {
        $this->dataset['primary'] = $primary;
        $this->dataset['secondary'] = $secondary;
        $this->dataset['custom_right'] = $custom_right;
        return $this;
    }

    /**
     * Fix navigation bar on the top of page (navbar-fixed-top class on main div)
     *
     * @return $this
     */
    public function setFixedOnTop()
    {
        $this->addClass('fixed-top');
        return $this;
    }

    public function setToggler(bool $visibility)
    {
        $this->navbarToggler = $visibility;
    }
}
