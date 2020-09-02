<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 3/5/2019
 * Time: 6:05 PM
 */

namespace Game;


class RoomNode extends Node
{

    /**
     * Stores ref to $item
     * @param $item to place in node
     */
    public function setContents($item){
        if (in_array($item, $this->myContents)){
            unset($this->myContents[$item->getName()]);
        }else {
            $this->myContents[$item->getName()] = &$item;
        }
    }

    /**
     * Get the contents of this node
     * @return mixed contents
     */
    public function getContents(){
        return $this->myContents;
    }

    private $myContents = array();

}