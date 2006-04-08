<?php

    include 'http.php';

    function setup_db() {
        # needful things we need
        global $ft, $_TABLE_PREFIX;

        # now, setup the tables we use
        $tbls = array(
##############################################################################
            'sitelinks' => '
create table if not exists tbl:sitelinks (
  linkid int unsigned not null auto_increment,
  name varchar(100),
  url varchar(255),
  sort int unsigned not null default 50,
  primary key (linkid)
)
            ',

##############################################################################
            'pilots' => '
create table if not exists tbl:pilots (
  pilotid int unsigned not null auto_increment,
  name varchar(255) not null,
  corpid int unsigned,
  charid int unsigned,
  password varchar(60),
  roles int unsigned not null default 0,
  primary key (pilotid),
  unique (name)
)
            ',

##############################################################################
            'corps' => "
create table if not exists tbl:corps (
  corpid int unsigned not null auto_increment,
  name varchar(255) not null,
  ticker varchar(10),
  standings int,
  allianceid int unsigned,
  allowed int unsigned,
  warmode int unsigned not null default '0',
  note text,
  primary key (corpid),
  unique (name)
)
            ",

##############################################################################
            'alliances' => '
create table if not exists tbl:alliances (
  allianceid int unsigned not null auto_increment,
  name varchar(255) not null,
  standings int,
  note text,
  primary key (allianceid),
  unique (name)
)
            ',

##############################################################################
            'rawmail' => '

create table if not exists tbl:rawmail (
  mailid int unsigned not null auto_increment,
  pilotid int unsigned not null,
  expunged int unsigned not null default 0,
  submittime int unsigned not null,
  submitip varchar(15) not null,
  mail text not null,

  primary key (mailid),
  key (pilotid)
)
            ',

##############################################################################
            'sessions' => '

create table if not exists tbl:sessions (
  sessid int unsigned not null auto_increment,
  sesskey char(64) not null,
  userid int unsigned not null,
  issueip varchar(15) not null,
  issuetime int unsigned not null,
  exptime int unsigned not null,

  primary key (sessid),
  key (userid, sesskey)
)
            ',

##############################################################################
            'systems' => '

create table if not exists tbl:systems (
  systemid int unsigned not null auto_increment,
  regionid int unsigned,
  constellationid int unsigned,
  name varchar(60) not null,
  security int,
  primary key (systemid),
  unique (name)
)
            ',

##############################################################################
            'constellations' => '

create table if not exists tbl:constellations (
  constellationid int(10) unsigned not null,
  name varchar(100) not null,
  primary key (constellationid)
)
            ',

##############################################################################
            'regions' => '

create table if not exists tbl:regions (
  regionid int(10) unsigned not null,
  name varchar(100) not null,
  primary key (regionid)
)
            ',

##############################################################################
            'dupeids' => '

create table if not exists tbl:dupeids (
  dupeid int unsigned not null auto_increment,
  victimid int unsigned not null,
  v_shipid int unsigned not null,
  killerid int unsigned not null,
  systemid int unsigned not null,
  killtime int unsigned not null,
  primary key (dupeid),
  unique (victimid, v_shipid, killerid, systemid, killtime),
)

            ',

##############################################################################
            'summary' => "
create table if not exists tbl:summary (
  killid int unsigned not null auto_increment,
  mailid int unsigned not null,

  v_pilotid int unsigned not null,
  v_corpid int unsigned not null,
  v_allianceid int unsigned,
  v_shipid int unsigned not null,
  v_groupid int unsigned not null,

  k_pilotid int unsigned not null,
  k_corpid int unsigned not null,
  k_allianceid int unsigned,
  k_security int not null,
  k_shipid int unsigned not null,
  k_groupid int unsigned not null,

  systemid int unsigned not null,
  regionid int unsigned not null,
  constellationid int unsigned not null,
  system_security int not null,

  killtime int unsigned not null,
  type enum('kill', 'loss', 'murder', 'other') not null,
  weaponid int unsigned,

  primary key (killid),
  key (killtime),
  key (v_pilotid),
  key (v_corpid),
  key (v_allianceid),
  key (v_shipid),
  key (v_groupid),
  key (k_pilotid),
  key (k_corpid),
  key (k_allianceid),
  key (k_shipid),
  key (k_groupid),
  key (systemid),
  key (regionid),
  key (constellationid),
  key (weaponid),
  key (mailid),
  key (type, killtime)
)
            ",

##############################################################################
            'killers' => '

create table if not exists tbl:killers (
  killid int unsigned not null,
  pilotid int unsigned not null,
  corpid int unsigned not null,
  allianceid int unsigned,
  shipid int unsigned not null,
  groupid int unsigned not null,
  finalblow int unsigned not null,
  weaponid int unsigned,
  security int not null,

  primary key (killid, pilotid),
  key (pilotid),
  key (corpid),
  key (allianceid),
  key (shipid),
  key (groupid),
  key (weaponid)
)
            ',

##############################################################################
            'stats' => "

create table if not exists tbl:stats (
  type enum('pilot', 'corp', 'alliance', 'system', 'weapon', 'ship', 'group', 'region', 'constellation') not null,
  var1 int unsigned not null,

  dtype enum('week', 'month', 'year', 'ever') not null,
  var2 int unsigned not null,

  killgive int unsigned not null default 0,
  killrecv int unsigned not null default 0,
  lossgive int unsigned not null default 0,
  lossrecv int unsigned not null default 0,
  murdergive int unsigned not null default 0,
  murderrecv int unsigned not null default 0,

  finalblows int unsigned not null default 0,
  bountypoints bigint unsigned not null default 0,
  iskdestroyed bigint unsigned not null default 0,
  isklost bigint unsigned not null default 0,
 
  primary key (type, var1, dtype, var2),
  key (dtype, var2)
)
            ",

##############################################################################
            'favorites' => "

create table if not exists tbl:favorites (
  type enum('pilot', 'corp', 'alliance') not null,
  var1 int unsigned not null,

  ftype enum('pilot', 'system', 'weapon', 'ship_lost', 'ship_killed', 'ship_flown') not null,
  var2 int unsigned not null,

  var3 int unsigned not null,

  primary key (type, var1, ftype, var2)
)
            ",

##############################################################################
            'itemtypes' => '

create table if not exists tbl:itemtypes (
  typeid int(10) unsigned not null,
  groupid int(11),
  name varchar(100),
  seen int unsigned not null default 0,
  bountypoints bigint unsigned,
  killpoints bigint unsigned,
  baseprice int(11),
  icon varchar(10),

  primary key  (typeid),
  key (name)
)
            ',

##############################################################################
            'itemgroups' => '

create table if not exists tbl:itemgroups (
  groupid int(10) unsigned not null,
  categoryid int(11),
  name varchar(100),
  primary key (groupid)
)
            ',

##############################################################################
            'killitems' => "

create table if not exists tbl:killitems (
  killid int unsigned not null,
  itemid int unsigned not null,
  quantity int unsigned not null,
  loc enum('fitted', 'cargo', 'dronebay'),

  key (killid),
  key (itemid)
)
            ",

##############################################################################
            'killcache' => '

create table if not exists tbl:killcache (
  killid int unsigned not null,
  cache mediumblob,
  primary key (killid)
)
            ',

##############################################################################
            'news' => '

create table if not exists tbl:news (
  newsid int unsigned not null auto_increment,
  authorid int unsigned not null,
  display tinyint unsigned not null default 0,
  
  content text not null,

  primary key (newsid)
)
            ',

##############################################################################
            'config' => '

create table if not exists tbl:config (
  ckey varchar(255) not null,
  cval varchar(255),
  primary key (ckey)
)
            ',

##############################################################################
        );

        # create these tables
        foreach ($tbls as $name => $decl) {
            $ft->dbh->_do_query($decl);
        }
    }

    # get data to populate our database
    function populate_db() {
        global $ft, $_WEB_URL;

        # put in links
        $ft->dbh->_do_query('INSERT INTO tbl:sitelinks (linkid, name, url, sort) VALUES (NULL, ?, ?, ?)',
                            array('Home', "$_WEB_URL/", 50));

        # now fetch what we need for 'regions'
        $fetch = array(
            'regions' => array('regionid', 'name'),
            'constellations' => array('constellationid', 'name'),
            'systems' => array('systemid', 'regionid', 'constellationid', 'name', 'security'),
            'itemtypes' => array('typeid', 'groupid', 'name', 'baseprice', 'icon'),
            'itemgroups' => array('groupid', 'categoryid', 'name')
        );

        # now get the data
        foreach ($fetch as $tbl => $cols) {
            $r = new HTTPRequest("http://www.eve-dev.com/setup_dumper.php?table=$tbl");
            $str = $r->DownloadToString();
            $rows = explode("\n", $str);
            $colstr = implode(',', $cols);

            # now break it on newlines
            foreach ($rows as $row) {
                $row = trim($row);
                if ($row{0} == "#") {
                    continue;
                }

                # make some SQL
                $ft->dbh->_do_query("INSERT INTO tbl:$tbl ($colstr) VALUES ($row)");
            }
        }
    }

?>
