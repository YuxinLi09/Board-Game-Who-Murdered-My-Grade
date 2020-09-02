<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/22/2019
 * Time: 10:27 AM
 */

require 'lib/game.inc.php';

if(isset($_POST['reset']))
{
    $game->newGame();

    header("location: index.php");
}
