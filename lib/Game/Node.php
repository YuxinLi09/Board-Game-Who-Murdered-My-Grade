<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Carroll
 * Date: 2/17/2019
 * Time: 5:50 PM
 */

namespace Game;


/**
 * A node in the game graph
 */
class Node {


    /**
     * Add a connection to this node
     * @param $edge node to connect with
     */
    public function addEdge($edge){
        $this->to[] = &$edge;
    }

    public function setReachable($b){
        $this->reachable = $b;
    }


    /**
     * Get the connections of this node
     * @return array of edges
     */
    public function getEdges(){
        return $this->to;
    }

    /**
     * Stores ref to $item
     * @param $item to place in node
     */
    public function setContents($item){
        $this->contents = &$item;
    }

    /**
     * Get the contents of this node
     * @return mixed contents
     */
    public function getContents(){
        return $this->contents;
    }

    /**
     * Is this node currently reachable
     * @return bool
     */
    public function isReachable(){
        return $this->reachable;
    }

    /**
     * Set if the node is a room node or not
     * @param $val bool
     */
    public function setAsRoom($val){
        $this->room = $val;
    }

    /**
     * Sets current node as reachable then calls findOptions to search for other possible moves
     * @param $dist number of nodes away from main main to check
     */
    public function search($dist, &$moves){
        $this->reachable = true;
        $moves[] = ($this);
        $this->findOptions($dist, $moves);
    }

    /**
     * Search neighboring nodes recursively until a room node is found or search distance is met
     * @param $distance number of nodes away from main main to check
     */
    private function findOptions($distance, &$moves){
        // The path is done if it at the end of the distance
        if($distance === 0) {
            $this->reachable = true;
            $moves[] = ($this);
            return;
        }

        $this->onPath = true;

        foreach($this->to as $to) {
            if(!$to->blocked && !$to->onPath) {
                if ($to->room){
                    $to->findOptions(0, $moves);
                }else{
                    $to->findOptions($distance-1, $moves);
                }
            }
        }

        $this->onPath = false;
    }

    public function setBlocked($b){
        $this->blocked = $b;
    }


    private $contents;          // The object currently occupying the node
    private $room = false;      // Is this node a room node
    private $onPath = false;    // Is this node on the current path
    private $blocked = false;   // Is this node currently blocked
    private $reachable = false; // Is this node currently reachable
    private $to = [];           // Pointers to adjacent nodes
}