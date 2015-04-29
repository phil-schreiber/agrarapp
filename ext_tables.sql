#
# Table structure for table 'tx_agrarapp_infos_region_mm'
#
#
CREATE TABLE tx_agrarapp_infos_region_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_agrarapp_infos_cultivar_mm'
#
#
CREATE TABLE tx_agrarapp_infos_cultivar_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_agrarapp_infos'
#
CREATE TABLE tx_agrarapp_infos (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	region int(11) DEFAULT '0' NOT NULL,
	cultivar int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	abstract text,
	bodytext text,
	image text,
	bodytext2 text,
	image2 text,
	bodytext3 text,
	image3 text,
	wffinished int(11) DEFAULT '0' NOT NULL,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_zipcodes'
#
CREATE TABLE tx_agrarapp_zipcodes (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	country tinytext,
	language tinytext,
	id int(11) DEFAULT '0' NOT NULL,
	iso2 tinytext,
	region1 tinytext,
	region2 tinytext,
	region3 tinytext,
	region4 tinytext,
	zip int(11) DEFAULT '0' NOT NULL,
	city tinytext,
	area1 tinytext,
	area2 tinytext,
	latitude tinytext,
	longitude tinytext,
	tz tinytext,
	utc tinytext,
	dst tinytext,
	baywaid int(11) DEFAULT '0' NOT NULL

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY baywaid (baywaid)
) ENGINE=MyISAM;




#
# Table structure for table 'tx_agrarapp_regions_zipcodes_mm'
#
#
CREATE TABLE tx_agrarapp_regions_zipcodes_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_agrarapp_regions'
#
CREATE TABLE tx_agrarapp_regions (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,
	zipcodes int(11) DEFAULT '0' NOT NULL,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_cultivar'
#
CREATE TABLE tx_agrarapp_cultivar (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_mixedprices'
#
CREATE TABLE tx_agrarapp_mixedprices (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	price int(11) DEFAULT '0' NOT NULL,
	unit tinytext,
	category int(11) DEFAULT '0' NOT NULL,
	history text,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_mixedprices_categories'
#
CREATE TABLE tx_agrarapp_mixedprices_categories (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_mixedprices_archive'
#
CREATE TABLE tx_agrarapp_mixedprices_archive (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	price int(11) DEFAULT '0' NOT NULL,
	unit tinytext,
	category int(11) DEFAULT '0' NOT NULL,
	history text,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;




#
# Table structure for table 'tx_agrarapp_profiles_zip_mm'
#
#
CREATE TABLE tx_agrarapp_profiles_zip_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_agrarapp_profiles'
#
CREATE TABLE tx_agrarapp_profiles (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	name tinytext,
	street tinytext,
	zip int(11) DEFAULT '0' NOT NULL,
	city tinytext,
	email tinytext,
	phone tinytext,
	fax tinytext,
	mobile tinytext,
	picture text,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;




#
# Table structure for table 'tx_agrarapp_events_regions_mm'
#
#
CREATE TABLE tx_agrarapp_events_regions_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_agrarapp_events'
#
CREATE TABLE tx_agrarapp_events (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	abstract text,
	street tinytext,
	zip int(11) DEFAULT '0' NOT NULL,
	city tinytext,
	address_addition tinytext,
	datetime_start int(11) DEFAULT '0' NOT NULL,
	datetime_end int(11) DEFAULT '0' NOT NULL,
	regions int(11) DEFAULT '0' NOT NULL,
	markdeleted tinyint(3) DEFAULT '0' NOT NULL,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_weatherdata'
#
CREATE TABLE tx_agrarapp_weatherdata (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	zip int(11) DEFAULT '0' NOT NULL,
	current text,
	forecast text,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_weathermaps'
#
CREATE TABLE tx_agrarapp_weathermaps (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	zip int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	category int(11) DEFAULT '0' NOT NULL,
	image text,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_weathermaps_categories'
#
CREATE TABLE tx_agrarapp_weathermaps_categories (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_marketdata_categories'
#
CREATE TABLE tx_agrarapp_marketdata_categories (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_marketdata'
#
CREATE TABLE tx_agrarapp_marketdata (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	price double DEFAULT '',
	originalid text,
	datetime int(11) DEFAULT '0' NOT NULL,
	details text,
	category int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (originalid(100)),
	KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_marketdata_history'
#
CREATE TABLE tx_agrarapp_marketdata_history (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	price double DEFAULT '',
	originalid text,
	datetime int(11) DEFAULT '0' NOT NULL,
	details text,
	category int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;


#
# Table structure for table 'tx_agrarapp_marketdata'
#
CREATE TABLE tx_agrarapp_futures (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL DEFAULT '0',
  tstamp int(11) NOT NULL DEFAULT '0',
  crdate int(11) NOT NULL DEFAULT '0',
  cruser_id int(11) NOT NULL DEFAULT '0',
  sorting int(10) NOT NULL DEFAULT '0',
  deleted tinyint(4) NOT NULL DEFAULT '0',
  hidden tinyint(4) NOT NULL DEFAULT '0',
  originalid varchar(40) NOT NULL DEFAULT '',
  originalid_short text,
  datetime int(11) NOT NULL DEFAULT '0',
  details text,
  PRIMARY KEY (originalid),
  KEY uid (uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

#
# Table structure for table 'tx_agrarapp_marketdata'
#
CREATE TABLE tx_agrarapp_weatherwarnings (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL DEFAULT '0',
  tstamp int(11) NOT NULL DEFAULT '0',
  crdate int(11) NOT NULL DEFAULT '0',
  cruser_id int(11) NOT NULL DEFAULT '0',
  sorting int(10) NOT NULL DEFAULT '0',
  deleted tinyint(4) NOT NULL DEFAULT '0',
  hidden tinyint(4) NOT NULL DEFAULT '0',
  starttime int(11) DEFAULT '0' NOT NULL,
  endtime int(11) DEFAULT '0' NOT NULL,
  zipcode int(10) NOT NULL DEFAULT '0',
  warninglevel tinyint(4) NOT NULL DEFAULT '0',
  warningtype tinyint(4) NOT NULL DEFAULT '0',
  warningtext text,
  PRIMARY KEY (uid),
  KEY zipcode (zipcode),
  KEY starttime (starttime),
  KEY endtime (endtime)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


#
# Table structure for table 'tx_agrarapp_locations_zipcodes_mm'
#
#
CREATE TABLE tx_agrarapp_locations_zipcodes_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_agrarapp_locations'
#
CREATE TABLE tx_agrarapp_locations (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	location tinytext,
	division tinytext,
	street tinytext,
	zip int(11) DEFAULT '0' NOT NULL,
	city tinytext,
	phone tinytext,
	fax tinytext,
	email tinytext,
	zipcodes int(11) DEFAULT '0' NOT NULL,
	baywaid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;


#
# Table structure for table 'tx_agrarapp_devices'
#
CREATE TABLE tx_agrarapp_devices (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    deviceid text,
    ostype text,

    PRIMARY KEY (uid),
    KEY parent (pid)
) ENGINE=InnoDB;



#
# Table structure for table 'tx_agrarapp_subscriptions'
#
CREATE TABLE tx_agrarapp_subscriptions (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    category int(11) DEFAULT '0' NOT NULL,
    zipcode int(11) DEFAULT '0' NOT NULL,
    subtype int(11) DEFAULT '0' NOT NULL,
    deviceid text,

    PRIMARY KEY (uid),
    KEY parent (pid)
) ENGINE=InnoDB;


