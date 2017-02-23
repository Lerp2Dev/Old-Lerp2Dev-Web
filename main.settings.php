<?php
    include('main.dev.php');
    class Settings
    {
        protected static $host;
        protected static $dbuser;
        protected static $dbpass;
        protected static $dbname;
        protected static $dbport;
        public static $adminpass = '1234';

        public static function getSettings()
        {
            DevProfiles::getProfile();
        }
    }