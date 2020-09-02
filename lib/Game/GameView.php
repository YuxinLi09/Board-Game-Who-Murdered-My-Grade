<?php
/**
 * Class GameView This class is responsible for creating the HTML in response to events in the game.
 * @package Game
 */


namespace Game;
use Game\Game as Game;

class GameView
{
    private $game;

    /**
     * GameView constructor.
     * @param $game Game The game object
     */
    public function __construct($game = null) {
        $this->game = $game;
    }

    public function displayBoard() {
        $html = <<<HTML
<form method="post" id="board" action="game-post.php">
   <div class="game">
      
HTML;

        $html .= $this->game->getBoard()->presentBoard();


        $center = $this->getCentralView();
        $html .= <<<HTML
        <div class="play">
            $center
        </div>
    </div>
</form>
HTML;

        $html .= <<<HTML
<form action="game-post.php" method="post">
    <input type="submit" name='reset' value="New Game"/>
</form>

HTML;



        return $html;
    }

    /**
     * Create the HTML to display the dice
     * @return String html
     */
    private function drawDice($dice) {
        $html = "";
        foreach($dice as $element) {
            /*if ($element == "7") {
                $html = <<<HTML
<P>YES</P>
HTML;

            }*/
            if ($element == "1" or $element == "2" or $element == "3" or $element == "4" or $element == "5" or $element == "6") {
                $html .= <<<HTML
<div class="diceCenter"><img src="images/dice$element" alt="Dice Display"/></div>
HTML;
            }
        }
        return $html;
    }

    /**
     * Draw the player icons on the board
     * @return String html
     */
    public function drawPlayers() {
        return "";
    }

    /**
     * The menu the player gets while in a room
     * @return String html
     */
    private function roomMenu() {
        $html = '<p>What do you wish to do?</p>';

        $html .= '<input type="radio" name="room-action" value="pass">Pass<br>';
        $html .= '<input type="radio" name="room-action" value="suggest">Suggest<br>';
        if ($this->game->getCurrentPlayer()->canAccuse()) {
            $html .= '<input type="radio" name="room-action" value="accuse">Accuse<br>';
        }
        $html .= '<input type="submit" name="room-next" value="Go">';

        return $html;
    }

    /**
     * Player wants the Suggestion menu
     * @return String html
     */
    private function makeSuggestion() {
        $html = '';

        if ($this->game->getCurrentState() == Game::SUGGESTION) {
            $html .= "<p>Who done it?</p>";

            foreach (Game::SUSPECT_NAMES as $player) {
                $html .= "<input type='radio' name='who' value='$player'>$player<br>";
            }

            $html .= '<input type="submit" name="suggestion-who" value="Next">';
        } else { // SUGGESTION1
            $html .= "<p>With what?</p>";

            foreach (Game::WEAPON_NAMES as $weapon) {
                $html .= "<input type='radio' name='what' value='$weapon'>$weapon<br>";
            }

            $html .= '<input type="submit" name="suggestion-what" value="Next">';
        }

        return $html;
    }

    /**
     * Show the player the result of the suggestion.
     * @return String html The results
     */
    private function resultsSuggestion() {
        $html = '<div class="centered-center"><p>Word on the street is:</p>';
        $result = $this->game->clearSuggestion();
        $html .= "<p class='fancy-card'>$result</p>";
        $html .= '</div>';

        $html .= '<input type="submit" value="Go">';
        return $html;
    }

    /**
     * Player wants to make a Accusation
     * @return String html
     */
    private function makeAccusation() {
        $html = '';

        if ($this->game->getCurrentState() == Game::ACCUSATION) {
            $html .= "<br><p>Who done it?</p><br>";

            foreach (Game::SUSPECT_NAMES as $player) {
                $html .= "<input type='radio' name='who' value='$player'>$player<br>";
                //$html .= '<input type="radio" name="room-action" value="accuse">temphtml<br>';
                //$html .= "<input type='radio' name=\'checklist[]\' value='$player'><br>";
                /*$html .= "
<div class=\"checkboxes\">
<input type=\"checkbox\" name=\"checklist[]\" value=\"Professor Owen\">$player<br>
</div>";*/
            }

            $html .= "<input type='submit' name='accusation-who' value='Next'>";

            /*$html .= '<input type="submit" name="suggestion-who" value="Next">';
        } else { // SUGGESTION1
            $html .= "<p>With what?</p>";

            foreach (Game::WEAPON_NAMES as $weapon) {
                $html .= "<input type='radio' name='what' value='$weapon'>";
            }

            $html .= '<input type="submit" name="suggestion-what" value="Next">';*/
        }


        else { // Accusation1
            $html .= "<p>With what?</p>";

            foreach (Game::WEAPON_NAMES as $weapon) {
                $html .= "<input type='radio' name='what' value='$weapon'>$weapon<br>";
            }

            $html .= '<input type="submit" name="accusation-what" value="Next">';
        }

        return $html;
    }



    /**
     * Show the player the result of the accusation.
     * @return win.php
     */
    private function resultsAccusationRight() {
        //header('Location: ' . '../lib/Game/win.php');
    }


    /**
     * Show the player the result of the suggestion.
     * @return String html The results
     */
    private function resultsAccusationWrong() {
        $html = '<div class="centered-center"><p>You are Wrong.<br>You are no longer allowed to accuse anybody</p>';
        $html .= '</div>';
        $html .= '<input type="submit" name="judgment" value="Go">';
        return $html;
    }

    /**
     * This creates the HTML for the center of the game board.
     * @return String html
     */
    public function getCentralView() {
        $pname = $this->game->getCurrentPlayer()->getName();
        $results = "<h2>Player</h2><p>$pname</p>";

        switch($this->game->getCurrentState()) {
            case Game::SUGGESTION1: // Display second part of menu
            case Game::SUGGESTION: // Display suggestion menu
                $results .= $this->makeSuggestion();
                break;

            case Game::PROVING: // Show results
                $results .= $this->resultsSuggestion();
                break;
            case GAME::WIN:
                $results = $this->resultsAccusationRight();
                break;
            case GAME::LOSS:
                //header('Location: ' . 'lose.php');
                break;

            case Game::ACCUSATION1: // Display second part of menu
            case Game::ACCUSATION: // Display accusation menu
                $results .= $this->makeAccusation();
                break;

            case Game::JUDGMENT: // The player was wrong show results
                $results .= $this->resultsAccusationWrong();
                break;

            case Game::PLAYERTURN:
                if ($this->game->getCurrentPlayer()->isInRoom()) {
                    $results .= $this->roomMenu();
                } else {
                    $dice = $this->game->getDiceThrow();
                    /*$distance = $dice[0] + $dice[1];
                    $results .= <<<HTML
<br>$distance
HTML;*/

                    //$this->game->getBoard()->showMoves($this->game->getCurrentPlayer()->getName(), $distance);
                    $results .= $this->drawDice($dice);
                }
                break;

            default:
                break;
        }

        return $results;
    }

    /**
     * This gets the html required to draw the player icons
     * @return String html
     */
    public function getPlayerIcons() {
        return "";
    }

}
