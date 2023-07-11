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

use Osynapsy\Html\Component\CheckList as BaseCheckList;

/**
 * Build a list of check
 *
 */
class CheckList extends BaseCheckList
{
    /**
     *
     * @param string $id 
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->requireCss('bcl4/checkList/style.css');
    }
}
