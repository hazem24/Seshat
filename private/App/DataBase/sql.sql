CREATE database IF NOT exists seshat;
use seshat;

CREATE TABLE user (id int auto_increment primary key not null,
tw_id varchar(1000) unique not null,
screen_name varchar(100) not null , 
oauth_token varchar(100) not null,
oauth_token_secret varchar(100) not null);

create table user_data (id int auto_increment primary key not null,
email varchar(500) unique not null,
account_type int not null default 1,
user_describe varchar(500) not null,
iswizard bool not null default 1,
created_at datetime not null default Now());


ALTER TABLE user_data add column (user_id int not null), 
  ADD CONSTRAINT user_data FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;


  CREATE TABLE `seshatsession` (
  `id` varchar(32) NOT NULL,
  `lastaccess` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sdata` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

alter table user_data add column (name varchar(100) not null);

create table seshat_publish (id int auto_increment primary key,
tweet_id varchar(1000) not null,
publish_at datetime not null default Now());

ALTER TABLE seshat_publish add column (user_id int not null), 
  ADD CONSTRAINT seshat_publish FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;

  alter table seshat_publish add column (category_id int not null default 0);

  alter table seshat_publish add column (public_access bool default false);
