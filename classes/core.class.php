<?php

    class CoreUtils
    {
        public static function Now()
        { //Me he motivado mucho lo sé xD
            return time();
        }
        public static function isValidUrl($url)
        {
            if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url))
                return true;
            return false;
        }
        public static function isValidMail($mail)
        {
            //Esto me es invalido, tendría que usar esto: http://stackoverflow.com/a/13719870
            //if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $mail))
            return filter_var($mail, FILTER_VALIDATE_EMAIL); //No me hace mucha gracia usar esto
        }
        public static function getUserAge($birthdate)
        {
            return floor((time()-$birthdate)/(3600*24*365));
        }
        public static function getLocation($ip = null, $api = 0)
        {
            //Api = 0 => Geoplugin
            //Api = 1 => IP-Api

            if($ip == null)
                $ip = $_SERVER["REMOTE_ADDR"];

            if($api == 0)
                $result = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=".$ip));
            else
                $result = unserialize(file_get_contents("http://ip-api.com/php/".$ip));

            /*if($ip_data && $ip_data->geoplugin_countryName != null){
                $result['city'] = $ip_data->geoplugin_city;
                $result['region'] = $ip_data->geoplugin_region;
                $result['areaCode'] = $ip_data->geoplugin_areaCode;
                $result['dmaCode'] = $ip_data->geoplugin_dmaCode;
                $result['countryCode'] = $ip_data->geoplugin_countryCode;
                $result['country'] = $ip_data->geoplugin_countryName;
                $result['continentCode'] = $ip_data->geoplugin_continentCode;
                $result['latitude'] = $ip_data->geoplugin_latitude;
                $result['longitude'] = $ip_data->geoplugin_longitude;
                $result['regionCode'] = $ip_data->geoplugin_regionCode;
                $result['region'] = $ip_data->geoplugin_regionName;
                $result['currencyCode'] = $ip_data->geoplugin_currencyCode;
                $result['currencySymbol'] = $ip_data->geoplugin_currencySymbol;
                $result['currencySymbol_UTF8'] = $ip_data->geoplugin_currencySymbol_UTF8;
                $result['currencyConverter'] = $ip_data->geoplugin_currencyConverter;
            }*/

            return $result;
        }
        public static function getUserAgentInfo($ua)
        {
            $result = unserialize(json_decode(file_get_contents("http://www.useragentstring.com/?uas=".$ua."&getJSON=all")));
            //{"agent_type":"Crawler","agent_name":"008","agent_version":"0.83","os_type":"unknown","os_name":"unknown","os_versionName":"","os_versionNumber":"","os_producer":"","os_producerURL":"","linux_distibution":"Null","agent_language":"","agent_languageTag":""}
            return $result;
        }
    }