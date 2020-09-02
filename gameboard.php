<?php
/**
 * Created by PhpStorm.
 * User: Leith Chatti
 * Date: 2/19/2019
 * Time: 7:39 PM
 */
require 'lib/Game/GameView.php';
require 'format.inc.php';
require 'lib/game.inc.php';

if ($game->getCurrentState() == \Game\Game::WIN) {
    header('location: win.php');
    exit;
} elseif ($game->getCurrentState() == \Game\Game::LOSS) {
    header('location: lose.php');
    exit;
}

$board = 'images/board.jpg';
$view = new \Game\GameView($game);
?>
<!DOCTYPE html>
<html lang="en">
<!--<link href="game.css" type="text/css" rel="stylesheet" />-->
<head>
    <meta charset="UTF-8">
    <title>Game Board</title>
    <script src="dist/main.js"></script>
</head>
<body>
<?php echo present_header('Who Murdered My Grade'); echo $view->displayBoard(); ?>
</body>
</html>
