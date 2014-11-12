CREATE TABLE IF NOT EXISTS `copy_wp_commentmeta` LIKE `wp_commentmeta`;

DELETE FROM `copy_wp_commentmeta`;

INSERT INTO `copy_wp_commentmeta` SELECT * FROM `wp_commentmeta`;

CREATE TABLE IF NOT EXISTS `copy_wp_comments` LIKE `wp_comments`;

DELETE FROM `copy_wp_comments`;

INSERT INTO `copy_wp_comments` SELECT * FROM `wp_comments`;

CREATE TABLE IF NOT EXISTS `copy_wp_links` LIKE `wp_links`;

DELETE FROM `copy_wp_links`;

INSERT INTO `copy_wp_links` SELECT * FROM `wp_links`;

CREATE TABLE IF NOT EXISTS `copy_wp_options` LIKE `wp_options`;

DELETE FROM `copy_wp_options`;

INSERT INTO `copy_wp_options` SELECT * FROM `wp_options`;

CREATE TABLE IF NOT EXISTS `copy_wp_postmeta` LIKE `wp_postmeta`;

DELETE FROM `copy_wp_postmeta`;

INSERT INTO `copy_wp_postmeta` SELECT * FROM `wp_postmeta`;

CREATE TABLE IF NOT EXISTS `copy_wp_posts` LIKE `wp_posts`;

DELETE FROM `copy_wp_posts`;

INSERT INTO `copy_wp_posts` SELECT * FROM `wp_posts`;

CREATE TABLE IF NOT EXISTS `copy_wp_terms` LIKE `wp_terms`;

DELETE FROM `copy_wp_terms`;

INSERT INTO `copy_wp_terms` SELECT * FROM `wp_terms`;

CREATE TABLE IF NOT EXISTS `copy_wp_term_relationships` LIKE `wp_term_relationships`;

DELETE FROM `copy_wp_term_relationships`;

INSERT INTO `copy_wp_term_relationships` SELECT * FROM `wp_term_relationships`;

CREATE TABLE IF NOT EXISTS `copy_wp_term_taxonomy` LIKE `wp_term_taxonomy`;

DELETE FROM `copy_wp_term_taxonomy`;

INSERT INTO `copy_wp_term_taxonomy` SELECT * FROM `wp_term_taxonomy`;

CREATE TABLE IF NOT EXISTS `copy_wp_usermeta` LIKE `wp_usermeta`;

DELETE FROM `copy_wp_usermeta`;

INSERT INTO `copy_wp_usermeta` SELECT * FROM `wp_options`;

CREATE TABLE IF NOT EXISTS `copy_wp_users` LIKE `wp_users`;

DELETE FROM `copy_wp_users`;

INSERT INTO `copy_wp_users` SELECT * FROM `wp_users`;
