DROP TABLE IF EXISTS cyask_answer;
CREATE TABLE cyask_answer (
  aid int(10) unsigned NOT NULL auto_increment,
  qid int(10) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL,
  username varchar(20) NOT NULL,
  answer mediumtext NOT NULL,
  joinvote tinyint(1) unsigned NOT NULL default '0',
  votevalue smallint(5) unsigned NOT NULL default '0',
  answertime int(10) unsigned NOT NULL default '0',
  adopttime int(10) unsigned NOT NULL default '0',
  response smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (aid),
  KEY qid (qid),
  KEY uid (uid),
  KEY adopttime (adopttime)
) TYPE=MyISAM;


DROP TABLE IF EXISTS cyask_collect;
CREATE TABLE cyask_collect (
  id int(10) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL,
  username varchar(20) NOT NULL,
  sortname char(20) NOT NULL,
  ctype tinyint(1) unsigned NOT NULL,
  title varchar(100) NOT NULL,
  content mediumtext NOT NULL,
  url mediumtext NOT NULL,
  public tinyint(1) unsigned NOT NULL default '1',
  collecttime int(10) unsigned default '0',
  edittime int(10) unsigned NOT NULL default '0',
  click int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY uid (uid),
  KEY ctype (ctype)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cyask_members;
CREATE TABLE  `cyask_members` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `username` varchar(18) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  `publicmail` tinyint(1) unsigned NOT NULL default '0',
  `adminid` tinyint(3) unsigned NOT NULL default '0',
  `groupid` tinyint(3) NOT NULL default '0',
  `scores` int(10) NOT NULL default '0',
  `gender` tinyint(1) unsigned NOT NULL default '0',
  `regdate` int(10) unsigned NOT NULL default '0',
  `signature` mediumtext NOT NULL,
  `qq` varchar(12) NOT NULL default '',
  `msn` varchar(35) NOT NULL default '',
  `bday` date NOT NULL default '0000-00-00',
  `receivemail` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `username` (`username`),
  KEY `adminid` (`adminid`),
  KEY `scores` (`scores`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cyask_notice;
CREATE TABLE cyask_notice (
  id smallint(5) unsigned NOT NULL auto_increment,
  author char(18) NOT NULL,
  title varchar(100) NOT NULL,
  content text NOT NULL,
  time int(10) unsigned NOT NULL default '0',
  orderid tinyint(3) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;


DROP TABLE IF EXISTS cyask_ques;
CREATE TABLE cyask_ques (
  qid int(10) unsigned NOT NULL auto_increment,
  sid1 smallint(5) unsigned NOT NULL default '0',
  sid2 smallint(5) unsigned NOT NULL default '0',
  sid3 smallint(5) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL,
  username varchar(20) NOT NULL,
  score tinyint(3) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL,
  content mediumtext NOT NULL,
  asktime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  status tinyint(1) unsigned NOT NULL default '1',
  hidanswer tinyint(1) unsigned NOT NULL default '0',
  introtime int(10) unsigned NOT NULL default '0',
  answercount int(10) unsigned NOT NULL default '0',
  clickcount int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (qid),
  KEY sid1 (sid1),
  KEY sid2 (sid2),
  KEY sid3 (sid3),
  KEY uid (uid),
  KEY status (status)
) TYPE=MyISAM;


DROP TABLE IF EXISTS cyask_res;
CREATE TABLE cyask_res (
  id int(10) unsigned NOT NULL auto_increment,
  aid int(10) unsigned NOT NULL default '0',
  qid int(10) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL,
  username varchar(20) NOT NULL,
  uip varchar(15) NOT NULL,
  content mediumtext NOT NULL,
  time int(10) unsigned NOT NULL default '0',
  days int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (id),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cyask_score;
CREATE TABLE cyask_score (
  uid int(10) unsigned NOT NULL,
  stype tinyint(1) unsigned NOT NULL,
  score int(10) unsigned NOT NULL default '0',
  online int(10) unsigned NOT NULL default '0',
  KEY uid (uid),
  KEY stype (stype)
) TYPE=MyISAM;


DROP TABLE IF EXISTS cyask_set;
CREATE TABLE cyask_set (
  variable varchar(32) NOT NULL default '',
  value mediumtext NOT NULL,
  number tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (variable)
) TYPE=MyISAM;

INSERT INTO cyask_set VALUES ('count_show_sort1', '10', 0);
INSERT INTO cyask_set VALUES ('site_name', 'redflood.cn', 1);
INSERT INTO cyask_set VALUES ('site_url', 'http://cyask3.redflood.cn', 1);
INSERT INTO cyask_set VALUES ('count_show_sort2', '5', 0);
INSERT INTO cyask_set VALUES ('count_show_intro', '5', 0);
INSERT INTO cyask_set VALUES ('count_show_nosolve', '8', 0);
INSERT INTO cyask_set VALUES ('count_show_solve', '8', 0);
INSERT INTO cyask_set VALUES ('count_show_note', '5', 0);
INSERT INTO cyask_set VALUES ('score_answer', '2', 0);
INSERT INTO cyask_set VALUES ('score_adopt', '5', 0);

-- --------------------------------------------------------

DROP TABLE IF EXISTS cyask_sort;
CREATE TABLE cyask_sort (
  sid smallint(5) unsigned NOT NULL auto_increment,
  sid1 smallint(5) unsigned NOT NULL default '0',
  sid2 smallint(5) unsigned NOT NULL default '0',
  sort1 char(30) NOT NULL,
  sort2 char(30) NOT NULL,
  sort3 char(30) NOT NULL,
  grade tinyint(1) unsigned NOT NULL default '0',
  orderid tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (sid),
  KEY grade (grade)
) TYPE=MyISAM;

INSERT INTO cyask_sort VALUES (1,0,0,'DefaultSort','','',1,1);

DROP TABLE IF EXISTS cyask_tpl;
CREATE TABLE cyask_tpl (
  templateid smallint(6) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  tpldir varchar(50) NOT NULL,
  styledir varchar(50) NOT NULL,
  copyright varchar(100) NOT NULL default '',
  PRIMARY KEY  (templateid)
) TYPE=MyISAM;

INSERT INTO cyask_tpl VALUES (1, 'default', 'templates/default', 'images/default', 'www.cyask.com');

DROP TABLE IF EXISTS cyask_vote;
CREATE TABLE cyask_vote (
  qid int(10) unsigned NOT NULL default '0',
  aid int(10) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL,
  uip varchar(15) NOT NULL,
  KEY qid (qid)
) TYPE=MyISAM;