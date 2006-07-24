<?php

    class DBH {
        var $dbh;
        
        function DBH() {
            # do nothing? we will auto-connect to the db on the first call to one of our
            # data retrieval functions, so we don't need to do much...
        }

        # given a query "FOO" replace question marks with values from the array
        function _prepare_query($query, $array) {
            # replace "tbl:" with "evedev_" etc
            global $_TABLE_PREFIX;
            if ($_TABLE_PREFIX) {
                $query = preg_replace('/\btbl:/', $_TABLE_PREFIX, $query);
            }

            # now start replacing question marks with data, hopefully
            while (count($array) > 0) {
                $var = array_shift($array);

                $escape = 1;
                if (is_null($var)) {
                    $var = "NULL";
                    $escape = 0;
                } elseif (is_numeric($var)) {
                    $escape = 0;
                } elseif (is_array($var)) {
                    $temp = array();
                    foreach ($var as $v) {
                        if (is_numeric($v)) {
                            array_push($temp, $v);
                        } elseif (is_null($v)) {
                            array_push($temp, "NULL");
                        } else {
                            array_push($temp, "'" . mysql_real_escape_string($v) . "'");
                        }
                    }
                    $var = implode(',', $temp);
                    $escape = 0;
                }

                if ($escape) {
                    if (get_magic_quotes_gpc()) {
                        $var = stripslashes($var);
                    }
                    $var = "'" . mysql_real_escape_string($var, $this->_dbh()) . "'";
                }

                $query = preg_replace('/\?/', $var, $query, 1);
            }
global $_TEST, $_QQ;
if (!isset($_TEST)) {
    $_QQ = 0;
    $_TEST = array();
}
$_QQ++;
if (isset($_TEST[$query])) {
    echo "[[$query]] #$_QQ <font color='red'>DUPLICATE</font><br />\n";
} else {
#    echo "[[$query]] #$_QQ <br />\n";
}
#$_TEST[$query] = 1;
            return $query;
        }

        # get a db handle
        function _dbh() {
            if (! is_null($this->dbh)) {
                return $this->dbh;
            }

            # guess not, make one
            global $_MYSQL_HOST, $_MYSQL_USER, $_MYSQL_PASS, $_MYSQL_DB;
            $dbh = mysql_connect($_MYSQL_HOST, $_MYSQL_USER, $_MYSQL_PASS);
            if (! $dbh) {
                # FIXME: how do we return errors here?
                return false;
            }

            # success, select our db
            if (! mysql_select_db($_MYSQL_DB, $dbh)) {
                # again FIXME on returning errors
                return false;
            }

            # final success is assured
            $this->dbh = $dbh;
            return $dbh;
        }

        function _do_and_get_result($query, $array) {
            # get a db handle
            $dbh = $this->_dbh();
            if (! $dbh) {
                # FIXME error
                return false;
            }

            # try to run the query
            $query = $this->_prepare_query($query, $array);
            $result = mysql_query($query, $dbh);
#            echo("[[err = " . mysql_error($dbh) . "]]<br />\n");
            if (! $result || mysql_errno($dbh) > 0) {
                # FIXME die with mysql_error
#                echo("[[error detected returning false]]<br />\n");
                return false;
            }

            return $result;
        }

        # get a column from a table
        function _select_column($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            $data = array();
            if (! $result) {
                return $data;
            }
            while ($row = mysql_fetch_row($result)) {
                array_push($data, $row[0]);
            }
            return $data;
        }

        # get a column from a table
        function _select_one($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return null;
            }
            $row = mysql_fetch_row($result);
            if (count($row) >= 1) {
                return $row[0];
            }
            return null;
        }

        # return a row as an object
        function _select_row_as_assoc($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return null;
            }
            $obj = mysql_fetch_assoc($result);
            return $obj;
        }

        # return a row as an object
        function _select_row_as_object($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return null;
            }
            $obj = mysql_fetch_object($result);
            return $obj;
        }

        function _select_rows_as_objects($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return null;
            }
            $data = array();
            while ($row = mysql_fetch_object($result)) {
                array_push($data, $row);
            }
            return $data;
        }

        function _select_rows_as_array($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return null;
            }
            $data = array();
            while ($row = mysql_fetch_row($result)) {
                array_push($data, $row);
            }
            return $data;
        }

        # just do something and return success or not
        function _do_query($query, $array = array()) {
            $result = $this->_do_and_get_result($query, $array);
            if (! $result) {
                return false;
            }
            return true;
        }
    }

?>
