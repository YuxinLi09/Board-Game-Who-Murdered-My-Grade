<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/20/2019
 * Time: 7:58 AM
 */
require 'format.inc.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Character Select</title>
    <script src="dist/main.js"></script>
</head>
<body>
<?php echo present_header("Who Murdered My Grade"); ?>
<div class="index">
<form class="index" method="post" action="character-post.php">
    <div class="checkboxes">
        <input type="checkbox" name="checklist[]" value="Professor Owen">Prof. Owen<br>
        <input type="checkbox" name="checklist[]" value="Professor McCullen">Prof. McCullen<br>
        <input type="checkbox" name="checklist[]" value="Professor Onsay">Prof. Onsay<br>
        <input type="checkbox" name="checklist[]" value="Professor Enbody">Prof. Enbody<br>
        <input type="checkbox" name="checklist[]" value="Professor Plum">Prof. Plum<br>
        <input type="checkbox" name="checklist[]" value="Professor Day">Prof. Day<br>
    </div>

    <p>Select at least 2 players to play the game.</p>
    <div class="button">
        <input type="submit">
    </div>
</form>
<div class="help">
    <p>For game instructions and rules click <a href="help.php">here</a></p>
</div>
</div>
</body>
</html>
