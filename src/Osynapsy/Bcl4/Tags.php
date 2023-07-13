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

class Tags extends AbstractComponent
{
    private $labelClass;
    private $modal;
    private $dropdown;
    private $hidden;
    private $autocomplete;

    public function __construct($name, $class = "badge badge-info")
    {
        parent::__construct('div', $name);
        $this->hidden = $this->add(new InputHidden($name));
        $this->requireJs('bcl4/tags/script.js');
        $this->requireCss('bcl4/tags/style.css');
        $this->labelClass = $class;
    }

    public function preBuild()
    {
        $this->addClass('bclTags');
        $wrapper = $this->add(new Tag('div', null, 'bclTags-container d-inline'));
        if (!empty($_REQUEST[$this->id])) {
            $wrapper->add($this->tagsFactory($_REQUEST[$this->id]));
        }
        if (!empty($this->autocomplete)) {
            $this->add($this->autocomplete);
        }
        if (!empty($this->modal)) {
            $buttonAdd = $this->add(new Button('btn'.$this->id, '<span class="fa fa-plus"></span>', 'btn-info btn-xs'));
            $buttonAdd->attribute('data-toggle','modal')->attribute('data-target','#modal'.$this->id);
        }
        if (!empty($this->dropdown)) {
            $this->add($this->dropdown);
        }
    }

    protected function tagsFactory($strTags)
    {
        $result = new Tag('dummy');
        $tags = explode('][', $strTags);
        foreach($tags as $tag) {
            $result->add($this->tagFactory($tag));
        }
        return $result;
    }

    protected function tagFactory($tag)
    {
        $result = (new Tag('span', null, $this->labelClass))->attribute('data-parent',sprintf('#%s', $this->id));
        $result->add(str_replace(['[',']'], '', $tag));
        $result->add('&nbsp');
        $result->add(new Tag('span', null, 'fa fa-close bclTags-delete'));
        return $result;
    }

    protected function buttonModalCloseFactory()
    {
        $Button = new Button('clsModal'.$this->id, 'Annulla', 'btn-default pull-left float-left');
        $Button->attribute('data-dismiss','modal');
        return $Button;
    }

    public function addModal($title, $body, $buttonAdd)
    {
        $this->modal = $this->add(new Modal('modal'.$this->id, $title));
        $this->modal->addBody($body);
        $this->modal->addFooter($this->buttonModalCloseFactory());
        if (is_object($buttonAdd)) {
            $buttonAdd->attribute('class', 'bclTags-add', true)
                      ->attribute('data-parent', '#'.$this->id);
        }
        $this->modal->addFooter($buttonAdd);
    }

    public function addDropDown($label, $dataset)
    {
        $this->dropdown = new Dropdown($this->id.'_list', $label, 'span');
        $this->dropdown->setDataset($dataset);
    }

    public function addAutoComplete(array $data = [])
    {
        $ajax = filter_input(\INPUT_POST, 'ajax');
        if (empty($ajax)) {
            return $this->autocomplete = $this->autocompleteTextBoxFactory();
        }
        if ($ajax != $this->id.'_auto') {
            return;
        }
        $Autocomplete = new Autocomplete($this->id.'_auto');
        $Autocomplete->setData($data);
        die($Autocomplete);
    }

    protected function autocompleteTextBoxFactory()
    {
        $autocomplete = new Autocomplete($this->id.'_auto');
        $autocomplete->addClass('input-group-sm');
        $autocomplete->attributes([
            'style' =>'width: 150px; margin-top: 3px;',
            'class' => 'd-inline-block'
        ]);
        $autocomplete->onSelect("\$('#{$this->id} span.fa-plus').click()");
        $autocomplete->setIco('<span class="fa fa-plus tag-append" onclick="BclTags.addTag(\'#'.$this->id.'\');"></span>');
        return $autocomplete;
    }
}
