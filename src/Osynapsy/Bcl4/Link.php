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

use Osynapsy\Ocl\Link as OclLink;

class Link extends OclLink
{
    public function openInModal($title, $widht = '640px', $height = '480px', $postData = false)
    {
        $this->setClass('open-modal')->att([
            'title' => $title,
            'modal-width' => $widht,
            'modal-height' => $height
        ]);
        if ($postData) {
            $this->addClass('postdata');
        }
    }

    public function setDisabled($condition)
    {
        if ($condition) {
            $this->att(['href' => 'javascipt:void(0);', 'onclick' => 'event.stopPropagation();']);
        }
        return $this;
    }
}
