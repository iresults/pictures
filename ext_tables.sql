#
# Table structure for table 'tx_pictures_domain_model_picture'
#
CREATE TABLE tx_pictures_domain_model_picture (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	headline varchar(255) DEFAULT '' NOT NULL,
	caption text,
	byline varchar(255) DEFAULT '' NOT NULL,
	copyright_string varchar(255) DEFAULT '' NOT NULL,
	file_uid int(11) DEFAULT '0' NOT NULL,
	file_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_pictures_domain_model_album'
#
CREATE TABLE tx_pictures_domain_model_album(

                                               uid              int(11)                          NOT NULL auto_increment,
                                               pid              int(11)              DEFAULT '0' NOT NULL,

                                               title            varchar(255)         DEFAULT ''  NOT NULL,
                                               storage          int(11)              DEFAULT '0' NOT NULL,
                                               folder           text,
                                               poster           int(11) unsigned                 NOT NULL default '0',
                                               description      text,
                                               pictures         int(11) unsigned     DEFAULT '0' NOT NULL,

                                               tstamp           int(11) unsigned     DEFAULT '0' NOT NULL,
                                               crdate           int(11) unsigned     DEFAULT '0' NOT NULL,
                                               cruser_id        int(11) unsigned     DEFAULT '0' NOT NULL,
                                               deleted          smallint(5) unsigned DEFAULT '0' NOT NULL,
                                               hidden           smallint(5) unsigned DEFAULT '0' NOT NULL,
                                               starttime        int(11) unsigned     DEFAULT '0' NOT NULL,
                                               endtime          int(11) unsigned     DEFAULT '0' NOT NULL,

                                               t3ver_oid        int(11)              DEFAULT '0' NOT NULL,
                                               t3ver_id         int(11) DEFAULT '0' NOT NULL,
                                               t3ver_wsid       int(11) DEFAULT '0' NOT NULL,
                                               t3ver_label      varchar(255) DEFAULT '' NOT NULL,
                                               t3ver_state      smallint(6) DEFAULT '0' NOT NULL,
                                               t3ver_stage      int(11) DEFAULT '0' NOT NULL,
                                               t3ver_count      int(11) DEFAULT '0' NOT NULL,
                                               t3ver_tstamp     int(11) DEFAULT '0' NOT NULL,
                                               t3ver_move_id    int(11) DEFAULT '0' NOT NULL,

                                               sys_language_uid int(11) DEFAULT '0' NOT NULL,
                                               l10n_parent      int(11) DEFAULT '0' NOT NULL,
                                               l10n_diffsource  mediumblob,
                                               l10n_state       text,

                                               PRIMARY KEY (uid),
                                               KEY parent (pid),
                                               KEY t3ver_oid (t3ver_oid, t3ver_wsid),
                                               KEY language (l10n_parent, sys_language_uid)

);

#
# Table structure for table 'tx_pictures_album_picture_mm'
#
CREATE TABLE tx_pictures_album_picture_mm
(
    uid_local       int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign     int(11) unsigned DEFAULT '0' NOT NULL,
    sorting         int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local, uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
