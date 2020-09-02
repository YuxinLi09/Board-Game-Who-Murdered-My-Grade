<?php
/**
 * Created by PhpStorm.
 * User: jstri
 * Date: 2/21/2019
 * Time: 12:28 PM
 */

function present_header($title)
{
    $html = <<<HTML
<header>
<h1>$title</h1>
</header>
HTML;

    return $html;
}