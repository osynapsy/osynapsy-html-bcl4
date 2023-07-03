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

//Costruttore del pannello html
class Accordion extends AbstractComponent
{
    private $panels = array();
    private $defaultOpen  = 0;

    public function __construct($id, $defaultOpen = 0)
    {        
        parent::__construct('div', $id);
        $this->requireCss('bcl4/accordion/style.css');
        $this->requireCss('bcl4/panelaccordion/style.css');
        $this->addClass('accordion osy-panel-accordion');
        $this->attribute('role','tablist');
        $memoryOpen = filter_input(\INPUT_POST, $this->id);
        $this->defaultOpen = is_null($memoryOpen) ? $defaultOpen : $memoryOpen;
    }

    public function preBuild()
    {
        $this->add(new InputHidden($this->id));
        foreach($this->panels as $panel) {
            $this->add($panel);
        }
    }

    public function addPanel($title, $commands = [])
    {
        $panelIdx = count($this->panels);
        $panelId = $this->id.'_'.$panelIdx;
        $open = $this->defaultOpen === $panelIdx ? true : false;
        //$panelHd = '<a data-toggle="collapse" data-parent="#'.$this->id.'" href="#'.$panelId.'-body" data-panel-id="'.$panelIdx.'" class="'.(filter_input(\INPUT_POST, $this->id) == $panelIdx ? 'collapsed' : '').'" onclick="">'.$title.'</a>';
        $panelHd = $this->buildHeader($title, $panelId.'_body', $open);
        $panel = new Panel($panelId, $panelHd);
        $panel->setClass(
            'card-body collapse'.($open ? ' show' : ''),
            'card-header',
            'card-foot',
            'card',
            ''
        );
        $panel->addCommands($commands)->getBody()->attributes([
            'id' => $panelId.'_body',
            'data-parent' => '#'.$this->id
        ]);
        $this->panels[] = $panel;
        return $this->panels[$panelIdx];
    }

    private function buildHeader($title, $targetId, $open)
    {
        $h5 = new Tag('h5', null, 'mb-0');
        $span = $h5->add(new Tag('span', null, ' text-left  collapsed'));
        $span->attributes([
            'type' => 'button',
            'data-toggle' => 'collapse',
            'role' => 'button',
            'data-target' => '#'.$targetId,
            'aria-expanded' => empty($open) ? 'false' : 'true',
            'aria-controls' => $targetId
        ])->add($title);
        return $h5;
    }
}
