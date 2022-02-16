<?php
/**
 * Initializations
 * 
 * Registers an autoloader to automatically load required classes
 * 
 */
spl_autoload_register(function ($class) {
    require dirname(__DIR__) . "/classes/{$class}.php";
});
