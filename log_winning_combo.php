<?php

require 'lib/game.inc.php';

echo $game->getWinningCombo()->getCards()[0]->getCardId() . "\n\n";
echo "WITH...\n\n";
echo $game->getWinningCombo()->getCards()[1]->getCardId() . "\n\n";
echo "AT...\n\n";
echo $game->getWinningCombo()->getCards()[2]->getCardId();
