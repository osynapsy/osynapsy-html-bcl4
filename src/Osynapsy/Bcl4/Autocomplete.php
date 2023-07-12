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
use Osynapsy\Html\Component\InputHidden;

class Autocomplete extends AbstractComponent
{
    protected $emptyMessage = 'No value match ';
    protected $autocompleteclass = ['osy-autocomplete'];
    protected $ico = '<span class="fa fa-search"></span>';
    protected $decodeEntityIdFunction;
    protected $datasourceFunction;
    protected $hiddenField;

    public function __construct($id)
    {
        parent::__construct('div', $id);
        $this->requireJs('bcl4/autocomplete/script.js');
        $this->requireCss('bcl4/autocomplete/style.css');
        $this->addClass('osy-autocomplete');
    }

    public function preBuild()
    {
        if (filter_input(\INPUT_SERVER, 'HTTP_OSYNAPSY_HTML_COMPONENTS') != $this->id) {
            $value = $this->add(new InputHidden('__'.$this->id))->getValue();
            $this->add($this->inputMaskFactory($value));
            return;
        }
        $userQuery = filter_input(\INPUT_POST, $this->id);
        $dataset = $this->loadDataset($userQuery);
        $this->add($this->valueListFactory($dataset, $userQuery));
    }

    protected function inputMaskFactory($value)
    {
        $Autocomplete = new InputGroup($this->id, '', $this->ico);
        $Autocomplete->getTextBox()->onselect = 'event.stopPropagation();';
        if (!empty($this->decodeEntityIdFunction)) {
            $function = $this->decodeEntityIdFunction;
            $Autocomplete->getTextBox()->setValue($function($value));
        }
        return $Autocomplete;
    }

    protected function getDecodedValue()
    {
        list($decodeSql, $decodeSqlParams) = $this->query['decode'];
        return !empty($decodeSql) ? $this->db->findOne($decodeSql, $decodeSqlParams ?? []) : null;
    }

    protected function loadDataset($query)
    {
        return ($this->datasourceFunction)($query);
    }

    protected function valueListFactory($dataset, $userQuery)
    {
        $valueList = new Tag('div', $this->id.'_list');
        if (empty($dataset) || !is_array($dataset)) {
            $valueList->add($this->emptyListMessageFactory($userQuery));
            return $valueList;
        }
        foreach ($dataset as $rec) {
            $val = array_values($rec);
            if (empty($val) || empty($val[0])) {
                continue;
            }
            switch (count($val)) {
                case 1:
                    $val[1] = $val[2] = $val[0];
                    break;
                case 2:
                    $val[2] = $val[1];
                    break;
            }

            $val[2] = str_replace($userQuery, '<b>'.$userQuery.'</b>', $val[2]);
            $valueList->add('<div class="item" data-value="'.$val[0].'" data-label="'.$val[1].'">'.$val[2].'</div>'.PHP_EOL);
        }
        return $valueList;
    }

    protected function emptyListMessageFactory($userQuery)
    {
        return sprintf('<div class="item empty-message">%s&nbsp;<b>%s</b>&nbsp;query</div>', $this->emptyMessage, $userQuery);
    }

    public function setLabel($label)
    {
        $_REQUEST[$this->id] = $label;
        return $this;
    }

    public function setEmptyMessage($msg)
    {
        $this->emptyMessage = $msg;
        return $this;
    }

    public function onSelect($function)
    {
        $this->onselect = $function;
        return $this;
    }

    public function onUnSelect($function)
    {
        $this->onunselect = $function;
        return $this;
    }

    public function setIco($ico)
    {
        $this->ico = $ico;
    }

    public function addAutocompleteClass($class)
    {
        $this->autocompleteclass[] = $class;
    }

    public function setDecodeEntityId(callable $decodeFunction)
    {
        $this->decodeEntityIdFunction = $decodeFunction;
    }

    public function setDatasource(callable $datasourceFunction)
    {
        $this->datasourceFunction = $datasourceFunction;
    }
}
