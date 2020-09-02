<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/22/2019
 * Time: 10:32 AM
 */

require 'lib/game.inc.php';

$controller = new \Game\GameController($game, $_POST);

//if($game->getCurrentState()===\Game\Game::WIN)
//{
//    header("location: " . $controller->getPage());
//    exit;
//}

//else if($game->getCurrentState()===\Game\Game::LOSS) {
//    header("location: " . $controller->getPage());
//   exit;
if ($game->getCurrentState() === \Game\Game::NEWGAME){
    header("location: " . $controller->getPage());
    exit;
}else{
    echo $controller->getResult();
}
