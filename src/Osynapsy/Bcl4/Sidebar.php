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

use Osynapsy\Html\Tag;
use Osynapsy\Html\Component\AbstractComponent;

/**
 * Costruttore della Sidebar HTML
 */
class Sidebar extends AbstractComponent
{
    private $items = array();
    private $groups = array();

    public function __construct($id)
    {
        // Usiamo il tag 'nav' come base per la sidebar
        parent::__construct('nav', $id);
        $this->requireCss('bcl4/sidebar/style.css');

        // Classi standard Bootstrap 4 per una sidebar verticale
        $this->addClass('nav flex-column sidebar');
    }

    public function preBuild()
    {
        // Prima aggiungiamo i titoli dei gruppi (se presenti)
        foreach ($this->groups as $group) {
            $this->add($group);
        }

        // Poi aggiungiamo i link
        foreach ($this->items as $item) {
            $this->add($item);
        }
    }

    /**
     * Aggiunge un titolo di sezione alla sidebar
     * @param string $title
     * @return Tag
     */
    public function addGroup($title)
    {
        $h5 = new Tag('h6', null, 'sidebar-group-title px-3 mt-3 mb-2');
        $h5->add($title);
        $this->groups[] = $h5;
        return $h5;
    }

    /**
     * Aggiunge un elemento di navigazione alla sidebar
     *
     * @param string $label Il testo del link
     * @param string $url L'URL di destinazione
     * @param string|null $icon Nome dell'icona (es. 'fas fa-home')
     * @param bool $active Se il link deve essere attivo
     * @return Tag
     */
    public function addItem($label, $url, $icon = null, $active = false)
    {
        $navItem = new Tag('li', null, 'nav-item');
        $navLink = new Tag('a', null, 'nav-link');

        $navLink->attributes([
            'href' => $url,
            'class' => $active ? 'active' : ''
        ]);

        // Se c'è un'icona, la aggiungiamo prima del testo
        if ($icon) {
            $iconTag = new Tag('i', null, $icon);
            $navLink->add($iconTag);
            $navLink->add(new Tag('span', null, ' mr-2')); // Spaziatore
        }

        $navLink->add(new Tag('span', null, $label));
        $navItem->add($navLink);

        $this->items[] = $navItem;
        return $navItem;
    }
}
