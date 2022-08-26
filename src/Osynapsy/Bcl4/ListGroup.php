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

use Osynapsy\Html\Component;

/**
 * Description of ListGroup
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class ListGroup extends Component
{
    protected $repo = [];
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
        parent::__construct('div', $id);
        $this->setClass('list-group');
    }

    public function __build_extra__(): void
    {
        foreach ($this->repo as $item) {
            $this->add($item);
        }
    }

    public function addLink($label, $uri)
    {
        $id = count($this->repo);
        $this->repo[$id] = new Link(sprintf('%s_%s', $this->id, $id), $uri, $label, 'list-group-item list-group-item-action');
    }

    public function setFixedPosition($width = '100%', $top = '0px', $left = '0px')
    {
        $this->addClass('position-fixed d-none d-lg-block');
        $this->att('style', sprintf('width : %s; top : %s; left : %s;', $width, $top, $left));
    }
}
