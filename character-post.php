<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/20/2019
 * Time: 8:01 AM
 */

require 'lib/game.inc.php';

$controller = new \Game\CharacterSelectController($game);
$acceptable=array("Professor Owen", "Professor McCullen", "Professor Onsay", "Professor Enbody", "Professor Plum", "Professor Day");
$accepted=True;

if(count(($_POST['checklist']))>=2)
{
    foreach($_POST['checklist'] as $checked)
    {
        if(!in_array($checked,$acceptable))
        {
            $accepted=False;
        }
    }
    if($accepted==True)
    {
        $controller->setPlayers($_POST['checklist']);
        if($controller->getGame()->canDisplayCard()==True)
        {
            $page = $controller->getPage();
            header('Location: ' . $controller->getPage());
        }



        //$test_game=$controller->getGame(); //commented out code was for testing purposes
        //$test_array=$test_game->getPlayers();

        //print_r($test_array);
    }

}

else
{
    header("location: index.php");
}
exit;