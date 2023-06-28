<?php
namespace Osynapsy\Bcl4;

/**
 * Description of TextIMaskBox
 *
 * @author peter
 */
class TextIMaskBox extends TextBox
{
    const IMASK_INTEGER = 10;
    const IMASK_NUMBER = 11;
    const IMASK_CURRENCY = 12;
    const IMASK_DATE = 20;

    protected $imask = [
        self::IMASK_INTEGER => [
            'id' => 'Number',
            'class' => 'text-right'
        ],
        self::IMASK_NUMBER  => [
            'id' => 'Number',
            'class' => 'text-right'
        ],
        self::IMASK_CURRENCY => [
            'id' => 'Currency',
            'class' => 'text-right'
        ],
        self::IMASK_DATE => [
            'id' => 'Date',
            'class' => 'text-center'
        ]
    ];

    public function __construct($name, $class = '')
    {
        parent::__construct($name, $class);
        $this->requireJs('Lib/imask-6.0.5/imask.js');
        $this->requireJs('Bcl4/InputMask/script.js');
    }

    public function setIMask($iMaskId)
    {
        if (!array_key_exists($iMaskId, $this->imask)) {
            throw new \Exception("TextBox {$this->id} : iMask format {$iMaskId} not regnized");
        }
        $mask = $this->imask[$iMaskId];        
        $this->addClass(trim('input-mask '.$mask['class']));
        $this->attribute('data-imask', $mask['id']);
    }
}
