<?php
class Kwf_Dao
{
    private $_config;
    private $_tables = array();
    private $_db = array();
    private $_pageData = array();

    public function __construct(array $config = null)
    {
        if (is_null($config)) {
            $cacheId = 'dbconfig';
            $config = Kwf_Cache_Simple::fetch($cacheId, $success);
            if (!$success) {
                if (file_exists('config.db.ini')) {
                    $config = new Zend_Config_Ini('config.db.ini', 'database');
                    $config = $config->toArray();
                } else {
                    $config = array();
                }
                Kwf_Cache_Simple::add($cacheId, $config);
            }
        }
        $this->_config = $config;
    }

    /**
     * @deprecated
     */
    public static function getTable($tablename, $config = array())
    {
        static $tables;
        if (!isset($tables[$tablename])) {
            $tables[$tablename] = new $tablename($config);
        }
        return $tables[$tablename];
    }

    public function getDbConfig($db = 'web')
    {
        if (!isset($this->_config[$db])) {
            throw new Kwf_Dao_Exception("Connection \"$db\" in config.db.ini not found.
                    Please add $db.host, $db.username, $db.password and $db.dbname under the sction [database].");
        }
        $dbConfig = $this->_config[$db];
        if (!isset($dbConfig['username']) && isset($dbConfig['user'])) $dbConfig['username'] = $dbConfig['user'];
        if (!isset($dbConfig['password']) && isset($dbConfig['pass'])) $dbConfig['password'] = $dbConfig['pass'];
        if (!isset($dbConfig['dbname']) && isset($dbConfig['name'])) $dbConfig['dbname'] = $dbConfig['name'];
        return $dbConfig;
    }

    public function getDb($db = 'web')
    {
        if (!isset($this->_db[$db])) {
            $dbConfig = $this->getDbConfig($db);
            $this->_db[$db] = Zend_Db::factory('PDO_MYSQL', $dbConfig);
            $this->_db[$db]->query('SET names UTF8');

            /**
             * lc_time_names wird hier nicht gesetzt weil man für trlKwf
             * momentan das userModel benötigt und das gibts ohne DB
             * Verbindung nicht -> Endlosschleifen gefahr.
             * lc_time_names wurde früher vermutlich im TreeCache noch benötigt
             * (z.B. bei den News Month), aber das macht jetzt das PHP, dehalb
             * ist es nicht mehr nötig dies zu setzen.
             */
//             $this->_db[$db]->query("SET lc_time_names = '".trlKwf('en_US')."'");


            if (Kwf_Config::getValue('debug.querylog')) {
                $profiler = new Kwf_Db_Profiler(true);
                $this->_db[$db]->setProfiler($profiler);
            } else if (Kwf_Config::getValue('debug.queryTimeout')) {
                $profiler = new Kwf_Db_Profiler_Timeout(Kwf_Config::getValue('debug.queryTimeout'), true);
                $this->_db[$db]->setProfiler($profiler);
            } else if (Kwf_Config::getValue('debug.benchmark') || Kwf_Config::getValue('debug.benchmarkLog')) {
                $profiler = new Kwf_Db_Profiler_Count(true);
                $this->_db[$db]->setProfiler($profiler);
            }
        }
        return $this->_db[$db];
    }

    public function hasDb($db = 'web')
    {
        return isset($this->_db[$db]);
    }

    public function getMongoDb()
    {
        static $ret;
        if (!isset($ret)) {
            $connection = new Mongo(); // connects to localhost:27017
            $mongoDb = Kwf_Config::getValue('server.mongo.database');
            $ret = $connection->$mongoDb;
        }
        return $ret;
    }
}