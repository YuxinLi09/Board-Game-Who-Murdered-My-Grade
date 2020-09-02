<?php
/**
 * Created by PhpStorm.
 * User: leithchatti
 * Date: 2019-02-15
 * Time: 17:46
 */

namespace Game;


class CardsView
{
    private $game;

    /**
     * CardsView constructor.
     * @param $game Game
     */
    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Creates the card display page for the current player.
     * @return string The HTML to display
     */
    public function display() {
        $player = $this->game->getCurrentPlayer();
        $cardNames = $player->getAliases();
        $pcards = $player->getHeld()->getCards();
        $pname = $player->getName();
        $html = <<<HTML
<header>
    <h1>Who Murdered My Grade!</h1>
</header>
<div class="no-print">
<div class="main center-box center-text sm-30">
<h3>Cards for $pname</h3>
<form action="cards-post.php" method="post">
<input type="button" onclick="window.print();return false;" value="Print">
<input type="submit" value="Next">
</form>
</div>
</div>

<div class="print-only">
HTML;

        $html .= "<p class='char'>Cards for $pname</p>";
        $html .= "<p class='fancy-card'>Held cards</p>";

        foreach ($pcards as $card) {
            $cimg = $card->getImage();
            $cname = $card->getCardId();
            $html .= <<<HTML
<div class="card">
<img class="card-figure" src="$cimg" width="324" height="505" alt="$cname"/>
</div>
HTML;
        }

        $html .= "<p class='fancy-card'>Others</p>";

        foreach ($this->game->getCards()->getCards() as $card) {
            if (in_array($card, $pcards)) {
                continue;
            }

            $cimg = $card->getImage();
            $cname = $cardNames[$card->getCardId()];
            $html .= <<<HTML
<div class="card">
<img class="card-figure" src="$cimg" width="324" height="505" alt="$cname"/>
<p class="card-cap">$cname</p>
</div>
HTML;
        }

        $html .= "<div class='spacer'></div></div></div>";

        return $html;
    }

}