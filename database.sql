START TRANSACTION;

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `enabled` bigint(1) NOT NULL DEFAULT 0,
  `create_IP` varchar(255) NOT NULL DEFAULT '',
  `create_datetime` char(14) NOT NULL DEFAULT '00000000000000',
  `email_checked` bigint(1) NOT NULL DEFAULT 0,
  `email_check_ip` varchar(255) NOT NULL DEFAULT '',
  `email_check_datetime` char(14) NOT NULL DEFAULT '00000000000000'
) DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`id`);

ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

COMMIT;
