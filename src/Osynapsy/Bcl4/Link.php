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

use Osynapsy\Html\Component\Link as BaseLink;

class Link extends BaseLink
{
    public function inModal($widht = '640px', $height = '480px', $postData = false, $title = null)
    {
        $this->addClass('open-modal');
        $this->attributes([
            'title' => strip_tags($title ?? $this->getChild()),
            'modal-width' => $widht,
            'modal-height' => $height
        ]);
        if ($postData) {
            $this->addClass('postdata');
        }
        return $this;
    }

    public function setDisabled($condition)
    {
        if ($condition) {
            $this->attributes([
                'href' => 'javascipt:void(0);',
                'onclick' => 'event.stopPropagation();'
            ]);
        }
        return $this;
    }
}
