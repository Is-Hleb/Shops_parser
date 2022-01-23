<?php

function d(...$values) : void {
    foreach ($values as $value) {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }
}

function dd(...$values) {
    d($values);
}