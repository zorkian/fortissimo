<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->admin()) {
        return $ft->errorpage('You must be an administrator to use this page.');
    }

    # and now handle changes
    foreach (get_sorted_links() as $link) {
        $url = trim($_POST['url_' . $link->linkid]);
        $name = trim($_POST['name_' . $link->linkid]);
        $sort = $_POST['sort_' . $link->linkid]+0;
        if ($url && $name && $sort) {
            $ft->dbh->_do_query("UPDATE tbl:sitelinks SET sort = ?, name = ?, url = ? WHERE linkid = ?",
                                array($sort, $name, $url, $link->linkid));
        } else {
            $ft->dbh->_do_query("DELETE FROM tbl:sitelinks WHERE linkid = ?", array($link->linkid));
        }
    }

    # see if they're creating a new one
    $url = trim($_POST['url_new']);
    $name = trim($_POST['name_new']);
    if ($url && $name) {
        $ft->dbh->_do_query("INSERT INTO tbl:sitelinks (sort, name, url) VALUES (?, ?, ?)",
                            array($_POST['sort_new']+0, $name, $url));
    }

    $ft->assign('message', 'Changes have been saved.');
    $ft->title('Administrate Links');
    $ft->makepage('admin_links');

?>
