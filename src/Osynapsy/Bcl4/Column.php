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
use Osynapsy\Html\Tag;

class Column extends AbstractComponent
{
    private $size = array(
        'lg' => array('width' => null, 'offset' => 0),
        'md' => array('width' => null, 'offset' => 0),
        'sm' => array('width' => null, 'offset' => 0),
        'xs' => array('width' => null, 'offset' => 0)
    );

    public function __construct($size = 2, $offset = 0)
    {
        parent::__construct('div');
        if (empty($size)) {
            return;
        }
        $this->setLg($size, $offset);
        $this->setMd($size, $offset);
        $this->setSm($size, $offset);
        $this->setXs(0, 0);
    }

    public function preBuild()
    {
        foreach ($this->size as $size => $dimension) {
            if (empty($dimension['width'])) {
                $this->addClass('col');
                continue;
            }
            $class = $size === 'xs' ? 'col-'.$dimension['width'] : 'col-'.$size.'-'.$dimension['width'];
            if (!empty($dimension['offset'])) {
                $class .= ' col-'.$size.'-offset-'.$dimension['offset'];
                $class .= ' offset-'.$size.'-'.$dimension['offset'];
            }
            $this->addClass($class);
        }
    }

    public function setLg($size, $offset = 0)
    {
        $this->size['lg']['width'] = $size;
        $this->size['lg']['offset'] = $offset;
         return $this;
    }

    public function setMd($size, $offset = 0)
    {
        $this->size['md']['width'] = $size;
        $this->size['md']['offset'] = $offset;
         return $this;
    }

    public function setSm($size, $offset = 0)
    {
        $this->size['sm']['width'] = $size;
        $this->size['sm']['offset'] = $offset;
         return $this;
    }

    public function setXs($size, $offset = 0)
    {
        $this->size['xs']['width'] = $size;
        $this->size['xs']['offset'] = $offset;
        return $this;
    }

    public function push($label, $object, $sublabel = '', $class = 'form-group')
    {
        if ($object instanceof Tag) {
            $object->attribute('data-label', strip_tags(is_array($label) ? $label[0] : $label));
        }
        $this->add(new FormGroup($object, $label, $class))->setInfo($sublabel);
        return $this;
    }
}
