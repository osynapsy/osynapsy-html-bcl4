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
        $this->requireJs('Bcl4/ListBox/script.js');
        $this->requireCss('Bcl4/ListBox/style.css');
        parent::__construct('div', $id.'_container');
        $this->addClass('listbox');
        $this->hdn = $this->add(new InputHidden($id));
        $this->box = $this->add(new Tag('div', null, 'listbox-box'));
    }

    public function preBuild()
    {
        $list = $this->add(new Tag('ul', null, 'listbox-list'));
        foreach ($this->dataset as $rec) {
            $selected = '';
            if (array_key_exists($this->hdn->id, $_REQUEST) && ($rec[0] == $_REQUEST[$this->hdn->id])) {
                $this->box->set($rec[1]);
                $selected = ' selected';
            }
            $list->add(new Tag('li'))
                 ->add(new Tag('div', null,'listbox-list-item'.$selected))
                 ->attribute('value',$rec[0])
                 ->add($rec[1]);
        }
    }   
}
