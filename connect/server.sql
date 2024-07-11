CREATE TABLE `tbl_admin_db` (
  `ad_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `ad_url` varchar(255) NOT NULL,
  `ad_firstname` varchar(255) NOT NULL,
  `ad_lastname` varchar(255) NOT NULL,
  `ad_email` varchar(255) NOT NULL,
  `ad_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_admin_db` (`ad_id`, `ad_url`, `ad_firstname`, `ad_lastname`, `ad_email`, `ad_password`)
VALUES (1, 'LqaPsR1Pnc', 'ศักดา', 'สุขขวัญ', 'admin@ars.com', '9c93bd12ff5ee85a40cb8b7ad5a3e8f0');

CREATE TABLE `tbl_member_db` (
  `mb_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `mb_url` varchar(255) NOT NULL,
  `mb_firstname` varchar(255) NOT NULL,
  `mb_lastname` varchar(255) NOT NULL,
  `mb_email` varchar(255) NOT NULL,
  `mb_time_add` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_report_db` (
  `rp_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `rp_url` varchar(255) NOT NULL,
  `mb_url` varchar(255) NOT NULL,
  `mb_token` varchar(255) NOT NULL,
  `rp_cash_out` int(11) NOT NULL,
  `rp_cash_in` int(11) NOT NULL,
  `rp_note` varchar(255) NOT NULL,
  `rp_time_add` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;