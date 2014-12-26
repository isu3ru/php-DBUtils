<?php

/**
 * Description of dbutils
 *
 * @author isuru
 */
class DButils {

    var $conn;
    var $hostname;
    var $user;
    var $pass;

    /**
     * Create a new instance of DBUtils class<br />
     * <p>Example usage: </p>
     * <code>$dbutils = new dbutils('localhost', 'root', 'mdcc');</code>
     */
    public function __construct($hostname, $user, $pass) {
        $this->hostname = $hostname;
        $this->user = $user;
        $this->pass = $pass;
        $this->conn = $this->_connect($this->user, $this->pass, $this->hostname);
    }

    private function _connect($host, $user, $pass) {
        return mysql_connect($host, $user, $pass);
    }

    public function realEscape($val) {
        $ret = FALSE;
        if ($val) {
            if (!is_array($val)) {
                //if magic quotes are enabled;-
                if (get_magic_quotes_gpc()) {
                    //strip off the slashes and escape, then return
                    $ret = mysql_real_escape_string(stripslashes($val));
                } else {
                    // escape the string and return
                    $ret = mysql_real_escape_string($val);
                }
            } else {
                $ret = $val;
            }
        } else {
            $ret = $val;
        }

        return $ret;
    }

    /**
     * Checks for a specific value in a specific field in a specific database table
     * returns true if found in the given table, otherwise returns false.
     *
     * Ex: $bool = isInTheTable('customers','customer_id','5005');
     */
    public function isInTheTable($table_name, $field_name, $value) {
        $query = "SELECT {$field_name} FROM {$table_name} WHERE {$field_name} = '{$value}'";
        $result = query($query);
        if ($result) {
            if (mysql_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Checks for a specific value in a specific field in a specific database table
     * returns true if found in the given table, otherwise returns false.
     *
     * array cotaining keys and values ($key = column name, $value = column value)
     * <code>$keysarray = array(
     * "lgi_id" => '2',
     * "lgi_name" => 'Wariyapola Pradeshiya Sabha'
     * );</code>
     */
    public function isInTheTableMultiple($table_name, $fields) {
        $size = count($fields);
        $query = "SELECT * FROM {$table_name}";
        if ($size > 0) {
            $query .= " WHERE ";
            $num = 0;
            foreach ($fields as $key => $value) {
                $num++;
                $query .= "{$key} = '{$value}'";
                if ($num != $size) {
                    $query .= " AND ";
                }
            }
        }

        $result = query($query);
        if ($result) {
            if (mysql_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function postValue($key, $num = FALSE) {
        if (array_key_exists($key, $_POST)) {
            $var = $_POST[$key];
            if (is_array($var)) {
                return $var;
            } else {
                if (is_string($var)) {
                    if ($num) {
                        if (empty($var)) {
                            return intval(0);
                        } else {
                            return realEscape($var);
                        }
                    } else {
                        return realEscape($var);
                    }
                } else {
                    return realEscape($var);
                }
            }
        } else {
            return $num ? intval(0) : FALSE;
        }
    }

    public function getValue($key) {
        return array_key_exists($key, $_GET) ? realEscape($_GET[$key]) : '';
    }

    public function sessionValue($key) {
        return array_key_exists($key, $_SESSION) ? realEscape($_SESSION[$key]) : '';
    }

    public function cookieValue($key) {
        return array_key_exists($key, $_COOKIE) ? realEscape($_COOKIE[$key]) : '';
    }

    public function arrayValue($key, $array, $default = '') {
        if (!is_array($array)) {
            return 0;
        }
        return array_key_exists($key, $array) ? ((empty($array[$key])) ? $default : realEscape($array[$key])) : $default;
    }

    /**
     * Create a SQL INSERT stateent ready to be submitted to a mysql server instance<br />
     * <p>Example Usage: </p>
     * <pre>
     * echo $dbutils->insert("mb_details", array(
     *     'brID' => 1,
     *     'rsCode' => 16515.1546,
     *     'desc' => "seyhgseh serhgrt'i'usehrig sergseg",
     *     'other' => NULL
     * ));
     * </pre>
     */
    public function insert($table, $data) {
        //INSERT INTO [table](fields) VALUES()
        if (empty($data)) {
            return FALSE;
        }
        $fks = array();
        foreach (array_keys($data) as $kv) {
            $fks[] = sprintf("`%s`", $this->realEscape($kv));
        }
        
        $fvals = array();
        foreach (array_values($data) as $fv) {
            if (is_string($fv)) {
                $fvals[] = sprintf("'%s'", $this->realEscape($fv));
            }
            if (is_null($fv)) {
                $fvals[] = "NULL";
            }
            if (is_numeric($fv)) {
                if (is_float($fv)) {
                    $fvals[] = sprintf("%f", $fv);
                } else {
                    $fvals[] = sprintf("%d", $fv);
                }
            }
        }

        return sprintf("INSERT INTO `%s` (%s) VALUES(%s)", $this->realEscape($table), implode(", ", $fks),implode(", ", $fvals));
    }

}
