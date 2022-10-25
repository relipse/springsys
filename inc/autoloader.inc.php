<?php
/**
 * SpringSys autoloader
 * Copyright (C) 2022 Jim Kinsman
 */
spl_autoload_register(function ($class) {
    $filepath = str_replace('\\', '/', $class).'.php';
    $file = __DIR__.'/../classes/'.$filepath;
    if (file_exists($file)){
        require_once($file);
    }else{
        throw new \Exception($file.' does not exist');
    }
});

//some helpful functions
require_once(__DIR__.'/misc.php');