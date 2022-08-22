<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4;

class ComboBoxTree extends Osynapsy\Ocl\ComboBoxTree
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setClass('form-control');
    }
}
