import $ from 'jquery';

import {parse_json} from './parse_json';

export function Game(fselect) {
    this.form = $(fselect);

    if (!this.form || this.form.length < 1) {
        return;
    }

    this.attachListeners();
}

/**
 * A button on the board was clicked.
 * @param button
 */
Game.prototype.onBoard = function(button) {
    console.log('b.click');

    $.ajax({
        url: "game-post.php",
        data: {dest: $(button).val()},
        method: "POST",
        dataType: "text",
        success: function (data) {
            var b = $("body");
            var json = parse_json(data);

            var html = "<header>\n" +
                "<h1>Who Murdered My Grade</h1>\n" +
                "</header>" +
                json.board;
            b.html(html);
            new Game("#board");
        },
        error: function(xhr, status, error){
            console.log(error);
        }
    });
};

/**
 * The form should be submitted, a submit button was clicked.
 * @param input
 */
Game.prototype.onSubmit = function(input) {
    var that = this;

    var obj = {};

    if($("input:checked").attr("name") == "room-next") {
        obj[$("input:checked").attr("name")] = $("input:checked").val();
    }else if($(input).attr("name") === undefined) {
        obj["jump"] = 1;
    }else if($(input).attr("name") == "judgment"){
        obj["judgment"] = "Go";
    }else{
        obj[$("input:checked").attr("name")] = $("input:checked").val();
        obj[$(input).attr("name")] = $(input).val();
    }


    $.ajax({
        url: "game-post.php",
        data: obj,
        method: "POST",
        dataType: "text",
        success: function (data) {
            var b = $("body")
            var json = parse_json(data);
            var html = "<header>\n" +
                "<h1>Who Murdered My Grade</h1>\n" +
                "</header>" +
                json.board;
            b.html(html);
            new Game("#board");


        },
        error: function(xhr, status, error){
            console.log(error);
        }
    });

};

Game.prototype.attachListeners = function() {
    var that = this;

    this.form.find('[type="submit"]').click(function(evt) {
        evt.preventDefault();

        if ($(this).hasClass('reach')) {
            that.onBoard(this);
        } else {
            that.onSubmit(this);
        }
    });
};
