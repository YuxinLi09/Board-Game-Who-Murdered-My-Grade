<?php
/**
 * Created by PhpStorm.
 * User: Justin Perry
 * Date: 2/19/2019
 * Time: 7:39 PM
 */
require 'format.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You LOSE! - Who Murdered my Grade!</title>
<!--    <link href="src/_win-lose-cards.scss" type="text/css" rel="stylesheet" />-->
    <script src="dist/main.js"></script>
</head>
<body>
<div class="lose">
<?php echo present_header("Who Murdered My Grade"); ?>
  <div class="main center-box sm-30">
      <p>It's good your degree was not in law, right?</p>
      <p>Justice is disappointed.</p>
      <form action="win-lose-post.php" method="post">
          <input type="submit" name="reset" value="New Game"/>
      </form>
  </div>
</div>
</body>
</html>
