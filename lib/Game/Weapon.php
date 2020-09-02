<?php
/**
 * Created by PhpStorm.
 * User: samvitu
 * Date: 2019-02-15
 * Time: 15:14
 */

namespace Game;

/**
 * Class Weapon representing a weapon card in the game
 * @package Game
 */
class Weapon extends Card {

    /**
     * Weapon card constructor.
     * @param string filename for the card image
     * @param string This card's id
     */
    public function __construct($image, $card_id) {
        parent::__construct($image, $card_id);
    }
}