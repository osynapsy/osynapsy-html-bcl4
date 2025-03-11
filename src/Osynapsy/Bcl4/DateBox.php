<?php
namespace Osynapsy\Bcl4;

use Osynapsy\Html\Component\InputDate;

/**
 * Description of DateBox
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class DateBox extends InputDate
{
    public function __construct($name, $class = '')
    {
        parent::__construct($name, trim('form-control '.$class));
        $this->attribute('type', 'date');
    }
}
