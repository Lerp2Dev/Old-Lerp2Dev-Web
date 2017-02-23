<?php
    class SessionManager
    {
        public static function getLoginInfo($session_id)
        {
            return Query::first("SELECT * FROM login_sessions WHERE session_id = '$session_id'");
        }
    }