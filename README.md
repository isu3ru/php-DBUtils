##php-DBUtils

**A simple PHP class containing several mysql database-related functions that you can use in your own projects.**

1. `public function realEscape($val)` - A function to easily escape any value
2. `public function isInTheTable($table_name, $field_name, $value)` - Check if a given field contains a given value in a given table
3. `public function isInTheTableMultiple($table_name, $fields)` - Check if a given fields contain given values in a given table
4. `public function postValue($key, $num = FALSE)` - Grab escaped value from standard $_POST array
5. `public function getValue($key)` - Grab escaped value from standard $_GET array
6. `public function sessionValue($key)` - Grab escaped value from standard $_SESSION array
7. `public function cookieValue($key)` - Grab escaped value from standard $_COOKIE array
8. `public function arrayValue($key, $array, $default = '')` -  - Grab escaped value an array
9. `public function insert($table, $data)` - Create MySQL INSERT statement by providing the table name and an associative array containing fields and values

###Example Usage
  echo $dbutils->insert("mb_details", array(
    'brID' => 1,
    'rsCode' => 16515.1546,
    'desc' => "seyhgseh serhgrt'i'usehrig sergseg",
    'other' => NULL
  ));
