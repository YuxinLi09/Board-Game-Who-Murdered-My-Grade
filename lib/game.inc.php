<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/21/2019
 * Time: 10:27 PM
 */

require __DIR__ . "/../vendor/autoload.php";

session_start();
define("CLUE_SESSION", 'Clouseau');

if(!isset($_SESSION[CLUE_SESSION])) {
    $_SESSION[CLUE_SESSION] = new \Game\Game(null);
}

$game = $_SESSION[CLUE_SESSION];