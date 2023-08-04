<?php
namespace Osynapsy\Bcl4;

use Osynapsy\Html\Component\InputDate;

/**
 * Description of DatePicker
 *
 * @author pietro
 */
class DateBox extends InputDate
{
    public function __construct($name, $class = '')
    {
        parent::__construct($name, trim('form-control '.$class));
    }
}
