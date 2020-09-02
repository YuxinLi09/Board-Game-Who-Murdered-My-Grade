<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/24/2019
 * Time: 6:43 PM
 */

namespace Game;


class CardController
{
    private $page = 'cards.php';

    /**
     * CardController constructor.
     * @param Game $game
     * @param array $vars
     */
    public function __construct($game, $vars) {
        if (!$game->canDisplayCard()) {
            $this->page = 'gameboard.php';
        }
    }

    public function getPage() {
        return $this->page;
    }
}