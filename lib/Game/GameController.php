<?php
/**
 * Created by PhpStorm.
 * User: leithchatti
 * Date: 2019-02-15
 * Time: 17:54
 */

namespace Game;


class GameController
{

    private $page = 'gameboard.php';

    /**
     * GameController constructor.
     * @param $game Game Game object
     * @param $vars array Post variables
     */
    public function __construct($game, $vars) {
        if(isset($vars['reset']))
        {
            $game->newGame();

            $this->page = 'index.php';
        } elseif (isset($vars['room-action'])) {
            $game->getBoard()->clearMoves();
            switch($vars['room-action']) {
                case 'pass':
                    $game->passTurn();
                    $view=new GameView($game);
                    $this->result=json_encode(['board' => $view->displayBoard()]);
                    break;

                case 'suggest':
                    $game->makeSuggestion();
                    $view=new GameView($game);
                    $this->result=json_encode(['board' => $view->displayBoard()]);
                    break;

                case 'accuse':
                    $game->makeAccusation();
                    $view=new GameView($game);
                    $this->result=json_encode(['board' => $view->displayBoard()]);
                    break;

                default:
                    die('whoops');
            }
        } elseif (isset($vars['suggestion-who'])) {
            $who = strip_tags($vars['who']);
            if (in_array($who, Game::SUSPECT_NAMES)) {
                $game->suggestSuspect($who);
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }
        } elseif (isset($vars['suggestion-what'])) {
            $what = strip_tags($vars['what']);
            if (in_array($what, Game::WEAPON_NAMES)) {
                $game->suggestWeapon($what);
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }
        } elseif (isset($vars['accusation-who'])){
            $who = strip_tags($vars['who']);
            if (in_array($who, Game::SUSPECT_NAMES)) {
                $game->accuseSuspect($who);
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }
        } elseif (isset($vars['accusation-what'])){
            $what = strip_tags($vars['what']);
            if (in_array($what, Game::WEAPON_NAMES)) {
                $game->accuseWeapon($what);
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }

            if ($game->getCurrentState() == Game::WIN) {
                $this->page = 'win.php';
                $view = new WinView($game);
                $this->result=json_encode(["board" => $view->present()]);
            }
        } elseif (isset($vars['judgment'])) {
            $result = $game->clearAccusation();
            if (!$result) {
                $this->page = 'lose.php';
                $view=new LoseView($game);
                $this->result=json_encode(["board" => $view->present()]);
            }else{
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }
        } elseif (isset($vars['dest'])){
            $board = $game->getBoard();
            $split = explode(',', strip_tags($vars['dest']));
            $board->move($game->getCurrentPlayer()->getName(), +$split[0], +$split[1]);
            $board->clearMoves();
            if ($game->getCurrentPlayer()->isInRoom() == false) {
                $game->passTurn();
                $view=new GameView($game);
                $this->result=json_encode(['board' => $view->displayBoard()]);
            }
            $view=new GameView($game);
            $this->result=json_encode(['board' => $view->displayBoard()]);
        }elseif (isset($vars["jump"])){
            $view=new GameView($game);
            $this->result=json_encode(['board' => $view->displayBoard()]);
        }
    }

    public function getPage() {
        return $this->page;
    }

    public function getResult()
    {
        return $this->result;
    }

    protected $result=null;

}

