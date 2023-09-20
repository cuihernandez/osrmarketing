<?php 
class Autoloader
{
    public static function loadClass($className)
    {
        $file = __DIR__ . '/../class/' . $className . '.class.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
}

spl_autoload_register('Autoloader::loadClass');