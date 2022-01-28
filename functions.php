<?php


namespace Parser\Helper {
    function random_string(): string {
        $validSims = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
        $name = "";
        for ($i = 0; $i < rand(5, 12); $i++) {
            $name .= $validSims[rand(0, strlen($validSims) - 1)];
        }
        return $name;
    }
}

namespace global {
    function d(...$values) {
        foreach ($values as $value) {
            echo '<pre>';
            var_dump($value);
            echo '</pre>';
        }
    }
}