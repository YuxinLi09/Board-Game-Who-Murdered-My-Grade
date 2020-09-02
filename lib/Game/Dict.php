<?php
/**
 * Created by PhpStorm.
 * User: samvitu
 * Date: 2019-02-27
 * Time: 16:47
 */

namespace Game;

/**
 * Class Dict stores all the card aliases in the game
 * @package Game
 */
class Dict
{
    const ENGLISH_DICT = __DIR__."/words_medium.txt";   // English dictionary

    private $card_aliases = array();  // array to hold the random words

    /**
     * Dict constructor.
     * @param int Number of aliases needed
     */
    public function __construct($num_aliases) {
        $this->generateAliases($num_aliases);
    }

    /**
     * Generates random words and populates $card_aliases
     * @param int The number of words to generate
     */
    public function generateAliases($num_aliases) {
        $i = 0;

        while($i < $num_aliases) {
            $file_arr = file(self::ENGLISH_DICT);
            $num_lines = count($file_arr);
            $last_arr_index = $num_lines - 1;
            $rand_index = rand(0, $last_arr_index);
            $rand_word = $file_arr[$rand_index];

            if(!in_array($rand_word, $this->card_aliases)) {
                array_push($this->card_aliases, $rand_word); // add unique word to array
            }

            // word already in array, generate new word
            else {
                continue;
            }

            $i++;
        }

        return;
    }

    /**
     * Get a random alias
     * Remove the alias from possible aliases so it can't be assigned more than once
     * @return string $alias Random card alias
     */
    public function getNewAlias() {
        $rand = array_rand($this->card_aliases);
        $alias = $this->card_aliases[$rand];
        //$index = array_search($alias, $this->card_aliases);
        //$alias = $this->card_aliases[$rand];  // get word at random index
        //$this->card_aliases = array_diff($this->card_aliases,[$alias]);
        unset($this->card_aliases[$rand]);    // remove the word so it can't be assigned again
        return $alias;
    }

    /**
     * Get all possible aliases
     * @return array Array of card aliases
     */
    public function getAliases() {
        return $this->card_aliases;
    }

}