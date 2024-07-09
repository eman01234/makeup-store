<?php

spl_autoload_register('load');

function load($class){
    $path = 'mvc/';
    $extension = '.class.php';

    $file = $path.$class.$extension;

    if(!file_exists($file)){
        return false;
    }

    include_once $file;
    
}
?>