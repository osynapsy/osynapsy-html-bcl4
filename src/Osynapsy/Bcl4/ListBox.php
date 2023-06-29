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


class ListBox extends AbstractComponent
{
    public $data = array();
    private $hdn;
    private $box;

    public function __construct($id)
    {        
        parent::__construct('div', $id.'_container');
        $this->requireJs('bcl4/listbox/script.js');
        $this->requireCss('bcl4/listbox/style.css');
        $this->addClass('listbox');
        $this->hdn = $this->add(new InputHidden($id));
        $this->box = $this->add(new Tag('div', null, 'listbox-box'));
    }

    public function preBuild()
    {
        $list = $this->add(new Tag('ul', null, 'listbox-list'));
        foreach ($this->dataset as $rec) {
            $selected = ($this->hdn->getAttribute('value') == $rec[0]);
            $list->add($this->listItemFactory($rec[0], $rec[1], $selected));
        }
    }

    protected function listItemFactory($value, $label, $selected)
    {
        $listItem = new Tag('li');
        $item = $listItem->add(new Tag('div', null, 'listbox-list-item' . $selected ? ' selected' : ''));
        $item->attribute('value', $value)->add($label);
        return $listItem;
    }
}
