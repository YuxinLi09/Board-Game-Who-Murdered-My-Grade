<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Carroll
 * Date: 2/15/2019
 * Time: 11:15 AM
 */

namespace Game;

/**
 * Class Board: The board the game is played on
 * @package Game
 */
class Board
{
    const NAMES = array("P1"=>"Professor McCullen",
        "P2" => "Professor Owen",
        "P3" => "Professor Day",
        "P4" => "Professor Plum",
        "P5" => "Professor Enbody",
        "P6" => "Professor Onsay");
    const SHOWMOVENAMES = array("Prof. McCullen"=>"Professor McCullen",
        "Prof. Owen" => "Professor Owen",
        "Prof. Day" => "Professor Day",
        "Prof. Plum" => "Professor Plum",
        "Prof. Enbody" => "Professor Enbody",
        "Prof. Onsay" => "Professor Onsay");

    const LOCATIONS = array("1" => "International",
        "2" => "Breslin",
        "3" => "Beaumont",
        "4" => "Union",
        "5" => "Museum",
        "6" => "Library",
        "7" => "Wharton",
        "8" => "Stadium",
        "9" => "Engineering");

    const SUSPECT_IMAGES = array("Professor Day"=>"images/day-piece.png",
        "Professor Enbody"=>"images/enbody-piece.png",
        "Professor McCullen"=>"images/mccullen-piece.png",
        "Professor Onsay"=>"images/onsay-piece.png",
        "Professor Owen"=>"images/owen-piece.png",
        "Professor Plum"=>"images/plum-piece.png");

    /**
     * Board constructor. creates a 2-D array of arrays that represent the cells
     * index [0:$h-1][0:$w-1]
     *       [row][height]
     * @param $map string the file path to a csv file that describes the map
     */
    public function __construct($map, $players)
    {
        // load map data into array
        $data = array_map('str_getcsv', file($map));

        // start row count
        $row_count = 0;


        // foreach row
        foreach($data as $row){

            // add an empty row to grid
            $this->grid[$row_count] = array();

            // start column count
            $col_count = 0;

            // foreach cell
            foreach($row as $cell){

                // X: Out of bounds cell
                if ($cell == "X"){
                    $this->grid[$row_count][] = new Node();

                // H: Hall cell
                }elseif ($cell == "H" or $cell == "HD" or substr($cell, 0, 1) == "P"){

                    // make new node
                    $node = new Node();

                    // Add left/right edges
                    if($col_count != 0) {

                        // other hall cells
                        if (strpos("HP", $data[$row_count][$col_count - 1][0]) !== false) {
                            $node->addEdge($this->grid[$row_count][$col_count - 1]);
                            $this->grid[$row_count][$col_count - 1]->addEdge($node);

                        // Door cells
                        } elseif (is_numeric(substr($data[$row_count][$col_count - 1], 0, -1)) and substr($data[$row_count][$col_count - 1], -1) == "D") {
                            $node->addEdge($this->grid[$row_count][$col_count - 1]);
                            $this->grid[$row_count][$col_count - 1]->addEdge($node);
                        }
                    }


                    // Add Up/Down edges
                    if ($row_count != 0) {

                        // other hall cells
                        if (strpos("HP", $data[$row_count - 1][$col_count][0]) !== false) {
                            $node->addEdge($this->grid[$row_count - 1][$col_count]);
                            $this->grid[$row_count - 1][$col_count]->addEdge($node);

                        // Door cells
                        } elseif (is_numeric(substr($data[$row_count - 1][$col_count], 0, -1)) and substr($data[$row_count - 1][$col_count], -1) == "D") {
                            $node->addEdge($this->grid[$row_count - 1][$col_count]);
                            $this->grid[$row_count - 1][$col_count]->addEdge($node);
                        }
                    }

                    if (substr($cell, 0, 1) == "P") {
                        // place node in the grid with player
                        foreach ($players as $player) {
                            if ($player->getName() == self::NAMES[$cell]) {
                                $node->setContents($player);
                                $this->grid[$row_count][] = $node;
                                $this->player_nodes[$player->getName()] = &$this->grid[$row_count][$col_count];
                                break;
                            }
                        }
                        if (array_key_exists($col_count, $this->grid[$row_count]) === false){
                            $node->setContents(new Player(self::NAMES[$cell]));
                            $this->grid[$row_count][] = $node;
                            $this->player_nodes[self::NAMES[$cell]] = &$this->grid[$row_count][$col_count];
                        }

                    }else {
                        // place node in the grid
                        $this->grid[$row_count][] = $node;
                    }



                // Number: This is a room cell
                }elseif(is_numeric($cell)){

                    // if the door node does not exist, make it
                    if (array_key_exists($cell, $this->rooms) == false){
                        $this->rooms[$cell] = new RoomNode();
                        $this->rooms[$cell]->setAsRoom(true);
                    }

                    // add ref to door to grid
                    $this->grid[$row_count][] = &$this->rooms[$cell];

                // Number + D: This is a room cell that connects to a hall
                }elseif($cell !== null and is_numeric(substr($cell, 0,-1)) and substr($cell, -1) == "D"){

                    // get index
                    $sub = substr($cell, 0,-1);

                    // if the door node does not exist, make it
                    if (array_key_exists($sub, $this->rooms) == false){
                        $this->rooms[$sub] = new RoomNode();
                    }

                    // add ref to door to grid
                    $this->grid[$row_count][] = &$this->rooms[$sub];

                    // Add edge between door and hallway when door is below or to the right of a hall cell
                    if($col_count != 0 and "HD" == $data[$row_count][$col_count - 1]){
                        $this->grid[$row_count][$col_count]->addEdge($this->grid[$row_count][$col_count - 1]);
                        $this->grid[$row_count][$col_count - 1]->addEdge($this->grid[$row_count][$col_count]);
                    }elseif($row_count != 0 and "HD" == $data[$row_count - 1][$col_count]){
                        $this->grid[$row_count][$col_count]->addEdge($this->grid[$row_count - 1][$col_count]);
                        $this->grid[$row_count - 1][$col_count]->addEdge($this->grid[$row_count][$col_count]);
                    }
                }

                // Any other cell, player start positions
                else{
                    $this->grid[$row_count][] = new Node();
                }
                $col_count++;
            }
            $row_count++;
        }
    }


    /**
     * @param $row row index of desired cell
     * @param $col column index of desired cell
     * @return mixed the contents of the cell
     */
    public function getContents($row, $col){
        if ($this->grid[$row][$col] === null){
            return null;
        }
        return $this->grid[$row][$col]->getContents();
    }


    /**
     * @param $row row of node
     * @param $col col of node
     * @return null array of edges
     */

    public function getEdges($row, $col){
        if ($this->grid[$row][$col] === null){
            return null;
        }
        return $this->grid[$row][$col]->getEdges();
    }

    /**
     * Sets the isReachable flags on nodes
     * @param $row row of node
     * @param $col col of node
     * @param $dist distance to check
     */
    public function showMoves($name, $dist){
        $player = $this->player_nodes[$name];

        // create secret passages
        if(in_array($player, $this->rooms)){
            if(array_search($player, $this->rooms) == 9){
                $this->rooms[1]->search(0, $this->moves);
            }elseif(array_search($player, $this->rooms) == 1){
                $this->rooms[9]->search(0, $this->moves);
            }elseif(array_search($player, $this->rooms) == 3){
                $this->rooms[7]->search(0, $this->moves);
            }elseif(array_search($player, $this->rooms) == 7){
                $this->rooms[3]->search(0, $this->moves);
            }
        }
        $player->search($dist, $this->moves);
    }

    /**
     * Reset the isReachable flag to false
     */
    public function clearMoves(){
        foreach ($this->moves as $move){
            $move -> setReachable(false);
        }
        $this->moves = array();
    }

    /**
     * Setter for grid cell
     * @param $row row index to set
     * @param $col column index to set
     * @param null $cont content reference to set the cell
     */
    public function setContents($row, $col, $cont){
        $this->grid[$row][$col]->setContents($cont);
    }

    /**
     * Generates the board as html
     * @return string html
     */
    public function presentBoard(){
        $this->displayed_players = array();
        $html = "<div class = 'board'>";
        for ($row = 0; $row < count($this->grid); $row++) {
            $row_html = "<div class = 'row'>";
            for ($col = 0; $col < count($this->grid[0]); $col++){

                if ($this->grid[$row][$col]->isReachable()){
                    $row_html .= "<button class='reach' type='submit' name='dest' value=\"$row, $col\">";
                    $row_html .= $this->addPicture($row, $col);
                    $row_html .= "</button>";
                } else{
                    $row_html .= "<div class='cell'>";
                    $row_html .= $this->addPicture($row, $col);
                    $row_html .= "</div>";
                }

            }
            $html .="\n".$row_html."</div>";
        }
        $html.="</div>";
        return $html;
    }

    public function move($name, $row, $col){
        if ($this->player_nodes[$name] instanceof RoomNode){
            $player = $this->player_nodes[$name]->getContents()[$name];
            $this->player_nodes[$name]->setContents($player);
        }else {
            $player = $this->player_nodes[$name]->getContents();
            $this->player_nodes[$name]->setContents(null);
        }

        $this->player_nodes[$name]->setBlocked(false);
        $this->player_nodes[$name] = &$this->grid[$row][$col];
        $this->player_nodes[$name]->setContents($player);
        $this->player_nodes[$name]->setBlocked(true);
        if (in_array($this->player_nodes[$name], $this->rooms)){
            $this->player_nodes[$name]->setBlocked(false);
            $player->setInRoom(true);
            $player->setRoom(self::LOCATIONS[array_search($this->player_nodes[$name], $this->rooms)]);
        }else{
            $player->setInRoom(false);
            $player->setRoom(null);
        }
        $player->setPos($row, $col);


    }

    private function addPicture($row, $col){
        // no piece
        if ($this->grid[$row][$col]->getContents() === null or $this->grid[$row][$col]->getContents() == array()){
            return "";


        // with piece
        } else {
            if(in_array($this->grid[$row][$col], $this->rooms) == false){
                $picture = self::SUSPECT_IMAGES[$this->grid[$row][$col]->getContents()->getName()];
                $this->displayed_players[] = $this->grid[$row][$col]->getContents()->getName();
                return "<img src=\"$picture\" >";
            } elseif ($this->checkRoom($row, $col)){
                if(!($this->grid[$row]{$col} instanceof RoomNode) and in_array($this->grid[$row][$col]->getContents()->getName(), $this->displayed_players) == false) {
                    $picture = self::SUSPECT_IMAGES[$this->grid[$row][$col]->getContents()->getName()];
                    $this->displayed_players[] = $this->grid[$row][$col]->getContents()->getName();
                    return "<img src=\"$picture\" >";
                }else{
                    foreach ($this->grid[$row][$col]->getContents() as $player){
                        if (in_array($player->getName(), $this->displayed_players) == false){
                            $picture = self::SUSPECT_IMAGES[$player->getName()];
                            $this->displayed_players[] = $player->getName();
                            return "<img src=\"$picture\" >";
                        }
                    }
                }

            }else{
                return "";
            }
        }
    }

    private function checkRoom($row, $col){
        if ($row == 0 or $col == 0 or $row == sizeof($this->grid) - 1 or $col == sizeof($this->grid[0]) - 1){
            return false;
        }else{
            if ($this->grid[$row - 1][$col] != $this->grid[$row][$col]){
                return false;
            }elseif($this->grid[$row + 1][$col] != $this->grid[$row][$col]){
                return false;
            }elseif($this->grid[$row][$col - 1] != $this->grid[$row][$col]){
                return false;
            }elseif($this->grid[$row][$col + 1] != $this->grid[$row][$col]){
                return false;
            }
            return true;
        }
    }

    private $displayed_players = array();
    private $moves = array();           // Nodes that are currently marked reachable
    private $player_nodes = array();    // Refs to the nodes that the players are currently on
    private $rooms = array();           // The nodes that represent the rooms
    protected $grid = array();          // The grid that represents the game board

}