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
use Osynapsy\Html\Component;

/**
 * Description of Dropdown
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Dropdown extends Component
{
    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';

    private $button;
    private $align;

    public function __construct($name, $label, $align = self::ALIGN_LEFT, $tag = 'div')
    {
        parent::__construct($tag);
        $this->setClass('dropdown');
        $this->add($this->buildMainButton($name, $label));
        $this->align = $align;
    }

    private function buildMainButton($name, $label)
    {
        $this->button = new Button($name.'_btn', $label, 'dropdown-toggle');
        $this->button->att([
            'data-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false'
        ]);
        return $this->button;
    }

    protected function __build_extra__()
    {
        $list = $this->add(new Tag('div', null, 'dropdown-menu dropdown-menu-'.$this->align));
        $list->att('aria-labelledby', $this->getButton()->id);
        foreach ($this->data as $rec) {
            if (is_object($rec)) {
                $list->add($rec)->att('class', 'dropdown-item', true);
                continue;
            }
            if ($rec === 'divider') {
                $list->add(new Tag('div', null, 'dropdown-divider'));
                continue;
            }
        }
    }

    public function getButton()
    {
        return $this->button;
    }
}
