<?php
/**
 * Created by PhpStorm.
 * User: samvitu
 * Date: 2019-02-22
 * Time: 17:32
 */

namespace Game;

use Game\Card as Card;

/**
 * Class Cards container class for cards in the game
 * @package Game
 */
class Cards
{
    private $cards = array();       // array to hold the actual cards (each key is an integer mapped to a Card)

    /**
     * Cards constructor.
     */
    public function __construct() {
    }

    /**
     * Add card to array of cards
     * If card already in $cards, does nothing
     * @param \Game\Card $card card to add
     */
    public function addCard(Card $card) {
        for($i = 0; $i < count($this->cards); $i++) {
            if ($this->cards[$i]->getCardId() === $card->getCardId()) { // check to make sure card not already added
                return;
            }
        }

        $add_index = count($this->cards); // index for new card
        $this->cards[$add_index] = $card; // add card to array
        return;
    }

    /**
     * Shuffles the cards
     */
    public function shuffle() {
        $keys = array_keys($this->cards);
        shuffle($keys);
        $random = array(); // temp array to help keep key=>value pairs the same

        foreach($keys as $key) {
            $random[$key] = $this->cards[$key]; // set key in new ordering its value in old ordering
        }

        //shuffle($this->cards);
        $this->cards = $random; // set array to randomized
        return;
    }

    /**
     * Given a card Id, remove card from array of cards
     * If id not in $cards, does nothing
     * @param string $remove_id Id of card to remove
     */
    public function removeCard($remove_id) {

        for($i = 0; $i < count($this->cards); $i++) {
            if($this->cards[$i]->getCardId() === $remove_id) {
                unset($this->cards[$i]);
                return;
            }
        }
        return;
    }

    /**
     * Get cards in the game
     * @return array The array of cards
     */
    public function getCards() {
        return $this->cards;
    }

    /**
     * Given an array of cards, get a random card
     * @param array Array of cards
     * @return Card The card we found
     */
    public function getRandomCard($card_ary) {
        return $card_ary[array_rand($card_ary)];
    }
}