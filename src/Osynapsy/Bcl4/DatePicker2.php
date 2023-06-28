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

class DatePicker2 extends AbstractComponent
{
    const FORMAT_DATE_IT = 'DD/MM/YYYY';
    const FORMAT_DATETIME_IT = 'DD/MM/YYYY HH:mm';

    private $datePickerId;
    private $dateComponent;
    protected $defaultValue;
    protected $format = 'DD/MM/YYYY';

    public function __construct($id)
    {
        $this->datePickerId = $id;        
        parent::__construct('div', $id.'_datepicker');
        $this->requireCss('Lib/tempusdominus-5.38.0/style.css');
        $this->requireJs('Lib/momentjs-2.17.1/moment.js');
        $this->requireJs('Lib/tempusdominus-5.38.0/script.js');
        $this->requireJs('Bcl4/DatePicker/script.js');
        $this->attributes(['class' => 'input-group date date-picker' , 'data-target-input'=> 'nearest']);
        $this->dateComponent = $this->add($this->fieldDateBoxFactory());
        $this->fieldInputGruopAppendFactory();
    }

    protected function fieldInputGruopAppendFactory()
    {
        $this->add('<div class="input-group-append" data-target="#'.$this->id.'" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div>');
    }

    protected function fieldDateBoxFactory()
    {
        $TextBox = new TextBox($this->datePickerId);
        $TextBox->attributes([
            'class' => 'form-control datetimepicker-input text-center',
            'data-toggle' => 'datetimepicker',
            'data-target' => sprintf('#%s',$this->id)
        ]);
        $TextBox->formatValueFunction = function($value)
        {
            if (empty($value)) {
                return $value;
            }
            $dateTimeParts = explode(' ', $value);
            $dateParts = explode('-', $dateTimeParts[0]);
            if (count($dateParts) >= 3 && strlen($dateParts[0]) == 4) {
                return $dateParts[2].'/'.$dateParts[1].'/'.$dateParts[0].(empty($dateTimeParts[1]) ? '' : " {$dateTimeParts[1]}");
            }
        };
        return $TextBox;
    }

    public function preBuild()
    {
        $this->attribute('data-date-format', $this->format);
        if (!empty($this->defaultValue) && empty($this->getTextBox()->getValue())) {
            $this->getTextBox()->setValue($this->defaultValue);
        }
    }

    public function getTextBox()
    {
        return $this->dateComponent;
    }

    /**
     *
     * @param type $min accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     * @param type $max accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateLimit($min, $max)
    {
        $this->setDateMin($min);
        $this->setDateMax($max);
    }

    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMax($date)
    {
        $this->dateComponent->att('data-max', $date);
    }
    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMin($date)
    {
        $this->dateComponent->att('data-min', $date);
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setDefaultDate($date = null)
    {
        $this->defaultValue = empty($date) ? date('d/m/Y') : $date;
    }

    public function onChange($code)
    {
        $this->att('onchangedate', $code);
    }

    public function setAction($action, $parameters = null, $confirmMessage = null, $class = 'change-execute')
    {
        parent::setAction($action, $parameters, $confirmMessage, $class);
    }

    public function setDisabled($condition)
    {
        $this->dateComponent->setDisabled($condition);
    }

    public function setReadOnly($condition)
    {
        $this->dateComponent->setReadOnly($condition);
    }   
}
