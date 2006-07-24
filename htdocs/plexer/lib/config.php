<?php

    ##########################################################################
    # CONFIGURE HERE, TOUCH THESE VALUES IF YOU WANT #########################
    ##########################################################################

    # this is where Smarty was installed, this is the default recommended by the
    # manual so it should probably be good
    $_INC_PATH = "/usr/local/lib/php/Smarty";

    # this is the path you put all of the Plexer files in
    $_APP_PATH = "/web/eve-dev/public_html/plexer";

    # the address that this site is located at, WITHOUT a trailing slash!
    $_WEB_URL = "http://www.eve-dev.com/plexer";

    # set to what path the cookie should be secured against, in most cases this is
    # going to be the path to the application or /.  the domain is going to be the
    # domain where this application is running.
    $_COOKIE_PATH = '/plexer';
    $_COOKIE_DOMAIN = 'www.eve-dev.com';

    # this information is used to connect to a MySQL database
    $_MYSQL_HOST = "db";
    $_MYSQL_USER = "dionforum";
    $_MYSQL_PASS = "kl3f9dFJ2cv";
    $_MYSQL_DB = "dionforum";

    # OPTIONAL: set to something like "foo_" if you want to run multiple kill
    # sites in one physical database
    $_TABLE_PREFIX = "plexer_";

    ##########################################################################
    # END OF CONFIG -- DO NOT GO BELOW THIS LINE!! ###########################
    ##########################################################################

?>
