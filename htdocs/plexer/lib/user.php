<?php

    class User {
        var $id = null;
        var $obj = null;
        
        function User($userid = null) {
            if (is_null($userid)) {
                return;
            }
    
            global $ft;
            if (is_numeric($userid)) {
                # load by userid
                $this->id = $userid;
                $this->obj = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:pilots WHERE pilotid = ?', array($userid));
                if (! $this->obj) {
                    $this->id = null;
                }
            } else {
                # load by username
                $this->obj = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:pilots WHERE name = ?', array($userid));
                if ($this->obj) {
                    $this->id = $this->obj->pilotid;
                }
            }
        }

        // if our standings are +10 (configurable?)
        function trustable() {
            if ($this->obj) {
                return 1;
            }
            return 0;
        }

        function userid() {
            if ($this->id) {
                return $this->id;
            }
            return 0;
        }

        function charid() {
            if ($this->obj && $this->obj->charid) {
                return $this->obj->charid;
            }
            return 0;
        }

        function corpid() {
            if ($this->obj) {
                return $this->obj->corpid;
            }
            return 0;
        }

        function name() {
            if ($this->obj) {
                return $this->obj->name;
            }
            return false;
        }

        function password($newpass = null) {
            if ($newpass) {
                global $ft;
                $ft->dbh->_do_query("UPDATE tbl:pilots SET password = ? WHERE pilotid = ?",
                                    array($newpass, $this->obj->pilotid));
                return true;
            }
            if ($this->obj) {
                return $this->obj->password;
            }
            return false;
        }

        function admin() {
            if ($this->obj && ($this->obj->roles & 1)) {
                return true;
            }
            return false;
        }

        function ceo($corpid = null) {
            if ($this->manager()) {
                return true;
            }
            if ($this->obj && ($this->obj->roles & 2)) {
                if (! is_null($corpid)) {
                    # we got a corp
                    if ($corpid == $this->corpid()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }
            return false;
        }

        function director($corpid = null) {
            if ($this->ceo()) {
                return true;
            }
            if ($this->obj && ($this->obj->roles & 4)) {
                if (! is_null($corpid)) {
                    # we got a corp
                    if ($corpid == $this->corpid()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }
            return false;
        }

        function manager() {
            if ($this->admin()) {
                return true;
            }
            if ($this->obj && ($this->obj->roles & 8)) {
                return true;
            }
            return false;
        }
    }

?>
