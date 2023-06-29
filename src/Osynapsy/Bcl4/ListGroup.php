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

use Osynapsy\Html\Component\AbstractComponent;

/**
 * Description of ListGroup
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class ListGroup extends AbstractComponent
{
    protected $repo = [];
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
        parent::__construct('div', $id);
        $this->addClass('list-group');
    }

    public function preBuild(): void
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
        $this->attribute('style', sprintf('width : %s; top : %s; left : %s;', $width, $top, $left));
    }
}
