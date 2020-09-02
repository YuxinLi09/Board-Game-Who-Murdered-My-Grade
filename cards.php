<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/19/2019
 * Time: 11:26 PM
 */

require 'lib/game.inc.php';
$view = new \Game\CardsView($game);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Who Murdered my Grade!</title>
<!--    <link href="win-lose-cards.css" type="text/css" rel="stylesheet" />-->
    <script src="dist/main.js"></script>
</head>
<body>
<div class="cardspage">
  <?php echo $view->display() ?>
</div>
</body>
</html>