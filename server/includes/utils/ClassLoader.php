<?php
    function phpClassLoader($phpClassName):bool {
        $parentPath = explode("includes", __DIR__)[0] . "classes/";
        $classExtension = ".php";

        $finalPath = $parentPath . $phpClassName . $classExtension;

        if (!file_exists($finalPath)) {
            return false;
        }

        include_once($finalPath);
        return true;
    }

    spl_autoload_register("phpClassLoader");
?>