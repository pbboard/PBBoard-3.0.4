<?php
/**
 * PBBoard 3.3
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 * Website: https://pbboard.info
 * License: https://pbboard.info/about/license
 *
 */

$tables[] = "CREATE TABLE pbb_addons (
  id int(9) NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL default '',
  title varchar(250) NOT NULL default '',
  version varchar(25) NOT NULL default '',
  description text NOT NULL,
  author varchar(250) NOT NULL default '',
  url varchar(350) NOT NULL default '',
  installcode text NOT NULL,
  uninstallcode text NOT NULL,
  module_index mediumtext NOT NULL,
  module_admin mediumtext NOT NULL,
  active smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  languagevals longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_ads (
  id int(9) NOT NULL AUTO_INCREMENT,
  sitename text NOT NULL,
  site text NOT NULL,
  picture text NOT NULL,
  width int(9) NOT NULL DEFAULT '0',
  height int(9) NOT NULL DEFAULT '0',
  clicks int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_adsense (
  id int(9) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL default '',
  adsense text NOT NULL,
  home int(9) NOT NULL DEFAULT '0',
  forum int(9) NOT NULL DEFAULT '0',
  topic int(9) NOT NULL DEFAULT '0',
  downfoot int(9) NOT NULL DEFAULT '0',
  all_page int(9) NOT NULL DEFAULT '0',
  between_replys int(9) NOT NULL DEFAULT '0',
  down_topic int(9) NOT NULL DEFAULT '0',
  in_page varchar(255) NOT NULL DEFAULT '0',
  side int(9) NOT NULL DEFAULT '0',
  mid_topic int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_announcement (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(200) NOT NULL default '',
  text text NOT NULL,
  writer varchar(200) NOT NULL default '',
  date varchar(100) NOT NULL default '',
  visitor int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_attach (
  id int(9) NOT NULL AUTO_INCREMENT,
  filename varchar(350) NOT NULL default '',
  filepath varchar(350) NOT NULL default '',
  filesize varchar(20) NOT NULL DEFAULT '0',
  subject_id int(9) NOT NULL DEFAULT '0',
  visitor int(9) NOT NULL DEFAULT '0',
  reply int(9) NOT NULL DEFAULT '0',
  pm_id int(9) NOT NULL DEFAULT '0',
  u_id int(9) NOT NULL DEFAULT '0',
  time int(11) NOT NULL DEFAULT '0',
  last_down int(11) NOT NULL DEFAULT '0',
  extension varchar(20) NOT NULL default '',
  user_ip varchar(250) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_avatar (
  id int(9) NOT NULL AUTO_INCREMENT,
  avatar_path varchar(250) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_award (
  id int(10) NOT NULL AUTO_INCREMENT,
  award varchar(200) NOT NULL default '',
  award_path varchar(250) NOT NULL default '',
  username varchar(100) NOT NULL default '',
  user_id int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_banned (
  id int(9) NOT NULL AUTO_INCREMENT,
  text text NOT NULL,
  text_type int(1) NOT NULL DEFAULT '0',
  ip varchar(100) NOT NULL default '',
  reason text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_blocks (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(355) NOT NULL default '',
  text longtext NOT NULL,
  place_block varchar(300) NOT NULL default '',
  sort int(5) NOT NULL DEFAULT '0',
  active smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_chat (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL default '',
  country varchar(100) NOT NULL default '',
  message text NOT NULL,
  user_id int(9) NOT NULL DEFAULT '0',
  date varchar(100) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_custom_bbcode (
  id int(9) NOT NULL AUTO_INCREMENT,
  bbcode_title varchar(355) NOT NULL DEFAULT '',
  bbcode_desc text NOT NULL,
  bbcode_tag varchar(255) NOT NULL DEFAULT '',
  bbcode_replace text NOT NULL,
  bbcode_useoption tinyint(1) NOT NULL DEFAULT '0',
  bbcode_example text NOT NULL,
  bbcode_switch varchar(355) NOT NULL DEFAULT '',
  bbcode_add_into_menu int(1) NOT NULL DEFAULT '0',
  bbcode_menu_option_text varchar(400) NOT NULL DEFAULT '',
  bbcode_menu_content_text varchar(400) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_emailed (
  id int(9) NOT NULL AUTO_INCREMENT,
  user_id int(9) NOT NULL DEFAULT '0',
  subject_title varchar(200) NOT NULL default '',
  section_title varchar(200) NOT NULL default '',
  subject_id int(9) NOT NULL DEFAULT '0',
  section_id int(9) NOT NULL DEFAULT '0',
  autosubscribe int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_emailmessages (
  id int(9) NOT NULL AUTO_INCREMENT,
  title text NOT NULL,
  number_messages int(9) NOT NULL DEFAULT '0',
  seconds int(9) NOT NULL DEFAULT '0',
  user_group varchar(200) NOT NULL default '',
  message longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_email_msg (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(300) NOT NULL default '',
  text text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_ex (
  id int(9) NOT NULL AUTO_INCREMENT,
  Ex varchar(100) NOT NULL default '',
  max_size varchar(100) NOT NULL default '',
  mime_type varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_extrafield (
  id int(9) NOT NULL AUTO_INCREMENT,
  name varchar(200) NOT NULL default '',
  show_in_forum varchar(3) NOT NULL default '',
  required varchar(3) NOT NULL default '',
  type varchar(250) NOT NULL default '',
  options text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_faq (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(200) NOT NULL default '',
  text longtext NOT NULL,
  description longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_feeds (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL default '',
  title2 varchar(250) NOT NULL default '',
  rsslink text NOT NULL,
  userid int(10) UNSIGNED NOT NULL DEFAULT '0',
  forumid smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  text mediumtext NOT NULL,
  ttl smallint(5) UNSIGNED NOT NULL DEFAULT '1500',
  options int(10) UNSIGNED NOT NULL DEFAULT '1',
  feeds_time varchar(20) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_friends (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(100) NOT NULL default '',
  userid_friend int(9) NOT NULL DEFAULT '0',
  username_friend varchar(100) NOT NULL default '',
  approval int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_group (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(200) NOT NULL default '',
  username_style varchar(100) NOT NULL default '',
  user_title varchar(100) NOT NULL default '',
  forum_team int(1) NOT NULL DEFAULT '0',
  banned int(1) NOT NULL DEFAULT '0',
  view_section int(1) NOT NULL DEFAULT '0',
  download_attach int(1) NOT NULL DEFAULT '0',
  download_attach_number smallint(4) UNSIGNED NOT NULL default '0',
  write_subject int(1) NOT NULL DEFAULT '0',
  write_reply int(1) NOT NULL DEFAULT '0',
  upload_attach int(1) NOT NULL DEFAULT '0',
  upload_attach_num int(5) NOT NULL DEFAULT '0',
  edit_own_subject int(1) NOT NULL DEFAULT '0',
  edit_own_reply int(1) NOT NULL DEFAULT '0',
  del_own_subject int(1) NOT NULL DEFAULT '0',
  del_own_reply int(1) NOT NULL DEFAULT '0',
  write_poll int(1) NOT NULL DEFAULT '0',
  vote_poll int(1) NOT NULL DEFAULT '0',
  no_posts int(1) NOT NULL DEFAULT '0',
  use_pm int(1) NOT NULL DEFAULT '0',
  send_pm int(1) NOT NULL DEFAULT '0',
  resive_pm int(1) NOT NULL DEFAULT '0',
  max_pm int(9) NOT NULL DEFAULT '0',
  min_send_pm int(9) NOT NULL DEFAULT '0',
  sig_allow int(1) NOT NULL DEFAULT '0',
  sig_len int(5) NOT NULL DEFAULT '0',
  group_mod int(1) NOT NULL DEFAULT '0',
  del_subject int(1) NOT NULL DEFAULT '0',
  del_reply int(1) NOT NULL DEFAULT '0',
  edit_subject int(1) NOT NULL DEFAULT '0',
  edit_reply int(1) NOT NULL DEFAULT '0',
  stick_subject int(1) NOT NULL DEFAULT '0',
  unstick_subject int(1) NOT NULL DEFAULT '0',
  move_subject int(1) NOT NULL DEFAULT '0',
  close_subject int(1) NOT NULL DEFAULT '0',
  usercp_allow int(1) NOT NULL DEFAULT '0',
  admincp_allow int(1) NOT NULL DEFAULT '0',
  search_allow int(1) NOT NULL DEFAULT '0',
  memberlist_allow int(1) NOT NULL DEFAULT '0',
  vice int(1) NOT NULL DEFAULT '0',
  show_hidden int(1) NOT NULL DEFAULT '0',
  view_usernamestyle int(1) NOT NULL DEFAULT '0',
  usertitle_change int(1) NOT NULL DEFAULT '0',
  onlinepage_allow int(1) NOT NULL DEFAULT '0',
  allow_see_offstyles int(1) NOT NULL DEFAULT '0',
  admincp_section int(1) NOT NULL DEFAULT '0',
  admincp_option int(1) NOT NULL DEFAULT '0',
  admincp_member int(1) NOT NULL DEFAULT '0',
  admincp_membergroup int(1) NOT NULL DEFAULT '0',
  admincp_membertitle int(1) NOT NULL DEFAULT '0',
  admincp_admin int(1) NOT NULL DEFAULT '0',
  admincp_adminstep int(1) NOT NULL DEFAULT '0',
  admincp_subject int(1) NOT NULL DEFAULT '0',
  admincp_database int(1) NOT NULL DEFAULT '0',
  admincp_fixup int(1) NOT NULL DEFAULT '0',
  admincp_ads int(1) NOT NULL DEFAULT '0',
  admincp_template int(1) NOT NULL DEFAULT '0',
  admincp_adminads int(1) NOT NULL DEFAULT '0',
  admincp_attach int(1) NOT NULL DEFAULT '0',
  admincp_page int(1) NOT NULL DEFAULT '0',
  admincp_block int(1) NOT NULL DEFAULT '0',
  admincp_style int(1) NOT NULL DEFAULT '0',
  admincp_toolbox int(1) NOT NULL DEFAULT '0',
  admincp_smile int(1) NOT NULL DEFAULT '0',
  admincp_icon int(1) NOT NULL DEFAULT '0',
  admincp_avater int(1) NOT NULL DEFAULT '0',
  group_order int(9) NOT NULL DEFAULT '0',
  admincp_contactus int(1) NOT NULL DEFAULT '0',
  send_warning int(1) NOT NULL DEFAULT '0',
  can_warned int(1) NOT NULL DEFAULT '0',
  hide_allow int(1) NOT NULL DEFAULT '0',
  visitormessage int(1) NOT NULL DEFAULT '0',
  see_who_on_topic int(1) NOT NULL DEFAULT '0',
  reputation_number int(1) NOT NULL DEFAULT '0',
  admincp_chat int(1) NOT NULL DEFAULT '0',
  admincp_extrafield int(1) NOT NULL DEFAULT '0',
  admincp_lang int(1) NOT NULL DEFAULT '0',
  admincp_emailed int(1) NOT NULL DEFAULT '0',
  admincp_warn int(1) NOT NULL DEFAULT '0',
  admincp_award int(1) NOT NULL DEFAULT '0',
  admincp_multi_moderation int(1) NOT NULL DEFAULT '0',
  view_subject int(1) NOT NULL DEFAULT '0',
  review_subject int(1) NOT NULL DEFAULT '0',
  review_reply int(1) NOT NULL DEFAULT '0',
  view_action_edit int(1) NOT NULL DEFAULT '1',
  topic_day_number int(1) NOT NULL DEFAULT '0',
  groups_security int(1) NOT NULL DEFAULT '1',
  profile_photo int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_hooks (
  id int(9) NOT NULL AUTO_INCREMENT,
  addon_id int(9) NOT NULL DEFAULT '0',
  main_place varchar(250) NOT NULL default '',
  place_of_hook varchar(250) NOT NULL default '',
  phpcode longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_info (
  id int(9) NOT NULL AUTO_INCREMENT,
  var_name varchar(255) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_lang (
  id int(9) NOT NULL AUTO_INCREMENT,
  lang_title varchar(200) NOT NULL default '',
  lang_order int(9) NOT NULL DEFAULT '0',
  lang_on int(1) NOT NULL DEFAULT '1',
  lang_path varchar(200) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_member (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL default '',
  password varchar(250) NOT NULL default '',
  email varchar(250) NOT NULL default '',
  usergroup int(9) NOT NULL DEFAULT '0',
  membergroupids varchar(250) NOT NULL default '',
  user_notes mediumtext NOT NULL,
  user_sig mediumtext NOT NULL,
  user_country varchar(100) NOT NULL default '',
  user_gender varchar(1) NOT NULL default '',
  user_website varchar(100) NOT NULL default '',
  lastvisit varchar(10) NOT NULL default '',
  user_time varchar(6) NOT NULL default '',
  register_date varchar(100) NOT NULL default '',
  posts int(9) NOT NULL DEFAULT '0',
  user_title varchar(300) NOT NULL default '',
  visitor int(9) NOT NULL DEFAULT '0',
  user_info mediumtext NOT NULL,
  avater_path mediumtext NOT NULL,
  away int(1) NOT NULL DEFAULT '0',
  away_msg mediumtext NOT NULL,
  new_password varchar(200) NOT NULL default '',
  new_email varchar(250) NOT NULL default '',
  active_number varchar(90) NOT NULL default '',
  style int(9) NOT NULL DEFAULT '0',
  hide_online int(1) NOT NULL DEFAULT '0',
  send_allow int(1) NOT NULL DEFAULT '1',
  unread_pm int(9) NOT NULL DEFAULT '0',
  lastpost_time varchar(15) NOT NULL default '',
  keepmeon int(9) NOT NULL DEFAULT '0',
  logged varchar(30) NOT NULL default '',
  register_time varchar(50) NOT NULL default '',
  style_cache text NOT NULL,
  style_id_cache int(9) NOT NULL DEFAULT '0',
  should_update_style_cache int(1) NOT NULL DEFAULT '0',
  autoreply int(9) NOT NULL DEFAULT '0',
  autoreply_title varchar(255) NOT NULL default '',
  autoreply_msg text NOT NULL,
  pm_senders int(1) NOT NULL DEFAULT '0',
  pm_senders_msg varchar(255) NOT NULL default '',
  member_ip varchar(20) NOT NULL default '',
  subject_sig mediumtext NOT NULL,
  reply_sig mediumtext NOT NULL,
  username_style_cache varchar(255) NOT NULL default '',
  review_subject int(1) NOT NULL DEFAULT '0',
  inviter varchar(250) NOT NULL default '',
  invite_num int(9) NOT NULL DEFAULT '0',
  warnings int(10) UNSIGNED NOT NULL DEFAULT '0',
  lang int(9) NOT NULL DEFAULT '1',
  review_reply int(1) NOT NULL DEFAULT '0',
  reputation int(10) UNSIGNED NOT NULL DEFAULT '10',
  award varchar(350) NOT NULL default '',
  lastsearch_time varchar(15) NOT NULL default '',
  pm_emailed int(1) NOT NULL DEFAULT '0',
  pm_window int(1) NOT NULL DEFAULT '1',
  visitormessage int(1) NOT NULL DEFAULT '1',
  bday_day int(2) DEFAULT NULL,
  bday_month int(2) DEFAULT NULL,
  bday_year int(4) DEFAULT NULL,
  profile_viewers int(1) NOT NULL DEFAULT '1',
  style_sheet_profile longtext NOT NULL,
  send_security_code int(1) NOT NULL DEFAULT '0',
  send_security_error_login int(1) NOT NULL DEFAULT '0',
  profile_cover_photo varchar(355) NOT NULL default '',
  profile_cover_photo_position varchar(355) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_moderators (
  id int(9) NOT NULL AUTO_INCREMENT,
  section_id int(9) NOT NULL DEFAULT '0',
  member_id int(9) NOT NULL DEFAULT '0',
  username varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_online (
  id int(9) NOT NULL AUTO_INCREMENT,
  username text NOT NULL,
  path text NOT NULL,
  logged varchar(30) NOT NULL default '',
  user_id int(9) NOT NULL DEFAULT '0',
  user_ip varchar(30) NOT NULL default '',
  hide_browse int(1) NOT NULL DEFAULT '0',
  username_style varchar(255) NOT NULL default '',
  user_location text NOT NULL,
  subject_show int(1) NOT NULL DEFAULT '0',
  subject_id int(9) NOT NULL DEFAULT '0',
  last_move varchar(30) NOT NULL default '',
  section_id int(9) NOT NULL DEFAULT '0',
  is_bot int(1) NOT NULL DEFAULT '0',
  bot_name text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_pages (
  id int(9) NOT NULL AUTO_INCREMENT,
  title text NOT NULL,
  html_code longtext NOT NULL,
  sort int(9) NOT NULL DEFAULT '0',
  link text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_phrase_language (
  phraseid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  languageid smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  varname varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  fieldname varchar(20) NOT NULL DEFAULT '',
  text mediumtext,
  product varchar(25) NOT NULL DEFAULT '',
  dateline int(10) UNSIGNED NOT NULL DEFAULT '0',
  username varchar(100) NOT NULL DEFAULT '',
  version varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (phraseid)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_pm (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(200) NOT NULL default '',
  text text NOT NULL,
  user_from varchar(250) NOT NULL default '',
  user_to varchar(250) NOT NULL default '',
  user_read varchar(1) NOT NULL default '',
  alarm int(10) NOT NULL default '0',
  date varchar(100) NOT NULL default '',
  icon varchar(50) NOT NULL default '',
  folder varchar(200) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_pm_folder (
  id int(9) NOT NULL AUTO_INCREMENT,
  folder_name varchar(200) NOT NULL default '',
  username varchar(200) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_pm_lists (
  id int(9) NOT NULL AUTO_INCREMENT,
  list_username varchar(250) NOT NULL default '',
  username varchar(250) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_poll (
  id int(9) NOT NULL AUTO_INCREMENT,
  qus varchar(350) NOT NULL default '',
  answers text NOT NULL,
  subject_id int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_profile_view (
   profile_user_id mediumint(8) unsigned NOT NULL,
   viewer_user_id mediumint(8) unsigned NOT NULL,
   viewer_user_counter mediumint(8) unsigned NOT NULL,
   viewer_visit_time int(11) unsigned NOT NULL default '0',
  KEY (profile_user_id),
  KEY (viewer_user_id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_rating (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL default '',
  by_username varchar(250) NOT NULL default '',
  subject_title varchar(250) NOT NULL default '',
  ratingdate varchar(250) NOT NULL default '',
  subject_id int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_reply (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(355) NOT NULL default '',
  text mediumtext NOT NULL,
  writer varchar(250) NOT NULL default '',
  subject_id int(9) NOT NULL DEFAULT '0',
  stick int(1) NOT NULL DEFAULT '0',
  close int(1) NOT NULL DEFAULT '0',
  delete_topic int(1) NOT NULL DEFAULT '0',
  section int(9) NOT NULL DEFAULT '0',
  write_time varchar(15) NOT NULL default '',
  icon varchar(50) NOT NULL default '',
  action_by varchar(200) NOT NULL default '',
  attach_reply int(1) NOT NULL DEFAULT '0',
  actiondate varchar(50) NOT NULL default '',
  keepmeon int(1) NOT NULL DEFAULT '0',
  review_reply int(1) NOT NULL DEFAULT '0',
  last_time varchar(60) NOT NULL default '',
  reason_edit varchar(200) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_reputation (
  id int(9) NOT NULL AUTO_INCREMENT,
  by_username varchar(250) NOT NULL default '',
  username varchar(250) NOT NULL default '',
  subject_title varchar(250) NOT NULL default '',
  reputationdate varchar(250) NOT NULL default '',
  reply_id int(9) NOT NULL DEFAULT '0',
  subject_id int(9) NOT NULL DEFAULT '0',
  comments text NOT NULL,
  peg_count int(9) NOT NULL DEFAULT '0',
  reputationread smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_requests (
  id int(9) NOT NULL AUTO_INCREMENT,
  random_url varchar(350) NOT NULL default '',
  username varchar(250) NOT NULL default '',
  request_type int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_section (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(455) NOT NULL default '',
  section_describe text NOT NULL,
  parent int(9) NOT NULL DEFAULT '0',
  sort int(5) NOT NULL default '0',
  section_password varchar(50) NOT NULL default '',
  show_sig int(1) NOT NULL DEFAULT '0',
  use_power_code_allow int(1) NOT NULL DEFAULT '0',
  section_picture varchar(300) NOT NULL default '',
  sectionpicture_type int(1) NOT NULL DEFAULT '0',
  use_section_picture int(1) NOT NULL DEFAULT '0',
  linksection int(1) NOT NULL DEFAULT '0',
  linkvisitor int(9) NOT NULL DEFAULT '0',
  linksite varchar(300) NOT NULL default '',
  subject_order int(1) NOT NULL DEFAULT '0',
  hide_subject int(1) NOT NULL DEFAULT '0',
  last_writer varchar(255) NOT NULL default '',
  last_subject varchar(355) NOT NULL default '',
  last_subjectid int(9) NOT NULL DEFAULT '0',
  last_date varchar(11) NOT NULL default '',
  sec_section int(1) NOT NULL DEFAULT '0',
  sig_iteration int(1) NOT NULL DEFAULT '0',
  subject_num int(9) NOT NULL DEFAULT '0',
  reply_num int(9) NOT NULL DEFAULT '0',
  forums_cache longtext NOT NULL,
  moderators longtext NOT NULL,
  sectiongroup_cache longtext NOT NULL,
  footer text NOT NULL,
  header text NOT NULL,
  review_subject int(1) NOT NULL DEFAULT '0',
  icon varchar(50) NOT NULL default '',
  last_time varchar(60) NOT NULL default '',
  last_reply int(9) NOT NULL DEFAULT '0',
  last_berpage_nm int(9) NOT NULL DEFAULT '0',
  prefix_subject text NOT NULL,
  active_prefix_subject int(1) NOT NULL DEFAULT '0',
  forum_title_color varchar(7) NOT NULL default '',
  trash int(1) NOT NULL DEFAULT '0',
  subjects_review_num int(1) NOT NULL DEFAULT '0',
  replys_review_num int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_sectiongroup (
  id int(9) NOT NULL AUTO_INCREMENT,
  section_id int(9) NOT NULL DEFAULT '0',
  group_id int(9) NOT NULL DEFAULT '0',
  view_section int(1) NOT NULL DEFAULT '0',
  view_subject int(1) NOT NULL DEFAULT '0',
  download_attach int(1) NOT NULL DEFAULT '0',
  write_subject int(1) NOT NULL DEFAULT '0',
  write_reply int(1) NOT NULL DEFAULT '0',
  upload_attach int(1) NOT NULL DEFAULT '0',
  edit_own_subject int(1) NOT NULL DEFAULT '0',
  edit_own_reply int(1) NOT NULL DEFAULT '0',
  del_own_subject int(1) NOT NULL DEFAULT '0',
  del_own_reply int(1) NOT NULL DEFAULT '0',
  write_poll int(1) NOT NULL DEFAULT '0',
  vote_poll int(1) NOT NULL DEFAULT '0',
  no_posts int(1) NOT NULL DEFAULT '0',
  main_section int(1) NOT NULL DEFAULT '0',
  group_name varchar(355) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_smiles (
  id int(9) NOT NULL AUTO_INCREMENT,
  smile_short varchar(200) NOT NULL default '',
  smile_path text NOT NULL,
  smile_type int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_style (
  id int(9) NOT NULL AUTO_INCREMENT,
  style_title varchar(250) NOT NULL default '',
  style_on int(1) NOT NULL DEFAULT '0',
  style_order int(9) NOT NULL DEFAULT '0',
  style_path varchar(250) NOT NULL default '',
  image_path varchar(250) NOT NULL default '',
  template_path varchar(250) NOT NULL default '',
  cache_path varchar(250) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_subject (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(355) NOT NULL default '',
  text text NOT NULL,
  writer varchar(250) NOT NULL default '',
  section int(9) NOT NULL DEFAULT '0',
  write_date varchar(10) NOT NULL default '',
  stick int(1) NOT NULL DEFAULT '0',
  close int(1) NOT NULL DEFAULT '0',
  delete_topic int(1) NOT NULL DEFAULT '0',
  reply_number int(9) NOT NULL DEFAULT '0',
  visitor int(9) NOT NULL DEFAULT '0',
  write_time varchar(25) NOT NULL default '',
  native_write_time int(15) NOT NULL default '0',
  icon varchar(100) NOT NULL default '',
  subject_describe mediumtext NOT NULL,
  action_by varchar(400) NOT NULL default '',
  sec_subject int(1) NOT NULL DEFAULT '0',
  lastreply_cache text NOT NULL,
  last_replier varchar(255) NOT NULL default '',
  poll_subject int(1) NOT NULL DEFAULT '0',
  attach_subject int(1) NOT NULL DEFAULT '0',
  actiondate varchar(50) NOT NULL default '',
  tags_cache text NOT NULL,
  close_reason varchar(355) NOT NULL default '',
  delete_reason varchar(255) NOT NULL default '',
  review_subject int(1) NOT NULL DEFAULT '0',
  special int(1) NOT NULL DEFAULT '0',
  review_reply int(1) NOT NULL DEFAULT '0',
  rating int(9) NOT NULL DEFAULT '0',
  last_time varchar(60) NOT NULL default '',
  reason_edit varchar(400) NOT NULL default '',
  prefix_subject text NOT NULL,
  close_poll_subject int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_supermemberlogs (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL default '',
  edit_action varchar(455) NOT NULL default '',
  subject_title varchar(350) NOT NULL default '',
  subject_id int(9) NOT NULL DEFAULT '0',
  edit_date varchar(10) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_tags (
  id int(9) NOT NULL AUTO_INCREMENT,
  tag varchar(255) NOT NULL default '',
  number int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_tags_subject (
  id int(9) NOT NULL AUTO_INCREMENT,
  tag_id int(9) NOT NULL DEFAULT '0',
  subject_id int(9) NOT NULL DEFAULT '0',
  tag varchar(255) NOT NULL default '',
  subject_title varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_template (
  templateid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  styleid smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  template longtext,
  template_un longtext,
  templatetype enum('template','stylevar','css','replacement') NOT NULL DEFAULT 'template',
  dateline int(10) UNSIGNED NOT NULL DEFAULT '0',
  username varchar(100) NOT NULL DEFAULT '',
  version varchar(30) NOT NULL DEFAULT '',
  product varchar(25) NOT NULL DEFAULT '',
  sort int(5) NOT NULL DEFAULT '0',
  active smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (templateid)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_templates_edits (
  id int(9) NOT NULL AUTO_INCREMENT,
  addon_id int(9) NOT NULL DEFAULT '0',
  template_name varchar(250) NOT NULL default '',
  action varchar(250) NOT NULL default '',
  old_text longtext NOT NULL,
  text longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_today (
  id int(9) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL default '',
  user_id int(9) NOT NULL DEFAULT '0',
  user_date varchar(10) NOT NULL default '',
  logged varchar(30) NOT NULL default '',
  hide_browse int(1) NOT NULL DEFAULT '0',
  username_style varchar(455) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_toolbox (
  id int(9) NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL default '',
  tool_type int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_topicmod (
  id int(9) NOT NULL AUTO_INCREMENT,
  title varchar(350) NOT NULL default '',
  enabled tinyint(1) NOT NULL DEFAULT '0',
  state varchar(10) NOT NULL DEFAULT 'leave',
  pin varchar(10) NOT NULL DEFAULT 'leave',
  move smallint(5) NOT NULL DEFAULT '0',
  move_link tinyint(1) NOT NULL DEFAULT '0',
  title_st varchar(350) NOT NULL DEFAULT '',
  title_end varchar(350) NOT NULL DEFAULT '',
  reply tinyint(1) NOT NULL DEFAULT '0',
  reply_content text NOT NULL,
  approve tinyint(1) NOT NULL DEFAULT '0',
  forums text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_userrating (
  id int(9) NOT NULL AUTO_INCREMENT,
  rating varchar(350) NOT NULL default '',
  posts int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_usertitle (
  id int(9) NOT NULL AUTO_INCREMENT,
  usertitle varchar(200) NOT NULL default '',
  posts int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_visitor (
  id int(9) NOT NULL AUTO_INCREMENT,
  lang_id int(9) NOT NULL DEFAULT '0',
  ip varchar(100) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_visitormessage (
  id int(9) NOT NULL AUTO_INCREMENT,
  userid int(10) UNSIGNED NOT NULL DEFAULT '0',
  postuserid int(10) UNSIGNED NOT NULL DEFAULT '0',
  postusername varchar(100) NOT NULL DEFAULT '',
  dateline int(10) UNSIGNED NOT NULL DEFAULT '0',
  pagetext mediumtext,
  ipaddress varchar(20) NOT NULL DEFAULT '0',
  messageread smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_vote (
  id int(9) NOT NULL AUTO_INCREMENT,
  poll_id int(9) NOT NULL DEFAULT '0',
  member_id int(9) NOT NULL DEFAULT '0',
  answer_number int(9) NOT NULL DEFAULT '0',
  votes int(9) NOT NULL DEFAULT '0',
  subject_id int(9) NOT NULL DEFAULT '0',
  user_ip varchar(100) NOT NULL default '',
  username varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_warnlog (
  id int(9) NOT NULL AUTO_INCREMENT,
  warn_from varchar(200) NOT NULL default '',
  warn_to varchar(200) NOT NULL default '',
  warn_text longtext NOT NULL,
  warn_date varchar(200) NOT NULL default '',
  warn_liftdate varchar(200) NOT NULL default '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$tables[] = "CREATE TABLE pbb_mention (
  id int(10) NOT NULL AUTO_INCREMENT,
  user_mention_about_you varchar(255) NOT NULL default '',
  you varchar(255) NOT NULL default '',
  topic_id int(10) UNSIGNED NOT NULL DEFAULT '0',
  reply_id int(10) UNSIGNED NOT NULL DEFAULT '0',
  profile_id int(10) UNSIGNED NOT NULL DEFAULT '0',
  date varchar(200) NULL default '',
  user_read int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

