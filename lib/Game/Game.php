<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/14/2019
 * Time: 11:33 PM
 */

namespace Game;
use Game\Board as Board;
use Game\Cards as Cards;
use Game\Card as Card;
use Game\Dict as Dict;

/**
 * Class Game This class contains the central logic of the game. It holds connections to all game items.
 * @package Game
 */
class Game
{
    // Trust PHP to not have something basic like enums
    CONST INVALID       =  0;
    CONST NEWGAME       = -1;
    CONST DISPLAYCARD   = -2;
    CONST SHOWCARDS     = -3;
    CONST ROUNDSTART    = -4;
    CONST PLAYERTURN    = -5;
    CONST MOVE          = -6;
    CONST LEAVE         = -7;
    CONST SUGGESTION    = -8;  // Who
    CONST SUGGESTION1   = -18; // With what
    CONST PROVING       = -9;
    CONST ACCUSATION    = -10; // Who
    CONST ACCUSATION1   = -17; // With what
    CONST ACCUSATION2   = -20;
    CONST JUDGMENT      = -11;
    CONST WIN           = -12;
    CONST JUSTICE       = -13;
    CONST LOSS          = -14;
    CONST PASS          = -15;
    CONST TAKEPASSAGE   = -16;

    const GAME_FILE = __DIR__ . "/map_data.csv";

    const SUSPECT_NAMES = array("Professor Day", "Professor Enbody", "Professor McCullen",
        "Professor Onsay", "Professor Owen", "Professor Plum");
    const SUSPECT_IMAGES = array("images/day.jpg", "images/enbody.jpg", "images/mccullen.jpg",
        "images/onsay.jpg", "images/owen.jpg", "images/plum.jpg");

    const WEAPON_NAMES = array("Final", "Midterm", "Programming", "Project", "Quiz", "Written");
    const WEAPON_IMAGES = array("images/final.jpg", "images/midterm.jpg", "images/programming.jpg",
        "images/project.jpg", "images/quiz.jpg", "images/written.jpg");

    const LOCATION_NAMES = array("Beaumont", "Breslin", "Engineering", "International",
        "Library", "Museum", "Stadium", "Union", "Wharton");
    const LOCATION_IMAGES = array("images/beaumont.jpg", "images/breslin.jpg", "images/engineering.jpg",
        "images/international.jpg", "images/library.jpg", "images/museum.jpg",
        "images/stadium.jpg", "images/union.jpg", "images/wharton.jpg");

    const DICE_MIN = 1;
    const DICE_MAX = 6;

    private $currentPlayer = -1;
    private $gameState = self::INVALID;
    private $gameBoard = null;
    private $players = null;
    private $cards = null;

    private $winningCombo = null;

    private $dice = array(); // Contains two numbers

    private $checkResult = null;
    private $accused = null;

    function __construct($seed) {
        if($seed === null) {
            $seed = time();
        }

        srand($seed);

        // initialize these here so they can be referenced in multiple functions
        $this->cards = new Cards();
        $this->players = array();
        $this->winningCombo = new Cards();

        $this->newGame();
    }

    /**
     * Sets the state of a game to a new state.
     * ##Note## you should never have anything other than switch logic and functions calls inside this function.
     * All actions must occur outside in another function. Would be best if it called other classes so we can keep
     * this class slim.
     *
     * @param $newState int The new state of the game
     */
    protected function setState($newState) {

        // EXIT STATE
        switch ($this->gameState) {
            case self::NEWGAME:
                $this->dealCards();
                $this->assignCardAliases();
                $this->gameBoard = new Board(self::GAME_FILE, $this->players);
                break;

            case self::DISPLAYCARD:
                break;
            default:
                break;
        }

        $this->gameState = $newState;

        // ENTER STATE
        switch ($this->gameState) {
            case self::NEWGAME:
                $this->currentPlayer = -1;
                $swl = $this->makeCards();
                $this->makeWinningCombo($swl[0], $swl[1], $swl[2]);
                break;

            case self::ROUNDSTART:
                $this->currentPlayer = -1;
                $this->setState(self::PLAYERTURN);
                break;

            case self::PLAYERTURN:
                if (++$this->currentPlayer >= count($this->players)) {
                    $this->setState(self::ROUNDSTART);
                }
                $this->throwDice();
                $this->gameBoard->showMoves($this->players[$this->currentPlayer]->getName(), ($this->getDiceThrow()[0] + $this->getDiceThrow()[1]));
                break;

            case self::PASS:
                $this->setState(self::PLAYERTURN);
                break;

            default:
                break;
        }

    }

    /**
     * Randomly draw a suspect, location, and weapon card. Who murdered my grade!? Likely Prof. Onsay.
     * @param array Suspects
     * @param array Weapons
     * @param array Locations
     * @return array ID's for Suspect, Weapon, Location winning combo
     */
    public function makeWinningCombo($suspects, $weapons, $locations) {
        $this->winningCombo = new Cards();

        $suspect = $this->cards->getRandomCard($suspects);
        $this->winningCombo->addCard($suspect);

        $weapon = $this->cards->getRandomCard($weapons);
        $this->winningCombo->addCard($weapon);

        $location = $this->cards->getRandomCard($locations);
        $this->winningCombo->addCard($location);

        return array($suspect, $weapon, $location);
    }

    /**
     * Generate the random cards for each type
     * and return array of sub-arrays: suspects, weapons and locations
     * from which we can generate a winning combo
     * @return array Array of suspect, weapon and location card arrays
     */
    public function makeCards() {
        $this->cards = new Cards();  // reset cards (mainly to make this function easier to test)

        $suspects = array(); // temp array of suspect cards in the game

        // Add 6 suspect cards
        for($i = 0; $i < 6; $i++) {
            $suspect = new Suspect(self::SUSPECT_IMAGES[$i], self::SUSPECT_NAMES[$i]);
            array_push($suspects, $suspect);
            $this->cards->addCard($suspect);
        }

        $weapons = array(); // temp array of weapon cards in the game

        // Add 6 weapon cards
        for($i = 0; $i < 6; $i++) {
            $weapon = new Weapon(self::WEAPON_IMAGES[$i], self::WEAPON_NAMES[$i]);
            array_push($weapons, $weapon);
            $this->cards->addCard($weapon);
        }

        $locations = array(); // temp array of location cards in the game

        // Add 9 location cards
        for($i = 0; $i < 9; $i++) {
            $location = new Location(self::LOCATION_IMAGES[$i], self::LOCATION_NAMES[$i]);
            array_push($locations, $location);
            $this->cards->addCard($location);
        }

        return array($suspects, $weapons, $locations);
    }

    /**
     * Deal the cards to each player
     */
    public function dealCards()
    {
        $this->cards->shuffle(); // shuffle before dealing

        $suspect_id = $this->winningCombo->getCards()[0]->getCardId();
        $weapon_id = $this->winningCombo->getCards()[1]->getCardId();
        $location_id = $this->winningCombo->getCards()[2]->getCardId();

        // temp array of win combo ids so we don't deal these cards to players
        $win_combo_ids = array($suspect_id, $weapon_id, $location_id);

        // array of non-winning combo cards to deal
        $cards_to_deal = new Cards();

        // loop to get all non-winning combo cards
        for ($i = 0; $i < count($this->cards->getCards()); $i++) {
            if (!in_array($this->cards->getCards()[$i]->getCardId(), $win_combo_ids)) {
                $cards_to_deal->addCard($this->cards->getCards()[$i]);
            }
        }

        // number of cards to be dealt to each player (intdiv returns floor int value)
        $num_cards = intdiv(count($cards_to_deal->getCards()), count($this->players));
        if ($num_cards > 6) {
            $num_cards = 6; // players can't be dealt more than 6 cards
        }

        $index = 0; // index to slice dealing array
        foreach ($this->players as $player) {
            $player->dealHeld(array_slice($cards_to_deal->getCards(), $index, $num_cards, true));
            $index += $num_cards;
        }

        // each player gets the winning combo cards as "other" cards
        foreach ($this->players as $player) {
            $player->dealOther($this->winningCombo->getCards());
        }

        // deal rest of "other" cards (different dealings based on number of players)
        switch (count($this->players)) {
            case 2:
                // Player 1 gets all cards they weren't dealt
                $this->players[0]->dealOther(array_slice($cards_to_deal->getCards(), 6,
                    12, true));

                // Player 2 gets all cards they weren't dealt
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    6, true));
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 12,
                    18, true));
                break;
            case 3:
                // Player 1 gets all cards they weren't dealt
                $this->players[0]->dealOther(array_slice($cards_to_deal->getCards(), 6,
                    12, true));

                // Player 2 gets all cards they weren't dealt
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    6, true));
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 12,
                    18, true));

                // Player 3 gets all cards they weren't dealt
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    12, true));
                break;
            case 4:
                // Player 1 gets all cards they weren't dealt
                $this->players[0]->dealOther(array_slice($cards_to_deal->getCards(), 4,
                    14, true));

                // Player 2 gets all cards they weren't dealt
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    4, true));
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 8,
                    10, true));

                // Player 3 gets all cards they weren't dealt
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    8, true));
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 12,
                    6, true));

                // Player 4 gets all cards they weren't dealt
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    12, true));
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 16,
                    2, true));
                break;
            case 5:
                // Player 1 gets all cards they weren't dealt
                $this->players[0]->dealOther(array_slice($cards_to_deal->getCards(), 3,
                    15, true));

//                $multiplier = 0;
//                for($i = 1; $i < count($this->players); $i++) {
//                    $dealt = 0;
//                    while($dealt < 15) {
//
//                    }
//                }

                // Player 2 gets all cards they weren't dealt
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    3, true));
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 6,
                    12, true));

                // Player 3 gets all cards they weren't dealt
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    6, true));
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 9,
                    9, true));

                // Player 4 gets all cards they weren't dealt
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    9, true));
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 12,
                    6, true));

                // Player 5 gets all cards they weren't dealt
                $this->players[4]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    12, true));
                $this->players[4]->dealOther(array_slice($cards_to_deal->getCards(), 15,
                    3, true));
                break;
            case 6:
                // Player 1 gets all cards they weren't dealt
                $this->players[0]->dealOther(array_slice($cards_to_deal->getCards(), 3,
                    15, true));

                // Player 2 gets all cards they weren't dealt
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    3, true));
                $this->players[1]->dealOther(array_slice($cards_to_deal->getCards(), 6,
                    12, true));

                // Player 3 gets all cards they weren't dealt
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    6, true));
                $this->players[2]->dealOther(array_slice($cards_to_deal->getCards(), 9,
                    9, true));

                // Player 4 gets all cards they weren't dealt
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    9, true));
                $this->players[3]->dealOther(array_slice($cards_to_deal->getCards(), 12,
                    6, true));

                // Player 5 gets all cards they weren't dealt
                $this->players[4]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    12, true));
                $this->players[4]->dealOther(array_slice($cards_to_deal->getCards(), 15,
                    3, true));

                // Player 6 gets all cards they weren't dealt
                $this->players[5]->dealOther(array_slice($cards_to_deal->getCards(), 0,
                    15, true));
                break;
        }

        return;
    }

    /**
     * Assign card aliases for each player in the game
     */
    public function assignCardAliases() {
        // generate maximum number of aliases to assign (18 per player)
        $dict = new Dict(count($this->players) * 18);

        // assign card aliases for each player's non-held cards
        foreach($this->players as $player) {
            $aliases = array();
            for($i=0; $i<18; $i++) {  // get 18 new aliases for this player
                array_push($aliases, $dict->getNewAlias());
            }
            $player->assignAliases($aliases);
        }
    }

    /**
     * Kronk, throw the dice!
     */
    private function throwDice() {
        $this->dice = array(rand(self::DICE_MIN, self::DICE_MAX), rand(self::DICE_MIN, self::DICE_MAX));
    }

    // GETTERS ---------------------------------------------------------------------------------------------------------

    /**
     * Gets the player currently selected by the game controller
     * @return Player Get the current player
     */
    public function getCurrentPlayer() {
        return $this->players[$this->currentPlayer];
    }

    /**
     * Gets the games current state
     * @return int The game state
     */
    public function getCurrentState() {
        return $this->gameState;
    }

    /**
     * Get the results of a dice throw
     * @return array The results of a previous dice throw
     */
    public function getDiceThrow() {
        return $this->dice;
    }

    /**
     * Get the cards in the game
     * @return Cards The cards in the game
     */
    public function getCards() {
        return $this->cards;
    }

    // FUNCTIONS THAT WILL CHANGE THE GAME STATE -----------------------------------------------------------------------

    /**
     * Clears the game state and sets it back to NewGame
     */
    public function newGame() {
        if ($this->gameState != Game::NEWGAME) {
            $this->setState(self::NEWGAME);
        }
    }

    /**
     * Add the new players to the game.
     * @param $playerNames array Array of the selected player names
     */
    public function addPlayers($playerNames) {
        if ($this->gameState != self::NEWGAME) {
            die("Tried to add players in the wrong state: ".$this->gameState); //Well this went wrong!
        }

        $this->players = array();

        foreach ($playerNames as $pname) {
            $add_index = count($this->players); // index for new Player
            $this->players[$add_index] = new Player($pname);; // index => Player
            //$this->players[] = new Player($pname);
        }

        //$this->dealCards(); // now that the players are set, deal them

        $this->setState(self::DISPLAYCARD);
    }

    /**
     * Ask the controller if we have cards to display.
     * @return bool Will return true if we have cards to display. False if we are out of players.
     */
    public function canDisplayCard() {
        if ($this->gameState == self::DISPLAYCARD) {
            if (++$this->currentPlayer >= count($this->players)) {
                $this->setState(self::ROUNDSTART);
                return false;
            }
        } else {
            die("Tried to call canDisplayCard in the wrong state: ".$this->gameState); //Well this went wrong!
        }

        return true;
    }

    // SUGGESTION

    /**
     * The player wants to make a suggestion
     */
    public function makeSuggestion() {
        $this->setState(self::SUGGESTION);
    }

    /**
     * The player suggests a suspect did it
     * @param $suspect string Suspect who done did it
     */
    public function suggestSuspect($suspect) {
        $this->setState(self::SUGGESTION1);
        $this->accused = $suspect;
        $this->gameBoard->move($suspect, $this->getCurrentPlayer()->getPos()[0], $this->getCurrentPlayer()->getPos()[1]);
    }

    /**
     * The player suggests a weapon that did it. When then check to see what the result of the suggestion is.
     * @param $weapon string Weapon that was used
     */
    public function suggestWeapon($weapon) {
        $result = 'I got nothing.';

        $pcards = $this->getCurrentPlayer()->getHeld()->getCards();
        $pcnames = array();
        foreach ($pcards as $card) {
            $pcnames[] = $card->getCardId();
        }
        $wcnames = array();
        foreach ($this->winningCombo->getCards() as $card) {
            $wcnames[] = $card->getCardId();
        }

        $ccards = array($this->accused, $weapon, $this->getCurrentPlayer()->getRoom());

        $checks = array(0, 1, 2); // Randomise the order we check the cards.
        shuffle($checks);

        while (count($checks) > 0) {
            $card = array_pop($checks);
            if (in_array($ccards[$card], $pcnames)) { //If the card is in the player deck skip it
                continue;
            }

            if (in_array($ccards[$card], $wcnames)) { // If their suggestion is right
                continue;
            }

            // We have a non player held card that is not a part of winning combo, get it's obfuscated name.
            $result = $this->getCurrentPlayer()->getAliases()[$ccards[$card]];
            break;
        }

        $this->checkResult = $result;
        $this->setState(self::PROVING);
    }

    /**
     * Return the result of the suggestion then move on to the next turn.
     * @return string Return the result of the suggestion
     */
    public function clearSuggestion() {
        $this->setState(self::PLAYERTURN);
        return $this->checkResult;
    }



    // ACCUSATION

    /**
     * How very bold of you. The player is accusing someone else!
     */
    public function makeAccusation() {
        // if (getCurrentPlayer()->canAccuse()) {
        $this->setState(self::ACCUSATION);
    }

    /**
     * The player accuses a suspect did it
     * @param $suspect string Suspect who done did it
     */
    public function accuseSuspect($suspect) {
        $this->setState(self::ACCUSATION1);
        $this->accused = $suspect;
        $this->gameBoard->move($suspect, $this->getCurrentPlayer()->getPos()[0], $this->getCurrentPlayer()->getPos()[1]);
    }

    /**
     * The player accuses a weapon that did it. When then check to see what the result of the accusation is.
     * @param $weapon string Weapon that was used
     */
    public function accuseWeapon($weapon) {

        $this->checkAccusation($this->accused, $weapon, $this->getCurrentPlayer()->getRoom());

    }

    /**
     * The player made their suggestion, now check it
     * @param $suspect Suspect who done did it
     * @param $location Location where it was done
     * @param $weapon Weapon how it was done
     */
    public function checkAccusation($suspect, $location, $weapon) {
        $this->setState(self::JUDGMENT);

        $wcnames = array();
        foreach ($this->winningCombo->getCards() as $card) {
            $wcnames[] = $card->getCardId();
        }

        if (in_array($suspect, $wcnames) and in_array($location, $wcnames) and in_array($weapon, $wcnames)){
            $this->setState(Game::WIN);
        }else{
            $this->getCurrentPlayer()->setCanAccuse(false);
        }
    }

    /**
     * The player has accepted their fate. We move ever forward.
     */
    public function clearAccusation() {
        foreach ($this->players as $player) {
            if (is_object($player) && $player instanceof Player) {
                if ($player->canAccuse()) {
                    $this->setState(self::PLAYERTURN);
                    return true;
                }
            }
        }

        $this->setState(self::LOSS); // Oh no.
        return false;
    }

    // END CARD CHECKS

    /**
     * The player passes their turn.
     */
    public function passTurn() {
        $this->setState(self::PASS);
    }

    /**
     * The player takes a hidden passage.
     */
    public function takePassage() {
        $this->setState(self::TAKEPASSAGE);
    }

    /**
     * Get the board for the game
     * @return board
     */
    public function getBoard(){
        // needed for gameView
        return $this->gameBoard;
    }

    public function getPlayers() //added this so I can test that the right characters are going into players array
    {
        return $this->players;
    }

    public function getWinningCombo() //added to get winning combo for winView
    {
        return $this->winningCombo;
    }

}