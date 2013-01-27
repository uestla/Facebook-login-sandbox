Facebook login using Nette Framework
====================================

Quick steps to make this sandbox work:

- create [Facebook application](https://developers.facebook.com/apps) properly
- create following table in your database:

```sql
SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `facebook_id` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `birthday` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`facebook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
```

- open **app/config/config.local.neon** and set your local environment variables

Sitenotes:

- pay attention to CSRF protection at both login and logout links in **app/presenters/HomepagePresenter.php**
