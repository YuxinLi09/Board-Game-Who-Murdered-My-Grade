<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/24/2019
 * Time: 6:48 PM
 */

require 'lib/game.inc.php';

$controller = new \Game\CardController($game, $_POST);

header("location: " . $controller->getPage());
exit;