<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/15/2019
 * Time: 9:26 AM
 */

namespace Game;

/**
 * Class Card This class is the base class for all cards in the game
 * @package Game
 */
class Card
{
    private $image;      // filename for the card image
    private $card_id;    // string identifying the actual card (e.g. "Stadium")

    public function __construct($image, $card_id)
    {
        $this->image = $image;
        $this->card_id = $card_id;
    }

    /**
     * Get the card image
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get the card id
     * @return string $card_id
     */
    public function getCardId()
    {
        return $this->card_id;
    }
}