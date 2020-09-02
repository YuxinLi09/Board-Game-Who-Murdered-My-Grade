<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/21/2019
 * Time: 4:52 PM
 */

namespace Game;


class CharacterSelectController
{
    private $game;
    private $page='cards.php';

    public function getPage()
    {
        return $this->page;
    }

    public function __construct(Game $game)
    {
        $this->game=$game;
    }

    public function setPlayers($characters)
    {
        $this->game->addPlayers($characters);
    }

    public function getGame()
    {
        return $this->game;
    }
}