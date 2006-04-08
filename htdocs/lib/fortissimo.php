<?php

    # include needful things
    include("config.php"); # better be in the same path as us...!

    # extra things we need to run
    include("db.php");
    include("user.php");
    include("eveheader.php");
    include("session.php");
    include("parser.php");
    include("universe.php");

    # now bring in Smarty library
    include("$_INC_PATH/Smarty.class.php");

    # setup a custom smarty object
    class Fortissimo extends Smarty {
        var $dbh = null;
        var $eve = null;

        function Fortissimo() {
            global $_APP_PATH, $_INC_PATH, $_WEB_URL;

            # basically we are a Smarty
            $this->Smarty();
            $this->debugging = false; # FIXME DISABLE THIS XXX FIXME TODO LOLLERSKATES FIXME

            # setup paths
            $this->template_dir = "$_APP_PATH/_ft_templates/";
            $this->compile_dir  = "$_APP_PATH/_ft_templates_c/";
            $this->config_dir   = "$_APP_PATH/_ft_configs/";
            $this->cache_dir    = "$_APP_PATH/_ft_cache/";

            # other options
            $this->caching = false; # TODO: TRUE eventually?

            # parses out EVE IGB information
            $this->eve = new EVEHeader;

            # jump out early if need be
            if ($this->eve->usingIGB() && ! strstr($_SERVER['SCRIPT_NAME'], '/igb')) {
                # they aren't on the igb page and they are in the igb, redirect them
                $this->redirect($_WEB_URL . "/igb.php");
                exit;
            }

            # get a database
            $this->dbh = new DBH();

            # set some default options
            $this->title("(untitled page)");
            $this->assign('SITEROOT', $_WEB_URL);
        }

        # custom page display with header/footer templates
        function makepage($resource_name, $cache_id = null, $compile_id = null) {
            $this->assign('links', get_sorted_links());
            $this->display("header.tpl");
            $this->display($resource_name . ".tpl", $cache_id, $compile_id);
            $this->display("footer.tpl");
            return;
        }

        # just assign an error message
        function error($errmsg = null) {
            if (is_null($errmsg)) {
                $errmsg = "Sorry, we ran into an error we couldn't identify.  Please try your action again.";
            }
            $this->assign('error', $errmsg);
            return;
        }

        # display a generic error page
        function errorpage($errmsg = null, $page = null) {
            if (is_null($errmsg)) {
                $errmsg = "Sorry, an unknown error occurred.";
            }
            if (is_null($page)) {
                $page = 'error';
            }
            $this->error($errmsg);
            $this->assign('error', $errmsg);
            $this->makepage($page);
            return;
        }
        function igberrorpage($errmsg = null) {
            if (is_null($errmsg)) {
                $errmsg = "Sorry, an unknown error occurred.";
            }
            echo("<html><body>$errmsg</body></html>");
            return;
        }

        # set the page title
        function message($message = null) {
            if (is_null($message)) {
                $message = "The server had a message for you, but didn't specify what it was.";
            }
            $this->assign('message', $message);
        }

        # set the page title
        function title($title) {
            $this->assign('title', "Fortissimo - $title");
        }

        # sets up a redirect
        function redirect($url) {
            header("Location: $url");
            return true;
        }

        function setup_session() {
            # see if the user is logged in
            $this->session = new Session();
            $this->assign('remote', $this->session->user);
            if ($this->session->user) {
                $this->assign('admin', $this->session->user->admin());
            } else {
                $this->assign('admin', false);
            }
        }

        function config($key) {
            $val = $this->dbh->_select_one('SELECT cval FROM tbl:config WHERE ckey = ?', array($key));
            return $val;
        }

        function set_config($key, $val) {
            $this->dbh->_do_query("REPLACE INTO tbl:config (ckey, cval) VALUES (?, ?)",
                                  array($key, $val));
        }                                      

    }

    # functions we use
    function left_box_start($params, &$ft) {
        global $_WEB_URL;
        $title = $params["title"];
        $short = $params["name"];
        return '<table align="center" border="0" cellpadding="6" cellspacing="1" class="tborder" width="100%">' .
               '<thead><tr><td class="tcatbl smallfont" style="font-weight: bold;">' .
#               '<a style="float: right" href="#" onclick="return toggle_collapse(\'forumhome_' . $short . '\')">' .
#               '<img id="collapseimg_forumhome_' . $short . '" src="' . $_WEB_URL . '/img/collapse_thead.gif" ' .
#               'width="13" height="13" border="0" alt="Collapse/Expand" /></a>' .
               '&raquo;&nbsp; ' . $title .
               '</td></tr></thead><tfoot style="display: none;"><tr><td></td></tr></tfoot><tbody ' .
               'id="collapseobj_forumhome_' . $short . '"><tr style=""><td class="alt2">' .
               '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    }
    function left_box_end($params, &$ft) {
        return "</table></td></tr></tbody></table><br />";
    }
    function left_box_link($params, &$ft) {
        $url = $params["url"];
        if (substr($url, 0, 1) == '/') {
            global $_WEB_URL;
            $url = $_WEB_URL . $url;
        }
        $text = $params["text"];
        $extra = $params["extra"];
        return '<tr><td height="17" valign="bottom" class="row1">&nbsp;&raquo;&nbsp; ' .
               '<a href="' . $url . '">' . $text . '</a> ' . $extra . '</td>';
    }
    function commify($str) {
        $n = strlen($str); 
        if ($n <= 3) { 
            $return = $str;
        } else { 
            $pre = substr($str,0,$n-3); 
            $post = substr($str,$n-3,3); 
            $pre = commify($pre); 
            $return = "$pre,$post"; 
        }
        return $return; 
    }
    function standings($str) {
        $num = $str+0;
        $num = sprintf("%0.1f", $num/10);
        return $num;
    }
    function security($str) {
        $num = $str+0;
        if ($num < 0) {
            return "0.0";
        } elseif ($num >= 1000000) {
            return "1.0";
        } else {
            return sprintf("%0.1f", $num / 1000000);
        }
    }
    function security_color($str) {
        $str = security($str);
        $arr = array(
            '0.0' => '#ff0000',
            '0.1' => '#ff2200',
            '0.2' => '#ff4400',
            '0.3' => '#ff6600',
            '0.4' => '#ff8800',
            '0.5' => '#ffaa00',
            '0.6' => '#ffcc00',
            '0.7' => '#ffff00',
            '0.8' => '#aaff00',
            '0.9' => '#44ff00',
            '1.0' => '#00ff00',
        );
        return $arr[$str];
    }
    function maxlen20($str) {
        if (strlen($str) > 20) {
            $str = substr($str, 0, 17);
            $str .= '...';
        }
        return $str;
    }
    function isk($str) {
        $str = $str+0;
        if (preg_match("/^(\d+)\.(\d+)$/", $str, $matches)) {
            return commify($matches[1]) . '.' . $matches[2] . ' ISK';
        }
        return commify($str+0) . " ISK";
    }
    function minsecs($str) {
        return date("H:i", $str);
    }
    function ymd($str) {
        return date("Y-m-d", $str);
    }
    function this_week() {
        $week = date("W", time());
        $year = date("Y", time());
        return $week + ($year * 100);
    }
    function this_month() {
        $month = date("m", time());
        $year = date("Y", time());
        return $month + ($year * 100);
    }
    function this_year() {
        return date("Y", time());
    }
    function how_long_ago($str) {
        $n = time() - ($str+0);
        
        if ($n < 60) {
            return $n . " secs";
        } elseif ($n < 60*60) {
            $val = (int)($n / 60);
            return $val . " mins";
        } elseif ($n < 24*60*60) {
            $val = (int)($n / (60*60));
            return $val . " hours";
        } elseif ($n < 7*24*60*60) {
            $val = (int)($n / (24*60*60));
            return $val . " days";
        } elseif ($n < 365*24*60*60) {
            $val = (int)($n / (7*24*60*60));
            return $val . " weeks";
        } else {
            return "ages";
        }
    }
    function hide_location($k) {
        if (is_null($k)) {
            return 0;
        }
        $since = time() - $k->killtime;
        if ($since < 0) {
            $since = 1;
        }
        $warmode = get_corp_warmode($k->killer->corp_id);
        if ($warmode > 0) {
            if ($since > ($warmode*60*60)) {
                return 0;
            } else {
                return 1;
            }
        }
        $warmode = get_corp_warmode($k->victim->corp_id);
        if ($warmode > 0) {
            if ($since > ($warmode*60*60)) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
        return 1;
    }
    function cisort($a, $b) {
        $la = strtolower($a);
        $lb = strtolower($b);
        if ($la == $lb) {
            return 0;
        }
        return ($la > $lb) ? 1 : -1;
    }

    # now we create a basic object for them to use
    global $ft;
    $ft = new Fortissimo;
    $ft->setup_session();

    # now give them a global remote object
    global $remote;
    $remote = $ft->session->user;

    # and now define some functions we're going to need later, perhaps
    $ft->register_function("left_box_start", "left_box_start");
    $ft->register_function("left_box_end", "left_box_end");
    $ft->register_function("left_box_link", "left_box_link");
    $ft->register_modifier("commify", "commify");
    $ft->register_modifier("isk", "isk");
    $ft->register_modifier("standings", "standings");
    $ft->register_modifier("security", "security");
    $ft->register_modifier("security_color", "security_color");
    $ft->register_modifier("minsecs", "minsecs");
    $ft->register_modifier("how_long_ago", "how_long_ago");
    $ft->register_modifier("maxlen20", "maxlen20");

?>
