<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/15/2019
 * Time: 9:27 AM
 */

namespace Game;

/**
 * Class Suspect This class inherits from the base card class and is the class for suspect cards in the game
 * @package Game
 */
class Suspect extends Card
{
    /**
     * Suspect constructor.
     * @param string filename for the card image
     * @param string This card's id
     */
    public function __construct($image, $card_id)
    {
        parent::__construct($image, $card_id);
    }
}