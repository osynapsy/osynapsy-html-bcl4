<?php
namespace Osynapsy\Bcl4;

use Osynapsy\Html\Component\AbstractComponent;
use Osynapsy\Html\Component\RadioBox;

/**
 * Description of RadioList
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class RadioList extends AbstractComponent
{
    public $space = '&nbsp;';

    public function __construct($id = null)
    {
        parent::__construct('div', $id);
    }

    public function preBuild()
    {
        $request = $_REQUEST[$this->id] ?? null;
        $j = 0;
        foreach($this->dataset as $i => $row) {
            $v = is_array($row) ? array_values($row) : [$i, $row];
            if (!empty($j)) {
                $this->add($this->space);
            }
            $this->add($this->radioBoxFactory($i, $v[0], $v[1], $request));
            $j++;
        }
    }

    protected function radioBoxFactory($idx, $value, $label, $request)
    {
        $RadioBox = new RadioBox($this->id . '_' . $idx, $label, $value);
        $RadioBox->getRadio()->attribute('name', $this->id);
        if (!is_null($request) && $request == $value) {
            $RadioBox->setChecked(true);
        }
        return $RadioBox;
    }
}
