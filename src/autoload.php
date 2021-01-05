<?php

/**
 * Paystack Autoloader
 * For use when library is being used without composer
 */

$paystack_autoloader = function ($class_name) {
    if (strpos($class_name, 'YeThird\ThirdSdk')===0) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $file .= str_replace([ 'YeThird\\', '\\' ], ['', DIRECTORY_SEPARATOR ], $class_name) . '.php';
        include_once $file;
    }
};

spl_autoload_register($paystack_autoloader);

return $paystack_autoloader;
