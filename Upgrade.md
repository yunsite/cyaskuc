#这是针对Cyask3.0.3 for dz 的升级说明.

# 简介 #

此文档介绍如何在Cyask 3.0.3 for dz 通过手动升级成为Cyask 3.0 for UCenter1.0.
注意： 升级前请备份所有数据，包括CYASK和BBS数据。


# 正文 #

**升级前注意事项：**
  1. 确认您的论坛BBS已经升级到DZ6.1.0及其以上版本
  1. 确保您的所有数据，包括UC，BBS，CYASK3.0.3的网页和数据库数据都已经备份
  1. 确保您已经下载了Cyask For UCenter ,建议使用SVN，打包下载的可能含已知BUG。
  1. 通过升级，之前的积分数据可能暂时无法导入到用户的积分数据中

**升级步骤**
  1. 备份好您的数据
  1. 使用phpmyadmin 将以下数据表导入到数据库中
```
CREATE TABLE  `cyask_members` (  `uid` mediumint(8) unsigned NOT NULL auto_increment,  `username` varchar(18) NOT NULL,  `password` varchar(32) NOT NULL,  `email` varchar(40) NOT NULL,  `publicmail` tinyint(1) unsigned NOT NULL default '0',  `adminid` tinyint(3) unsigned NOT NULL default '0',  `groupid` tinyint(3) NOT NULL default '0',  `scores` int(10) NOT NULL default '0',  `gender` tinyint(1) unsigned NOT NULL default '0',  `regdate` int(10) unsigned NOT NULL default '0',  `signature` mediumtext NOT NULL,  `qq` varchar(12) NOT NULL default '',  `msn` varchar(35) NOT NULL default '',  `bday` date NOT NULL default '0000-00-00',  `receivemail` tinyint(1) unsigned NOT NULL default '0',  PRIMARY KEY  (`uid`),  KEY `username` (`username`),  KEY `adminid` (`adminid`),  KEY `scores` (`scores`) ) TYPE=MyISAM; 
```
  1. 上传 除askdata，attachments,install目录及install.php文件外其他所有文件并覆盖服务器上原有文件
  1. 请在UC中添加本应用，并按照以下格式修改config.inc.php
```
<?php

$cookiedomain = '';
$cookiepath = '/';

$headercache = 0; 
$tplrefresh = 1;
$htmlfresh =0;
$headercharset = 1;	
$onlinehold = 300;

$attachdir = './attachments';
$attachurl = 'attachments';	

$database = 'mysql';
$dbreport = 0;
$errorreport = 1;
$attackevasive = 0;
$charset = 'utf-8'; // utf-8 or gbk
$dbcharset = 'utf8'; //utf8 or gbk
define('UC_CONNECT', ''); // mysql or leave blank
define('UC_DBHOST', 'localhost'); //your uc db server 
define('UC_DBUSER', 'root'); //your uc db username
define('UC_DBPW', ''); // your uc db password
define('UC_DBNAME', 'muc'); //your uc db name
define('UC_DBCHARSET', 'utf8'); //your uc db charset
define('UC_DBTABLEPRE', '`muc`.uc_'); // your uc db prefix , pls follow the eg.
define('UC_DBCONNECT', '0');
define('UC_KEY', ''); // the communicate key between UC & your application
define('UC_API', ''); // the uc pic url ,like http://ucserver/path
define('UC_CHARSET', ''); //the charset of your uc server 
define('UC_IP', ''); // uc server's ip or leave blank
define('UC_APPID', ''); // this application id in uc 
define('UC_PPP', '20');
$dbhost = 'localhost'; // cyask db server 
$dbuser = 'root'; //cyask db username
$dbpw = ''; //cyask db password
$dbprefix = 'cyask_';  // cyask db table prefix
$adminemail = ''; // cyask admin email
$dbname = ''; // cyask db name
```

# 其他说明 #

  * 本升级说明可能会导致某些不可预见的问题，请慎重选择。
  * 请尽量能在本地测试通过后再选择使用本升级
  * 升级后，用户之前的积分可能不可用
  * 对以上文档及其他未尽事宜给您带来的后果，本人不负任何责任。