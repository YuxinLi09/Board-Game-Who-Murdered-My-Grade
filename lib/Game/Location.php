<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Carroll
 * Date: 2/15/2019
 * Time: 11:11 AM
 */

namespace Game;

/**
 * Class Location extends Class Card: A location card
 * @package Game
 */
class Location extends Card{

    /**
     * Location constructor.
     * @param string filename for the card image
     * @param string This card's id
     */
    public function __construct($image, $card_id)
    {
        parent::__construct($image, $card_id);
    }
}