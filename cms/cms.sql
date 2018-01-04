CREATE TABLE config (
  var varchar(100) NOT NULL default '',
  val text,
  PRIMARY KEY  (var)
);
INSERT INTO config VALUES ('contact_mail','nomail@mail.local');
INSERT INTO config VALUES ('site_description','demo site');
INSERT INTO config VALUES ('site_keywords','demo site');
INSERT INTO config VALUES ('site_name','demo');
INSERT INTO config VALUES ('site_title','demo site');


CREATE TABLE pages (
  id int(10) NOT NULL ,
  pid int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  alias varchar(100) NOT NULL default '',
  text mediumtext NOT NULL,
  date int(10) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  keywords varchar(255) NOT NULL default '',
  hidden tinyint(3) NOT NULL default '0',
  so int(11) default '1',
  PRIMARY KEY  (id)
);
INSERT INTO pages VALUES (1,0,'Index page','Index page','index','<p>text on index page</p>',1206815569,'','',1,1);
INSERT INTO pages VALUES (2,0,'About','about','about','<p>about</p>',1231099972,'about','about',0,3);
INSERT INTO pages VALUES (3,0,'Contact','Contact','contact','<p>Contact</p>',1231101632,'Contact','Contact',0,4);
INSERT INTO pages VALUES (4,0,'Gallery','Gallery','gallery','<p>Gallery</p>',1231103029,'Gallery','Gallery',0,2);
INSERT INTO pages VALUES (5,0,'rightblock','Right block','rightblock','<p><strong>bold</strong> text in right block <a href=\"#\">More...</a></p>',1231103271,'rightblock','rightblock',1,5);
INSERT INTO pages VALUES (6,1,'sub index','','p4961274ec65d8','',1231103822,'','',0,7);
INSERT INTO pages VALUES (7,1,'sub index 2','','p496127584c4b8','',1231103832,'','',0,6);
INSERT INTO pages VALUES (8,4,'sub page','','p496128cda037e','',1231104205,'','',0,8);


CREATE TABLE users (
  id int(10)  NOT NULL ,
  login varchar(45) NOT NULL default '',
  password char(32) NOT NULL default '',
  mail varchar(100) NOT NULL default '',
  type tinyint(3)  NOT NULL default '0',
  code char(32) NOT NULL default '',
  created_at int(11)  NOT NULL default '0',
  last_login int(11)  NOT NULL default '0',
  PRIMARY KEY  (id)
);
INSERT INTO users VALUES (1,'root','e10adc3949ba59abbe56e057f20f883e','yagrys.com',2,'',0,1231103015);
