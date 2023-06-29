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
use Osynapsy\Html\Component\InputHidden;

//Costruttore del pannello html
class PanelAccordion extends AbstractComponent
{
    private $panels = array();

    public function __construct($id)
    {        
        parent::__construct('div', $id);
        $this->requireCss('bcl4/panelaccordion/style.css');
        $this->requireJs('bcl4/panelaccordion/script.js');
        $this->addClass('panel-group osy-panel-accordion');
        $this->attribute('role', 'tablist');
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
        $panelId = $this->id.$panelIdx;
        $panelTitle = '<a data-toggle="collapse" data-parent="#'.$this->id.'" href="#'.$panelId.'-body" data-panel-id="'.$panelIdx.'" class="'.(filter_input(\INPUT_POST, $this->id) == $panelIdx ? 'collapsed' : '').'" onclick="">'.$title.'</a>';
        $this->panels[] = new Panel($panelId, $panelTitle);
        $this->panels[$panelIdx]
             ->addCommands($commands)
             ->getBody()
             ->att('id', $panelId.'-body');
        $this->panels[$panelIdx]->addClass('panel-body collapse' .(filter_input(\INPUT_POST, $this->id) == $panelIdx ? ' in' : ''));
        return $this->panels[$panelIdx];
    }
}
