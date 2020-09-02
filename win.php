<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/19/2019
 * Time: 7:39 PM
 */
require 'lib/Game/WinView.php';
require 'format.inc.php';
require 'lib/game.inc.php';
$view=new \Game\WinView($game);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You WIN! - Who Murdered my Grade!</title>
<!--    <link href="src/_win-lose-cards.scss" type="text/css" rel="stylesheet" />-->
    <script src="dist/main.js"></script>
</head>
<body>
<div class="win">
<?php echo present_header("Who Murdered My Grade"); echo $view->present(); ?>
</div>
</body>
</html>
