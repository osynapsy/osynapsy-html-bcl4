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

/**
 * Represents a Html Button.
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class Button extends \Osynapsy\Ocl\Button
{
    /**
     * Constructor of button component
     *
     * @param string $id
     * @param string $type button|submit
     * @param string $class extra css class to add to button
     * @param string $label text of the button
     */
    public function __construct($id, $label = '', $class = 'btn-primary')
    {
        parent::__construct($id, $label, sprintf('btn %s', $class));
    }
}
