<?php
    class DirectoryManager
    {
        public static function getPath($path)
        { //Give paths from root
            $my_path = __DIR__;
            return str_replace("classes", "", $my_path).$path;
        }
    }