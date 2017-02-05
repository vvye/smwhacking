SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `smwhacking`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;
USE `smwhacking`;

CREATE TABLE `awarded_medals` (
  `user`       INT(11) NOT NULL,
  `medal`      INT(11) NOT NULL,
  `award_time` INT(11) NOT NULL,
  `favorite`   INT(11) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `chat_messages` (
  `id`        INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `post_time` INT(11)    NOT NULL,
  `content`   TEXT       NOT NULL,
  `deleted`   TINYINT(1) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `edits` (
  `post`      INT(11) NOT NULL,
  `user`      INT(11) NOT NULL,
  `edit_time` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `files` (
  `id`                INT(11)       NOT NULL,
  `user`              INT(11)       NOT NULL,
  `name`              VARCHAR(50)   NOT NULL,
  `extension`         VARCHAR(10)   NOT NULL,
  `short_description` VARCHAR(50)   NOT NULL,
  `long_description`  VARCHAR(1000) NOT NULL,
  `upload_time`       INT(11)       NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `forums` (
  `id`                INT(11)      NOT NULL,
  `category`          INT(11)      NOT NULL,
  `name`              VARCHAR(255) NOT NULL,
  `description`       TEXT         NOT NULL,
  `threads`           INT(11)      NOT NULL,
  `posts`             INT(11)      NOT NULL,
  `last_post`         INT(11)      NOT NULL,
  `sort_order`        INT(11)      NOT NULL,
  `view_powerlevel`   INT(11)      NOT NULL,
  `post_powerlevel`   INT(11)      NOT NULL,
  `thread_powerlevel` INT(11)      NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `forum_categories` (
  `id`         INT(11)      NOT NULL,
  `name`       VARCHAR(255) NOT NULL,
  `sort_order` INT(11)      NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `medals` (
  `id`              INT(11)                                                NOT NULL,
  `category`        INT(11)                                                NOT NULL,
  `name`            VARCHAR(255)                                           NOT NULL,
  `description`     TEXT                                                   NOT NULL,
  `image_filename`  VARCHAR(128)                                           NOT NULL,
  `award_condition` ENUM ('manual', 'post_count', 'registration_time', '') NOT NULL,
  `value`           INT(11)                                                NOT NULL,
  `secret`          TINYINT(1)                                             NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `medal_categories` (
  `id`   INT(11)      NOT NULL,
  `name` VARCHAR(255) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `posts` (
  `id`        INT(11)    NOT NULL,
  `thread`    INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `post_time` INT(11)    NOT NULL,
  `content`   TEXT       NOT NULL,
  `deleted`   TINYINT(1) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `private_messages` (
  `id`        INT(11)    NOT NULL,
  `send_time` INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `recipient` INT(11)    NOT NULL,
  `subject`   TEXT       NOT NULL,
  `content`   TEXT       NOT NULL,
  `unread`    TINYINT(1) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `ranks` (
  `id`        INT(11)      NOT NULL,
  `name`      VARCHAR(255) NOT NULL,
  `min_posts` INT(11)      NOT NULL,
  `has_image` TINYINT(1)   NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `smileys` (
  `id`             INT(10) UNSIGNED NOT NULL,
  `code`           VARCHAR(50)
                   CHARACTER SET utf8
                   COLLATE utf8_bin NOT NULL DEFAULT '',
  `name`           VARCHAR(100)     NOT NULL,
  `image_filename` VARCHAR(100)     NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `threads` (
  `id`             INT(11)      NOT NULL,
  `forum`          INT(11)      NOT NULL,
  `name`           VARCHAR(511) NOT NULL,
  `creation_time`  INT(11)      NOT NULL,
  `posts`          INT(11)      NOT NULL,
  `last_post`      INT(11)      NOT NULL,
  `last_post_time` INT(11)      NOT NULL,
  `views`          INT(11)      NOT NULL,
  `closed`         TINYINT(1)   NOT NULL,
  `sticky`         TINYINT(1)   NOT NULL,
  `deleted`        TINYINT(1)   NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `threads_read` (
  `user`           INT(11) NOT NULL,
  `thread`         INT(11) NOT NULL,
  `last_read_time` INT(11) NOT NULL
  COMMENT 'Post-Zeit des letzten gelesenen Posts'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `users` (
  `id`                   INT(11)                  NOT NULL,
  `name`                 VARCHAR(255)             NOT NULL,
  `password`             TEXT                     NOT NULL,
  `legacy_login`         TINYINT(1)               NOT NULL
  COMMENT 'ist der Passwort-Hash noch im alten phpBB-Format gespeichert?',
  `email`                VARCHAR(100)             NOT NULL,
  `powerlevel`           INT(11)                  NOT NULL
  COMMENT '0 = normaler User, 1 = Mod, 2 = Admin',
  `title`                VARCHAR(255)             NOT NULL,
  `bio`                  TEXT                     NOT NULL,
  `signature`            TEXT                     NOT NULL,
  `location`             VARCHAR(100)             NOT NULL,
  `website`              VARCHAR(100)             NOT NULL,
  `theme`                ENUM ('default', 'dark') NOT NULL DEFAULT 'default',
  `enable_notifications` TINYINT(1)               NOT NULL,
  `registration_time`    INT(11)                  NOT NULL,
  `last_login_time`      INT(11)                  NOT NULL,
  `banned`               TINYINT(1)               NOT NULL,
  `activated`            TINYINT(1)               NOT NULL
  COMMENT 'Registrierung per E-Mail abgeschlossen',
  `activation_token`     VARCHAR(32)              NOT NULL,
  `csrf_token`           VARCHAR(16)              NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `watched_threads` (
  `user`   INT(11) NOT NULL,
  `thread` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


ALTER TABLE `awarded_medals`
  ADD UNIQUE KEY `user` (`user`, `medal`);

ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `medals`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `medal_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `private_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `smileys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `threads_read`
  ADD PRIMARY KEY (`user`, `thread`),
  ADD UNIQUE KEY `user` (`user`, `thread`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `watched_threads`
  ADD UNIQUE KEY `thread` (`thread`, `user`);


ALTER TABLE `chat_messages`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `files`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forums`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forum_categories`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `medals`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `medal_categories`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `posts`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `private_messages`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ranks`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `smileys`
  MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `threads`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
