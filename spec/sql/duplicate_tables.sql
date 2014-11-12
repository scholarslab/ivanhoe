CREATE TABLE IF NOT EXISTS `wp_commentmeta_copy` LIKE `wp_commentmeta`;

DELETE FROM `wp_commentmeta_copy`;

INSERT INTO `wp_commentmeta_copy` SELECT * FROM `wp_commentmeta`;

CREATE TABLE IF NOT EXISTS `wp_comments_copy` LIKE `wp_comments`;

DELETE FROM `wp_comments_copy`;

INSERT INTO `wp_comments_copy` SELECT * FROM `wp_comments`;

CREATE TABLE IF NOT EXISTS `wp_links_copy` LIKE `wp_links`;

DELETE FROM `wp_links_copy`;

INSERT INTO `wp_links_copy` SELECT * FROM `wp_links`;

CREATE TABLE IF NOT EXISTS `wp_options_copy` LIKE `wp_options`;

DELETE FROM `wp_options_copy`;

INSERT INTO `wp_options_copy` SELECT * FROM `wp_options`;

CREATE TABLE IF NOT EXISTS `wp_postmeta_copy` LIKE `wp_postmeta`;

DELETE FROM `wp_postmeta_copy`;

INSERT INTO `wp_postmeta_copy` SELECT * FROM `wp_postmeta`;

CREATE TABLE IF NOT EXISTS `wp_posts_copy` LIKE `wp_posts`;

DELETE FROM `wp_posts_copy`;

INSERT INTO `wp_posts_copy` SELECT * FROM `wp_posts`;

CREATE TABLE IF NOT EXISTS `wp_terms_copy` LIKE `wp_terms`;

DELETE FROM `wp_terms_copy`;

INSERT INTO `wp_terms_copy` SELECT * FROM `wp_terms`;

CREATE TABLE IF NOT EXISTS `wp_term_relationships_copy` LIKE `wp_term_relationships`;

DELETE FROM `wp_term_relationships_copy`;

INSERT INTO `wp_term_relationships_copy` SELECT * FROM `wp_term_relationships`;

CREATE TABLE IF NOT EXISTS `wp_term_taxonomy_copy` LIKE `wp_term_taxonomy`;

DELETE FROM `wp_term_taxonomy_copy`;

INSERT INTO `wp_term_taxonomy_copy` SELECT * FROM `wp_term_taxonomy`;

CREATE TABLE IF NOT EXISTS `wp_usermeta_copy` LIKE `wp_usermeta`;

DELETE FROM `wp_usermeta_copy`;

INSERT INTO `wp_usermeta_copy` SELECT * FROM `wp_options`;

CREATE TABLE IF NOT EXISTS `wp_users_copy` LIKE `wp_users`;

DELETE FROM `wp_users_copy`;

INSERT INTO `wp_users_copy` SELECT * FROM `wp_users`;
