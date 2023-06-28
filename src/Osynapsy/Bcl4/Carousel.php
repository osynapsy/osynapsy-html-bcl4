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

/**
 * Description of Carousel
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Carousel extends AbstractComponent
{
    private $showCommands;
    private $showIndicators;

    public function __construct($id, $showCommands = false, $showIndicators = false)
    {
        parent::__construct('div', $id);
        $this->showCommands = $showCommands;
        $this->showIndicators = $showIndicators;
    }

    public function preBuild()
    {
        if (!empty($this->data)) {
            return;
        }
        if ($this->showIndicators) {
            $this->buildIndicators();
        }
        $inner = $this->add(new Tag('div', null, 'carousel-inner'));
        foreach($this->data as $key => $rec) {
            $item = $inner->add($this->buildItem($rec));
            if (empty($key)) {
                $item->att('class', 'active', true);
            }
        }
        if ($this->showCommands) {
            $this->buildCommands($inner);
        }
    }

    private function buildItem($rec)
    {
        $div = new Tag('div', null, 'carousel-item');
        $div->add(new Tag('img', null, 'd-block w-100'))->att(array_pop($rec));
        $this->buildItemCaption($rec, $div);
        return $div;
    }

    private function buildItemCaption($rec, $item)
    {
        if (empty($rec)) {
            return;
        }
        $caption = $item->add(new Tag('div', null, 'carousel-caption d-none d-md-block'));
        $texts = array_values($rec);
        if (empty($texts[0])) {
            $caption->add(new Tag('h5'))->add($text[0]);
        }
        if (empty($texts[1])) {
            $caption->add(new Tag('p'))->add($text[1]);
        }
    }

    private function buildCommands($inner)
    {
        foreach(['prev','next'] as $cmd) {
            $a = $inner->add(new Tag('a', null, 'carousel-control-'.$cmd));
            $a->att([
                'href' => "#carouselExampleControls",
                'role'=> "button",
                'data-slide' => "prev"
            ]);
            $a->add(new Tag('span', null, "carousel-control-{$cmd}-icon"))
              ->att('aria-hidden', "true");
            $a->add(new Tag('span', null, 'sr-only'))->add($cmd);
        }
    }

    private function buildIndicators()
    {
        $ol = $this->add(new Tag('ol', null, 'carousel-indicators'));
        foreach(array_keys($ol) as $key) {
            $li = $ol->add(new Tag('li', null, empty($key) ? 'active' : null));
            $li->att(['data-target' => "#{$this->id}", "data-slide-to" => $key]);
        }
    }
}
