<?php
/**
 * Created by PhpStorm.
 * User: leithchatti
 * Date: 2019-02-15
 * Time: 17:41
 */
namespace Game;

/**
 * Class Player
 * @package Game
 */
class Player
{
    private $name;  // The player name
    private $held_cards; // The cards this player holds
    private $other_cards; // The other cards in the game
    private $inRoom = false; // is player in a room
    private $accuse = true; // is the player still allowed to accuse
    private $room;
    private $aliases;  // array of card id's mapped to aliases this player sees on their card sheet
    private $pos = array();

    /**
     * Player constructor.
     * @param $name player name
     */
    public function __construct($name/*, $start_point*/) {
        $this->name = $name;
        $this->held_cards = new Cards();
        $this->other_cards = new Cards();
        /*$this->startpoint = $start_point;*/
    }

    /**
     * Get this player's name
     * @return string Player
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get player in room status
     * @return bool status
     */
    public function isInRoom(){
        return $this->inRoom;
    }

    /**
     * Get in room status
     * @param bool true if player in room, else false
     */
    public function setInRoom($b){
        $this->inRoom = $b;
    }

    /**
     * Set room this player is in
     * @param string Room to set
     */
    public function setRoom($room){
        $this->room = $room;
    }

    /**
     * Get room player is in
     * @return string Room
     */
    public function getRoom(){
        return $this->room;
    }

    public function setPos($r, $c){
        $this->pos = array($r, $c);
    }

    public function getPos(){
        return $this->pos;
    }


    /**
     * Get this player's cards
     * @return Cards This player's cards
     */
    public function getHeld() {
        return $this->held_cards;
    }

    /**
     * Get the other cards in the game (the player has aliases for each of these)
     * @return Cards Other cards in the game
     */
    public function getOther() {
        return $this->other_cards;
    }

    /**
     * Deal cards this player will hold
     * @param array Array of cards to deal to this player
     */
    public function dealHeld($cards) {
        foreach($cards as $card) {
            $this->held_cards->addCard($card);
        }
        return;
    }

    /**
     * Assign aliases for each card in the game this player is not holding
     * @param array Array of 18 aliases to assign to this player's card sheet
     * Some aliases may not be used if there are fewer than 18 other cards in the game
     */
    public function assignAliases($aliases) {
        for($i = 0; $i < count($this->other_cards->getCards()) ; $i++) {
            $this->aliases[$this->other_cards->getCards()[$i]->getCardId()] = $aliases[$i]; // index => alias
        }
        return;
    }

    /**
     * Get player aliases
     * @return array Array of player aliases
     */
    public function getAliases() {
        return $this->aliases;
    }

    /**
     * Deal the cards to this player not held by them
     * @param $cards array Other cards in the game held by other players and the computer
     */
    public function dealOther($cards) {
        foreach($cards as $card) {
            $this->other_cards->addCard($card);
        }
        return;
    }

    /**
     * Get whether or not player has remaining accusation
     * @return bool True if player still can accuse, else false
     */
    public function canAccuse() {
        return $this->accuse;
    }

    /**
     * Set player's accusation status
     * @param bool True means player can accuse, false means they've spent their one accusation
     */
    public function setCanAccuse($b){
        $this->accuse = $b;
    }
}