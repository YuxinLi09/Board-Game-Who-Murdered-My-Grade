<?php
/**
 * Created by PhpStorm.
 * User: samvitu
 * Date: 2019-02-15
 * Time: 15:15
 */

namespace Game;

/**
 * Class WinView representing the view upon winning the game
 * @package Game
 */
class WinView {

    /**
     * WinView constructor.
     * @param Game $game the game that has been won
     */
    private $game;
    public function __construct($game)
    {
        $this->game=$game;
    }

    /**
     * Present the win page
     * @return string html for win page
     */
    public function present()
    {
        $html = '<div class="win">';
        $pname=$this->game->getCurrentPlayer()->getName();
        $wincombo=$this->game->getWinningCombo()->getCards();
        $suspect=$wincombo[0]->getCardId();
        $weapon=$wincombo[1]->getCardId();
        $location=$wincombo[2]->getCardId();

        if($this->game->getCurrentState()==Game::WIN)
        {
            $html .= <<<HTML
  <div class="main center-box sm-30">
      <p>$pname knew, it truly was $suspect in the $location using the $weapon.</p>
      <p>Justice is done.</p>
      <form action="win-lose-post.php" method="post">
          <input type="submit" name="reset" value="New Game"/>
      </form>
  </div>
HTML;
            $html .= '</div>';

            return $html;
        }

        else
        {
            $html=<<<HTML
    <div class="main center-box sm-30">
      <p>Stop trying to cheat</p>
      <form action="win-lose-post.php" method="post">
          <input type="submit" name="reset" value="New Game"/>
      </form>
  </div>
HTML;
            $html .= '</div>';
            return $html;
        }



    }

}
