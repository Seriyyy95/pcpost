CREATE TABLE permisions(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_Element' NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
group_id VARCHAR(255) NOT NULL,
action ENUM(
'show_post', 'edit_post', 'add_post', 'add_comment' , 'reply_comment', 'modify_comment', 'hide_comment',
 'vote', 'auth', 'show_authhistory', 'show_users_authhistory', 'show_userinfo', 'show_more_userinfo',
 'show_userprofile', 'show_journal', 'show_messages', 'edit_settings', 'work_messages', 'subscription_work', 
'administrate_user', 'safe_remove_post', 'safe_remove_user',
'edit_sections', 'edit_sitesettings', 'show_statistic',
'show_users', 'edit_widgets', 'service_works', 'edit_groups' , 'show_adminpanel', 'autors_subscription_work',
'show_favorites', 'show_users_favorites', 'work_favorites', 'show_hide_posts', 'show_people', 'generate_sitemap'
) NOT NULL,
PRIMARY KEY(id)
);
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_adminpanel');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_users_authhistory');
INSERT INTO permisions (group_id, action) VALUES ('1', 'modify_comment');
INSERT INTO permisions (group_id, action) VALUES ('1', 'hide_comment');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_more_userinfo');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_post');
INSERT INTO permisions (group_id, action) VALUES ('1', 'administrate_user');
INSERT INTO permisions (group_id, action) VALUES ('1', 'safe_remove_post');
INSERT INTO permisions (group_id, action) VALUES ('1', 'safe_remove_user');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_sections');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_sitesettings');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_statistic');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_users');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_widgets');
INSERT INTO permisions (group_id, action) VALUES ('1', 'service_works');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_groups');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_authhistory');
INSERT INTO permisions (group_id, action) VALUES ('1', 'add_comment');
INSERT INTO permisions (group_id, action) VALUES ('1', 'reply_comment');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_userinfo');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_journal');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_messages');
INSERT INTO permisions (group_id, action) VALUES ('1', 'work_messages');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_post');
INSERT INTO permisions (group_id, action) VALUES ('1', 'add_post');
INSERT INTO permisions (group_id, action) VALUES ('1', 'subscription_work');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_userprofile');
INSERT INTO permisions (group_id, action) VALUES ('1', 'edit_settings');
INSERT INTO permisions (group_id, action) VALUES ('1', 'vote');
INSERT INTO permisions (group_id, action) VALUES ('1', 'auth');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_favorites');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_users_favorites');
INSERT INTO permisions (group_id, action) VALUES ('1', 'work_favorites');
INSERT INTO permisions (group_id, action) VALUES ('1', 'autors_subscription_work');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_hide_post');
INSERT INTO permisions (group_id, action) VALUES ('1', 'show_people');
INSERT INTO permisions (group_id, action) VALUES ('1', 'generate_sitemap');
INSERT INTO permisions (group_id, action) VALUES ('2', 'show_post');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_authhistory');
INSERT INTO permisions (group_id, action) VALUES ('4', 'add_comment');
INSERT INTO permisions (group_id, action) VALUES ('4', 'reply_comment');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_userinfo');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_journal');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_messages');
INSERT INTO permisions (group_id, action) VALUES ('4', 'work_messages');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_post');
INSERT INTO permisions (group_id, action) VALUES ('4', 'add_post');
INSERT INTO permisions (group_id, action) VALUES ('4', 'subscription_work');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_userprofile');
INSERT INTO permisions (group_id, action) VALUES ('4', 'edit_settings');
INSERT INTO permisions (group_id, action) VALUES ('4', 'vote');
INSERT INTO permisions (group_id, action) VALUES ('4', 'auth');
INSERT INTO permisions (group_id, action) VALUES ('4', 'autors_subscription_work');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_favorites');
INSERT INTO permisions (group_id, action) VALUES ('4', 'work_favorites');
INSERT INTO permisions (group_id, action) VALUES ('4', 'show_people')
