<?php
class Vps_Util_Check_Config
{
    public function check()
    {
        $checks = array();
        $checks['php'] = array(
            'name' => 'Php > 5.2'
        );
        $checks['memcache'] = array(
            'name' => 'memcache Php extension'
        );
        $checks['imagick'] = array(
            'name' => 'imagick Php extension'
        );
        $checks['gd'] = array(
            'name' => 'gd Php extension'
        );
        $checks['fileinfo'] = array(
            'name' => 'fileinfo Php extension'
        );
        $checks['simplexml'] = array(
            'name' => 'simplexml Php extension'
        );
        $checks['tidy'] = array(
            'name' => 'tidy Php extension'
        );
        $checks['pdo_mysql'] = array(
            'name' => 'pdo_mysql Php extension'
        );

        //ab hier wird die config geladen
        $checks['setup_vps'] = array(
            'name' => 'loading vps'
        );
        $checks['memcache_connection'] = array(
            'name' => 'memcache connection'
        );
        $checks['db_connection'] = array(
            'name' => 'db connection'
        );
        $checks['svn'] = array(
            'name' => 'svn'
        );

        $res = '<h3>';
        if (php_sapi_name()!= 'cli') {
            $res .= "Test Webserver...\n";
        } else {
            $res .= "Test Cli...\n";
        }
        $res .= '</h3>';
        foreach ($checks as $k=>$i) {
            $res .= "<p style=\"margin:0;\">";
            $res .= $i['name'].': ';
            try {
                call_user_func(array('Vps_Util_Check_Config', '_'.$k));
                $res .= "<span style=\"background-color:green\">OK</span>";
            } catch (Exception $e) {
                $res .= "<span style=\"background-color:red\">FAILED:</span> ".$e->getMessage();
            }
            $res .= "</p>";
        }

        echo $res;
        if (php_sapi_name()!= 'cli') {
            passthru("php bootstrap.php check-config", $ret);
            if ($ret) {
                echo "<span style=\"background-color:red\">FAILED:</span>";
            }
            echo  '<br /><br /> all tests finished';
        }
        exit;
    }

    private static function _php()
    {
        if (version_compare(PHP_VERSION, '5.1.6') < 0) {
            throw new Vps_Exception("Php version '".PHP_VERSION."' is too old");
        }
    }

    private static function _memcache()
    {
        if (!extension_loaded('memcache')) {
            throw new Vps_Exception("Extension 'memcache' is not loaded");
        }
    }

    private static function _imagick()
    {
        if (!extension_loaded('imagick')) {
            throw new Vps_Exception("Extension 'imagick' is not loaded");
        }
    }

    private static function _gd()
    {
        if (!extension_loaded('gd')) {
            throw new Vps_Exception("Extension 'gd' is not loaded");
        }
    }

    private static function _fileinfo()
    {
        if (!extension_loaded('fileinfo')) {
            throw new Vps_Exception("Extension 'fileinfo' is not loaded");
        }
    }

    private static function _simplexml()
    {
        if (!class_exists('SimpleXMLElement')) {
            throw new Vps_Exception("Extension 'simplexml' is not loaded");
        }
    }

    private static function _tidy()
    {
        if (!extension_loaded('tidy')) {
            throw new Vps_Exception("Extension 'tidy' is not loaded");
        }
    }

    private static function _pdo_mysql()
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new Vps_Exception("Extension 'pdo_mysql' is not loaded");
        }
    }

    private static function _setup_vps()
    {
        Vps_Setup::setUpVps();
    }

    private static function _memcache_connection()
    {
        $cache = Vps_Cache::factory('Core', 'Memcached');
        $cache->save('foo', 'bar');
        if ($cache->load('bar') != 'foo') {
            throw new Vps_Exception("Memcache doesn't return the saved value correctly");
        }
    }
    private static function _db_connection()
    {
        Vps_Registry::get('db')->query("SHOW TABLES")->fetchAll();
    }
    private static function _svn()
    {
        exec("svn info", $out, $ret);
        if ($ret) {
            throw new Vps_Exception("Svn command failed");
        }
    }
}