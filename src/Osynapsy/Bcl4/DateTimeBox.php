<?php
namespace Osynapsy\Bcl4;

use Osynapsy\Html\Component\InputDateTime;

/**
 * Description of DatePicker
 *
 * @author pietro
 */
class DateTimeBox extends InputDateTime
{
    public function __construct($name, $class = '')
    {
        parent::__construct($name, trim('form-control '.$class));
    }
}
