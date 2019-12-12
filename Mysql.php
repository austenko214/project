<?php

namespace db;

class Mysql
{
    const DB_RES = 'RES';
// //    const MAX_ALLOWED_PACKET = 33554432; //32*1024*1024;

    private static $transaction_pool = [
        self::DB_RES => null,
    ];
    private static $db_pool = [
        self::DB_RES => null,
    ];
    private static $requests_log;

    private static function getMysqli($current_db = self::DB_RES, $reconnect = false)
    {
        if (empty(self::$db_pool[$current_db]) || $reconnect) {
            $dbConParams = self::getDbConParams($current_db);
            if (empty($dbConParams))
                self::outputDbError(['db' => $current_db, 'msg' => "DB connection params not found"]);
            $wait_time = 25 * pow(10, 4);
            $max_wait_time = 16 * pow(10, 6); //16 seconds
            while (1) {
                $mysqli = new \mysqli(
                    $dbConParams['host'],
                    $dbConParams['user'],
                    $dbConParams['pass'],
                    $dbConParams['name']
                );
                if ($mysqli->connect_error || !$mysqli->set_charset('utf8')) {
                    if (self::isMysqliConnectionLost($mysqli)) {
                        if ($wait_time < $max_wait_time) {
                            $wait_time *= 2;
                            usleep($wait_time);
                            continue;
                        }
                        $msg = "$current_db DB Connection failed after reaching max wait time";
                        self::outputDbError(['db' => $current_db, 'msg' => $msg]);
                    } else {
                        $msg = "$current_db DB Connection failed. {$mysqli->connect_error}.\n" . var_export($mysqli, 1);
                        self::outputDbError(['db' => $current_db, 'msg' => $msg]);
                    }
                }
                self::$db_pool[$current_db] = $mysqli;
                break;
            }
        }
        return self::$db_pool[$current_db];
    }

// //    private static function simpleGetMysqli($currentDb = self::DB_CONF, $connectionTimeout = 1)
// //    {
// //        if (empty(self::$db_pool[$currentDb])) {
// //            $dbConParams = self::getDbConParams($currentDb);
// //            if (empty($dbConParams)) {
// //                Debug::sendDebugInfo("DB $currentDb connection params not found", '');
// //                return null;
// //            }
// //            $mysqli = mysqli_init();
// //            if (!$mysqli) {
// //                return null;
// //            }
// //            $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, $connectionTimeout);
// //            $mysqli->real_connect(
// //                $dbConParams['host'],
// //                $dbConParams['user'],
// //                $dbConParams['pass'],
// //                $dbConParams['name']
// //            );
// //            if ($mysqli->errno || $mysqli->connect_errno) {
// //                $msg = "$currentDb DB Connection failed. {$mysqli->connect_error}.\n" . var_export($mysqli, 1);
// //                Debug::sendDebugInfo("$currentDb DB Connection failed", $msg);
// //                return null;
// //            }
// //            self::$db_pool[$currentDb] = $mysqli;
// //        }
// //        return self::$db_pool[$currentDb];
// //    }

    private static function getDbConParams($db)
    {
        foreach (['HOST', 'USER', 'PASS', 'NAME'] as $var_name)
            if (!defined($db . '_DB_' . $var_name))
                return [];
        return [
            'host' => constant($db . '_DB_HOST'),
            'user' => constant($db . '_DB_USER'),
            'pass' => constant($db . '_DB_PASS'),
            'name' => constant($db . '_DB_NAME'),
        ];
    }

// //    public static function insertRowAsync($tableName, $data, $currentDb = self::DB_CONF)
// //    {
// //        $mysqli = self::simpleGetMysqli($currentDb);
// //        if ($mysqli === null) {
// //            return false;
// //        }
// //        $keys = implode(', ', array_keys($data));
// //        $values = array_values($data);
// //        foreach ($values as &$value) {
// //            $value = $mysqli->escape_string($value);
// //            $value = "'{$value}'";
// //        }
// //        unset($value);
// //        $values = implode(', ', $values);
// //        return $mysqli->query("INSERT INTO {$tableName} ({$keys}) VALUES ({$values})", MYSQLI_ASYNC);
// //    }

    private static function isMysqliConnectionLost($mysqli)
    {
        static $mysqli_errors = [
            2001, //Can't create UNIX socket (%d)
            2002, //Can't connect to local MySQL server through socket '%s' (%d)
            2003, //Can't connect to MySQL server on '%s' (%d)
            2004, //Can't create TCP/IP socket (%d)
            2005, //Unknown MySQL server host '%s' (%d)
            2006, //MySQL server has gone away
            2008, //MySQL client ran out of memory
            2012, //Error in server handshake
            2013, //Lost connection to MySQL server during query
            2014, //Commands out of sync; you can't run this command now
            2024, //Error connecting to slave:
            2025, //Error connecting to master:
            2026, //SSL connection error: %s
            2045, //Can't open shared memory; no answer from server (%lu)
            2048, //Invalid connection handle
            2055, //Lost connection to MySQL server at '%s', system error: %d
            1053, //Server shutdown in progress
        ];
        return
            !empty($mysqli->errno) && in_array($mysqli->errno, $mysqli_errors) ||
            !empty($mysqli->connect_errno) && in_array($mysqli->connect_errno, $mysqli_errors);
    }

    public static function runQuery($query, $affected_rows = false, $current_db = self::DB_RES)
    {
        $mysqli = self::getMysqli($current_db);
        $start = microtime(true);
        $result = $mysqli->query($query);
        $query_time = microtime(true) - $start;
        if ($result == false && !self::$transaction_pool[$current_db] && self::isMysqliConnectionLost($mysqli)) {
            $mysqli = self::getMysqli($current_db, true);
            $start = microtime(true);
            $result = $mysqli->query($query);
            $query_time = microtime(true) - $start;
        }
        if ($result == false)
            self::outputDbError(
                ['db' => $current_db, 'msg' => 'Query: ' . $query . ' | Error message: ' . $mysqli->error]
            );
            self::$requests_log[] = [
                'query' => $query,
                'time' => $query_time,
                'db' => $current_db,
            ];
        if ($affected_rows) {
            return $mysqli->affected_rows;
        }
        return $result;
    }

// //    public static function getRequestsLog()
// //    {
// //        return self::$requests_log;
// //    }
// //
// //    public static function simpleRunQuery($query, $current_db = self::DB_CONF)
// //    {
// //        $mysqli = self::getMysqli($current_db);
// //        $result = $mysqli->query($query);
// //        if (!$result) {
// //            return $mysqli->error;
// //        }
// //        return true;
// //    }
// //
// //    public static function multiInsert($table_name, $params)
// //    {
// //        if (is_array($params)) {
// //            $fields = implode(',', array_keys($params[0]));
// //            $sql = [];
// //            foreach ($params as $row) {
// //                $row = array_map('addslashes', $row);
// //                $sql[] = "('" . implode("','", $row) . "')";
// //            }
// //            return self::runQuery("INSERT INTO {$table_name} ({$fields}) VALUES " . implode(',', $sql));
// //        }
// //        return false;
// //    }

    public static function fetchAssoc($query, $current_db = self::DB_RES)
    {
        $result = self::runQuery($query, false, $current_db);
        $data = [];
        while ($row = $result->fetch_assoc())
            $data[] = $row;
        return $data;
    }

//     public static function fetchAssocWithKey($query, $key, $current_db = self::DB_CONF)
//     {
//         $result = self::runQuery($query, false, $current_db);
//         $data = [];
//         while ($row = $result->fetch_assoc())
//             $data[$row[$key]] = $row;
//         return $data;
//     }

// //    public static function fetchArrayOneField($query, $current_db = self::DB_CONF)
// //    {
// //        $result = self::runQuery($query, false, $current_db);
// //        $data = [];
// //        while ($row = $result->fetch_row()) {
// //            $data[] = $row[0];
// //        }
// //        return $data;
// //    }

    public static function fetchOneAssoc($query, $current_db = self::DB_RES)
    {
        $result = self::runQuery($query, false, $current_db);
        $data = $result->fetch_assoc();
        if ($result->num_rows > 1)
            trigger_error("In fetch_one_assoc(query) result has more then one row:\n$query\n");
        return $data;
    }

   public static function fetchScalar($query, $current_db = self::DB_RES)
   {
       $result = self::runQuery($query, false, $current_db);
       $data = $result->fetch_row();
       if (!$data) 
           return false;
       return $data[0];
   }

    public static function lastInsertId($current_db = self::DB_CONF)
    {
        return self::getMysqli($current_db)->insert_id;
    }

    public static function getTableFields($table, $current_db = self::DB_RES)
    {
        static $fields_names = [];
        if (!empty($fields_names[$table]))
            return $fields_names[$table];
        $sql = "SHOW COLUMNS FROM $table";
        $fields = self::fetchAssoc($sql, $current_db);
        foreach ($fields as $field)
            $fields_names[$table][$field['Field']] = $field['Type'];
        return $fields_names[$table];
    }

// //    public static function checkUniqueField($table, $field, $val, $exception = '', $current_db = self::DB_CONF)
// //    {
// //        $sql = "SELECT 1 FROM " . addslashes($table)
// //            . " WHERE `" . addslashes($field) . "`='" . addslashes($val) . "'";
// //        if ($exception) {
// //            $sql .= " AND " . addslashes($exception);
// //        }
// //        $sql .= " LIMIT 1";
// //        return self::fetchScalar($sql, $current_db);
// //    }
// //
    public static function saveRow($table_name, $params, $current_db = self::DB_RES)
    {
        if (empty($table_name) || empty($params))
            return false;
        $order_fields = self::getTableFields($table_name, $current_db);
        $sql_set_string = '';
        foreach ($params as $key => $val) {
            if ($key === 'id' || !isset($val))
                continue;
            if (isset($order_fields[$key]))
                $sql_set_string .= "`" . addslashes($key) . "`='" . addslashes($val) . "',";
        }
        $sql_set_string = rtrim($sql_set_string, ',');
        //in case of update
        if (isset($params['id'])) {
            $id = (int)$params['id'];
            $sql = "UPDATE " . addslashes($table_name) . " SET {$sql_set_string} WHERE id = {$id}";
            return self::runQuery($sql, false, $current_db);
        }
        //in case of insert
        $sql = "INSERT INTO " . addslashes($table_name) . " SET {$sql_set_string}";
        if (!self::runQuery($sql, false, $current_db))
            return false;
        return self::lastInsertId($current_db);
    }

// //    public static function startTransaction($current_db = self::DB_CONF)
// //    {
// //        if (self::$transaction_pool[$current_db]) {
// //            return true;
// //        }
// //        if (self::runQuery("START TRANSACTION", false, $current_db)) {
// //            self::$transaction_pool[$current_db] = true;
// //        }
// //        return isset(self::$transaction_pool[$current_db]) ? self::$transaction_pool[$current_db] : false;
// //    }
// //
// //    public static function commitTransaction($current_db = self::DB_CONF)
// //    {
// //        if (!self::$transaction_pool[$current_db]) {
// //            Debug::sendDebugInfo(
// //                'commitTransaction() used but transaction not started',
// //                'commitTransaction() used but transaction not started on DB ' . $current_db
// //            );
// //            return;
// //        }
// //        self::$transaction_pool[$current_db] = false;
// //        self::runQuery('COMMIT', false, $current_db);
// //    }
// //
// //    public static function rollbackTransaction($current_db = self::DB_CONF)
// //    {
// //        if (!self::$transaction_pool[$current_db]) {
// //            Debug::sendDebugInfo(
// //                'rollbackTransaction() used but transaction not started',
// //                'rollbackTransaction() used but transaction not started on DB ' . $current_db
// //            );
// //            return;
// //        }
// //        self::$transaction_pool[$current_db] = false;
// //        self::runQuery('ROLLBACK', false, $current_db);
// //    }

    private static function outputDbError($data)
    {
        throw new DBException($data['db'] . " - " . $data['msg']);
    }

// //    public static function escapeLike($str)
// //    {
// //        return addslashes(addcslashes($str, "_%"));
//    }
}
