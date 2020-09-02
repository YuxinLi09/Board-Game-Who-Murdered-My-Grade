<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Carroll
 * Date: 2/15/2019
 * Time: 11:14 AM
 */

namespace Game;

class LoseView extends GameView{

    /**
     * LoseView constructor.
     * @param Game $game the game that has been lost
     */
    public function __construct(Game $game)
    {
        parent::__construct($game);
    }

    /**
     * Generates html for a game over screen with lose status
     * @return string html for lose page
     */
    public function present(){
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You LOSE! - Who Murdered my Grade!</title>
    <script src="dist/main.js"></script>
</head>
<body>
<div class="lose">
  <div class="main center-box sm-30">
      <p>YOU LOSE!</p>
      <p>It's good your degree was not in law, right?</p>
      <p>Justice is disappointed.</p>
      <form action="win-lose-post.php" method="post">
          <input type="submit" name="reset" value="New Game"/>
      </form>
  </div>
</div>
</body>
</html>

HTML;

        return $html;
    }

}
