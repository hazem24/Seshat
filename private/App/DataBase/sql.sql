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

create table tasks (id int auto_increment primary key,task_id int not null,
details longtext not null,expected_finish datetime not null,
is_finished bool default 0,progress int(3) default 0,
task_name varchar(50) not null);

ALTER TABLE tasks add column (user_id int not null), 
  ADD CONSTRAINT tasks FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;

create table follow_tree ( id int auto_increment primary key not null , 
name varchar( 500 )  not null , description varchar( 500 ) not null , max_accounts int not null);  

ALTER TABLE follow_tree add column (user_id int not null), 
  ADD CONSTRAINT tree_owner FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;


create table subscribed_in_tree ( id int auto_increment primary key not null , 
tokens text(1000) not null);

ALTER TABLE subscribed_in_tree add column (user_id int not null), 
  ADD CONSTRAINT sub_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE subscribed_in_tree add column (tree_id int not null), 
  ADD CONSTRAINT sub_tree FOREIGN KEY (tree_id) REFERENCES follow_tree (id) ON DELETE CASCADE ON UPDATE CASCADE;

Alter table follow_tree add column (tree_media int not null , created_at datetime not null default NOW());
Alter table subscribed_in_tree add column ( join_at datetime not null default NOW() );  

ALTER TABLE subscribed_in_tree CHANGE COLUMN user_id sub_user_id int NOT NULL;

create table notification (
id int auto_increment primary key not null ,
type int not null default 1,
status int not null default 1 , 
notify_msg varchar(1000) not null , 
is_read bool not null default 0);

ALTER TABLE notification add column (user_id int not null), 
  ADD CONSTRAINT user_notfication FOREIGN KEY (user_id) 
  REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE;
alter table notification add column ( created_at datetime not null default NOW());
alter table tasks add column(status int not null default 0);
alter table user_data add column (time_zone varchar(300) not null default 'UTC');

