CREATE TABLE `tamu` (
	`id` int(10) NOT NULL auto_increment,
	`name` varchar(80) NOT NULL,
	`email` varchar(100) NOT NULL,
	`address` varchar(100) NOT NULL,
	`city` varchar(100) NOT NULL,
	`msg` text NOT NULL,
	PRIMARY KEY  (`id`)
);