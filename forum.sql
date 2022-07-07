SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(25) NOT NULL,
  `cat_desc` varchar(125) NOT NULL,
  `cat_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `msg_sender` int(11) NOT NULL,
  `msg_receiver` int(11) NOT NULL,
  `msg_seen` tinyint(1) NOT NULL DEFAULT 0,
  `msg_title` varchar(255) NOT NULL,
  `msg_body` text NOT NULL,
  `msg_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `post_cat` int(11) NOT NULL,
  `post_title` varchar(60) NOT NULL,
  `post_body` text NOT NULL,
  `post_created` datetime NOT NULL DEFAULT current_timestamp(),
  `post_locked` tinyint(1) NOT NULL DEFAULT 0,
  `post_poster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `reply_post` int(11) NOT NULL,
  `reply_poster` int(11) NOT NULL,
  `reply_created` datetime NOT NULL,
  `reply_body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_password` text NOT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_status` varchar(250) NOT NULL DEFAULT 'Hi, I''m new here!',
  `user_bio` text DEFAULT NULL,
  `user_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_email` varchar(500) NOT NULL,
  `user_signup_ip` varchar(100) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `user_admin` tinyint(4) NOT NULL DEFAULT 0,
  `user_flood` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);


ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
