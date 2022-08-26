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

/**
 * Description of IPagination
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
interface IPagination
{
    public function setParentComponent(string $id);

    public function loadData($requestPage, $exceptionOnError = false);

    public function getOrderBy();

    public function getPageDimensionsCombo();

    public function getStatistic($key);

    public function getMeta($key);
}
