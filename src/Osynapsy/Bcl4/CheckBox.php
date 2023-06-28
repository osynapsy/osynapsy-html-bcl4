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

use Osynapsy\Html\Component\CheckBox as CheckBoxBase;

/**
 * Description of CheckBox
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CheckBox extends CheckBoxBase
{
    protected $checkbox;

    public function __construct($id, $label, $value = '1', $prefix = '')
    {
        parent::__construct($id, $label, $value, 'label');
        $this->addClass('form-check-label');
        if (!empty($prefix)) {
            $this->add($prefix);
        }        
    }   
}
