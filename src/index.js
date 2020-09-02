import './_characterselect-help.scss';
import './_win-lose-cards.scss';
import './_game.scss';

import $ from 'jquery';

import {Game} from './Game';
import {parse_json} from "./parse_json";

$(document).ready(function () {
    new Game('#board');

    //
    // Log the winning combo to the console
    //
    $.ajax({
        url: "log_winning_combo.php",
        success: function (data) {
            console.log("WINNING COMBO IS...");
            console.log(data);
        },
        error: function(xhr, status, error){
            console.log(error);
        }
    });
});
