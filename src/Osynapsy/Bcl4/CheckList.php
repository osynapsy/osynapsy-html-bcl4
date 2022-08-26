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

use Osynapsy\Ocl\CheckList as OclCheckList;

/**
 * Build a list of check
 *
 */
class CheckList extends OclCheckList
{
    /**
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->requireCss('Bcl4/CheckList/style.css');
    }
}
