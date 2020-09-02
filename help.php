<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/20/2019
 * Time: 7:59 AM
 */
require 'format.inc.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Help</title>
    <script src="dist/main.js"></script>
</head>
<body>
<?php echo present_header("Gameplay Instructions and Rules"); ?>
<div class="welcome">
    <div class="gameplay">
        <h2>Gameplay</h2>
        <h3>Character Selection</h3>
        <p>At least 2 players are required to play the game. Characters are selected for each player by checking the same amount of characters as players and then pressing submit.</p>
        <h3>Understanding the card print page</h3>
        <p>After character selection is completed, each player will be presented with a print card page. Each player will press the print button to be able to print a list of cards. The top cards are the cards dealt to that player. The other cards are secret cards, which include cards held by other players or the computer. Each of these cards is assigned a code name that is player specific. Each player should keep their printed card sheet secret. The computer will then use the specified code names for each card to communicate cards to a player in response to suggestions.</p>
        <h3>First turn</h3>
        <p>Once each player prints their card sheet, the game board is presented. The first player's turn begins with two dice being rolled. The result of the roll is presented in the center of the game board. The player's character will be displayed on the game board at the start location for that character. The player can then choose to move to any square that can be traveled to with the exact roll or move into a room. Valid squares will be highlighted on the game board. If the player is able to enter a room, they may do so. Once in a room the player will be prompted to pass, suggest or accuse. If the play cannot enter a room, their turn will end once they choose a square to move to.</p>
        <h3>Making a Suggestion</h3>
        <p>Once a player enters a room they may make a suggestion. The room that they have entered will be the location used in the suggestion. If the player chooses to make a suggestion, they will be presented with a list of possible suspects. The player will then check the character they suspect and press go. The suspect that is selected by the player will be placed in the room with the player. The player will then be presented with a list of possible weapons. The player will then choose which weapon they suspect and press go. If the suggestion is false, the player will be presented with a code word that will be representative of a card on their card sheet. After making a suggestion the player will then press go and it is the next player's turn.</p>
        <h3>Making an Accusation</h3>
        <p>Making an accusation is the same as making a suggestion other than the outcome. If a player is incorrect while making an accusation, they will no longer be able to make accusations for the remainder of the the game, therefore being unable to win. If the player is correct when making an accusation, the player wins the game.</p>
        <h3>Starting a Turn in a Room</h3>
        <p>If a player begins their turn while in a room they may choose to exit the room, stay in the room or take a secret passage. If the player chooses to leave the room, they may move using the roll performed at the beginning of the turn. To move the player can click on a highlighted square or room they are able to move to. If the player is able to move to another room using their roll, they may move to that room and make a suggestion or accusation. If the player chooses to stay in the room, it is the same as entering that room, they make a suggestion or accusation in that room. To stay in the room, the player just clicks on the room. If the player chooses to use a secret passage, they will be able to click on the location the passage leads to and they will be placed in that location.</p>
    </div>
    <div class="rules">
        <h2>Rules</h2>
        <h3>Movement</h3>
        <p>When moving to squares with the exact number from the roll, the player can only move horizontally or vertically. The player cannot move through the same square more than once in a turn and the player cannot move or land on a square occupied by another player. Using these rules the game will generate squares that are valid and highlight them for the player.</p>
        <h3>Entering and Exiting Rooms</h3>
        <p>Entering or exiting a room counts as a square. When entering a room the player will need enough to make it to the doorway and to make it inside the room. Multiple players can be in the same room. When exiting a room, the act of exiting subtracts one from the roll.</p>
        <h3>Using Secret Passages</h3>
        <p>Using a secret passage does not count against the player's roll. The player may use a secret passage and make a suggestion or accusation in the new room or exit that room and move using their roll.</p>
    </div>
    <div class="team">
        <h2>Team Clouseau</h2>
        <h3>Team Members</h3>
        <p>Jesse Richard M Stricklin, Benjamin Donovan Carroll, Yuxin Li, Justin Michael Perry and Sam Vitu</p>
    </div>
    <div class="return">
        <p>Click <a href="index.php">here</a> to return to character selection</p>
    </div>
</div>
</body>
</html>
