<?php
$now = TIME_NOW;
$inserts[] = "INSERT INTO pbb_avatar (id, avatar_path) VALUES
(1, 'look/images/avatar/Aeroplane.gif'),
(2, 'look/images/avatar/Fish.gif'),
(3, 'look/images/avatar/Golfball.gif'),
(4, 'look/images/avatar/Green-haze.gif'),
(5, 'look/images/avatar/Leaf.gif'),
(6, 'look/images/avatar/Melon.gif'),
(7, 'look/images/avatar/Meow.gif'),
(8, 'look/images/avatar/Orange-sticks.gif'),
(9, 'look/images/avatar/Pink-sea.gif'),
(10, 'look/images/avatar/Woof.gif'),
(11, 'look/images/avatar/Wub.gif'),
(12, 'look/images/avatar/Crazyman.jpg'),
(13, 'look/images/avatar/Dolphin.jpg'),
(14, 'look/images/avatar/Hammer-man.jpg'),
(15, 'look/images/avatar/PowerBB1.jpg'),
(16, 'look/images/avatar/PowerBB.jpg'),
(17, 'look/images/avatar/Whale.jpg'),
(18, 'look/images/avatar/coof.jpg');";


$inserts[] = "INSERT INTO pbb_blocks (id, title, text, place_block, sort, active) VALUES
(1, 'الأقسام الرئيسية', '{template}portal_main_categories{/template}', 'left', 1, 1),
(2, 'إحصائيات', '{template}portal_static{/template}', 'left', 2, 1),
(3, 'الساعة', '{template}portal_clock{/template}', 'left', 3, 1),
(4, 'التقويم', '{template}portal_calendar{/template}', 'center', 2, 1),
(5, 'آخر الأخبار', '{template}portal_last_news{/template}', 'center', 1, 1),
(6, 'القائمة الرئيسية', '{template}portal_main_menu{/template}', 'right', 1, 1),
(7, 'المتواجدين الآن', '{template}portal_online{/template}', 'right', 2, 1),
(8, 'آخر المشاركات', '{template}portal_latest_posts{/template}', 'right', 3, 1);";


$inserts[] = "INSERT INTO pbb_email_msg (id, title, text) VALUES
(1, 'طلب تغيير كلمة المرور', 'مرحبا بك [MySBB]username[/MySBB]<br /><br />لقد قمت بطلب تغيير كلمة مرورك الخاصه في المنتدى , لاكمال الخطوات و تغييرها يرجى الضغط على هذه الوصله : <a href=\"[MySBB]change_url[/MySBB]\">[MySBB]change_url[/MySBB]</a><br /><br />اذا لم تطلب تغيير كلمة المرور يرجى الضغط على الوصله التاليه لكي يتم إلغاء الطلب : <a href=\"[MySBB]cancel_url[/MySBB]\">[MySBB]cancel_url[/MySBB]</a><br /><br />مع تحيات ادارة [MySBB]board_title[/MySBB]'),
(2, 'طلب تغيير بريدك الالكتروني', 'مرحبا بك [MySBB]username[/MySBB]<br /><br />لقد قمت بطلب تغيير بريدك الالكتروني في المنتدى , لاكمال الخطوات و تغييره يرجى الضغط على هذه الوصله : <a href=\"[MySBB]change_url[/MySBB]\">[MySBB]change_url[/MySBB]</a><br /><br />اذا لم تطلب تغيير البريد الالكتروني يرجى الضغط على الوصله التاليه لكي يتم إلغاء الطلب : <a href=\"[MySBB]cancel_url[/MySBB]\">[MySBB]cancel_url[/MySBB]</a><br /><br />مع تحيات ادارة [MySBB]board_title[/MySBB]'),
(3, 'تبليغ عن موضوع مخالف', '<p>مرحبا بك عزيزي المدير , لقد قام احد الاعضاءالزوار بالتبليغ عن الموضوع التالي : <a href=\"[MySBB]subject_url[/MySBB]\">[MySBB]subject_url[/MySBB]</a></p>'),
(4, 'تفعيل العضوية', 'مرحباً بك  [MySBB]username[/MySBB] <br /><br />نشكرك على التسجيل في [MySBB]board_title[/MySBB] , يرجى تفعيل العضويه لكي تتمكن من المشاركه في المنتدى و تنتقل إلى مجموعة الاعضاء<br /><br />لتفعيل عضويتك يرجى الضغط على الرابط التالي :<br /> <a href=\"[MySBB]url[/MySBB]\">[MySBB]url[/MySBB]</a><br /><br />مع تحيات إدارة [MySBB]board_title[/MySBB]');";


$inserts[] = "INSERT INTO pbb_ex (id, Ex, max_size, mime_type) VALUES
(1, '.zip', '990000', ''),
(2, '.txt', '90000', ''),
(3, '.jpg', '90000', ''),
(4, '.gif', '90000', ''),
(5, '.bmp', '90000', ''),
(6, '.doc', '90000', ''),
(7, '.pdf', '90000', ''),
(8, '.png', '90000', ''),
(9, '.psd', '90000', ''),
(10, '.wav', '90000', ''),
(11, '.mp4', '990000', ''),
(12, '.mp3', '990000', ''),
(13, '.jpeg', '90000', ''),
(14, '.rar', '990000', '');";


$inserts[] = "INSERT INTO pbb_group (id, title, username_style, user_title, forum_team, banned, view_section, download_attach, download_attach_number, write_subject, write_reply, upload_attach, upload_attach_num, edit_own_subject, edit_own_reply, del_own_subject, del_own_reply, write_poll, vote_poll, no_posts, use_pm, send_pm, resive_pm, max_pm, min_send_pm, sig_allow, sig_len, group_mod, del_subject, del_reply, edit_subject, edit_reply, stick_subject, unstick_subject, move_subject, close_subject, usercp_allow, admincp_allow, search_allow, memberlist_allow, vice, show_hidden, view_usernamestyle, usertitle_change, onlinepage_allow, allow_see_offstyles, admincp_section, admincp_option, admincp_member, admincp_membergroup, admincp_membertitle, admincp_admin, admincp_adminstep, admincp_subject, admincp_database, admincp_fixup, admincp_ads, admincp_template, admincp_adminads, admincp_attach, admincp_page, admincp_block, admincp_style, admincp_toolbox, admincp_smile, admincp_icon, admincp_avater, group_order, admincp_contactus, send_warning, can_warned, hide_allow, visitormessage, see_who_on_topic, reputation_number, admincp_chat, admincp_extrafield, admincp_lang, admincp_emailed, admincp_warn, admincp_award, admincp_multi_moderation, view_subject, review_subject, review_reply, view_action_edit, topic_day_number, groups_security, profile_photo) VALUES
(1, 'مدير المنتدى', '<strong><em><span style=\"color: #840000;\">[username]</span></em></strong>', 'المشرف العام', 1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 2000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 100, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1, 1),
(2, 'مراقب عام', '<strong><span style=\"color: #FF0000;\">[username]</span></strong>', 'المراقب العام', 1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 2000, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0, 1, 0, 1, 1, 1, 50, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 1),
(3, 'المشرفين', '<strong><span style=\"color: #0000FF;\">[username]</span></strong>', 'مشرف', 1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 2000, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 1, 0, 1, 1, 1, 30, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 1),
(4, 'الأعضاء', '<span style=\"color: #000000;\">[username]</span>', 'عضو', 0, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 2000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 0, 0, 1, 1, 1, 1, 10, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 1),
(5, 'الأعضاء الغير مفعلين', '<span style=\"color: #008080;\">[username]</span>', 'غير مفعل', 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 1, 0, 1, 1, 10, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0),
(6, 'الموقوفين', '<span style=\"color: #FF0000;\">[username]</span>', 'موقوف', 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(7, 'الزوار', '[username]', 'زائر', 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0),
(8, 'المراقبين', '<strong><span style=\"color: #800000;\">[username]</span></strong>', 'مراقب', 1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 3000, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 1, 0, 1, 1, 1, 50, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 1);";

$smiles_cache = 'W3siaWQiOiIxIiwic21pbGVfc2hvcnQiOiI6KSIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wvc21pbGUuZ2lmIn0seyJpZCI6IjIiLCJzbWlsZV9zaG9ydCI6IjspIiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC93aW5rXzMuZ2lmIn0seyJpZCI6IjMiLCJzbWlsZV9zaG9ydCI6Ijpyb2xsOiIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wvcm9sbGV5ZXMuZ2lmIn0seyJpZCI6IjQiLCJzbWlsZV9zaG9ydCI6IjpEIiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC9iaWdncmluMi5naWYifSx7ImlkIjoiNSIsInNtaWxlX3Nob3J0IjoiOmNvb2w6Iiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC9jb29sLmdpZiJ9LHsiaWQiOiI2Iiwic21pbGVfc2hvcnQiOiI6bG9sOiIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wvbGF1Z2guZ2lmIn0seyJpZCI6IjciLCJzbWlsZV9zaG9ydCI6IjooIiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC9zYWQuZ2lmIn0seyJpZCI6IjgiLCJzbWlsZV9zaG9ydCI6IjptYWQ6Iiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC9tYWRfMS5naWYifSx7ImlkIjoiOSIsInNtaWxlX3Nob3J0IjoiOiMiLCJzbWlsZV9wYXRoIjoibG9va1wvaW1hZ2VzXC9zbWlsZXNcL2JsdXNoaW5nLmdpZiJ9LHsiaWQiOiIxMCIsInNtaWxlX3Nob3J0IjoiOkBAOiIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wvYmxpbmsuZ2lmIn0seyJpZCI6IjExIiwic21pbGVfc2hvcnQiOiI6eWVzOiIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wveWVzLmdpZiJ9LHsiaWQiOiIxMiIsInNtaWxlX3Nob3J0IjoiOm5vOiIsInNtaWxlX3BhdGgiOiJsb29rXC9pbWFnZXNcL3NtaWxlc1wvbm9fMS5naWYifSx7ImlkIjoiMTMiLCJzbWlsZV9zaG9ydCI6IjpobW06Iiwic21pbGVfcGF0aCI6Imxvb2tcL2ltYWdlc1wvc21pbGVzXC9nLmdpZiJ9XQ==';
$users_ratings_cache = 'W3siaWQiOiIxIiwicmF0aW5nIjoibG9va1wvaW1hZ2VzXC9yYXRpbmdcL3JhdGluZ18wLmdpZiIsInBvc3RzIjoiMTAifSx7ImlkIjoiMiIsInJhdGluZyI6Imxvb2tcL2ltYWdlc1wvcmF0aW5nXC9yYXRpbmdfMS5naWYiLCJwb3N0cyI6IjEwMCJ9LHsiaWQiOiIzIiwicmF0aW5nIjoibG9va1wvaW1hZ2VzXC9yYXRpbmdcL3JhdGluZ18yLmdpZiIsInBvc3RzIjoiMjAwIn0seyJpZCI6IjQiLCJyYXRpbmciOiJsb29rXC9pbWFnZXNcL3JhdGluZ1wvcmF0aW5nXzMuZ2lmIiwicG9zdHMiOiI0MDAifSx7ImlkIjoiNSIsInJhdGluZyI6Imxvb2tcL2ltYWdlc1wvcmF0aW5nXC9yYXRpbmdfNC5naWYiLCJwb3N0cyI6IjYwMCJ9LHsiaWQiOiI2IiwicmF0aW5nIjoibG9va1wvaW1hZ2VzXC9yYXRpbmdcL3JhdGluZ181LmdpZiIsInBvc3RzIjoiMTAwMCJ9XQ==';
$users_titles_cache = 'W3siaWQiOiIxIiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQ4IFx1MDYyY1x1MDYyZlx1MDY0YVx1MDYyZlx0IiwicG9zdHMiOiIyIn0seyJpZCI6IjIiLCJ1c2VydGl0bGUiOiJcdTA2MzlcdTA2MzZcdTA2NDBcdTA2NDggXHUwNjQ1XHUwNjM0XHUwNjI3XHUwNjMxXHUwNjQzIiwicG9zdHMiOiIxMDAifSx7ImlkIjoiMyIsInVzZXJ0aXRsZSI6Ilx1MDYzOVx1MDYzNlx1MDY0OCBcdTA2NDFcdTA2MzlcdTA2MjdcdTA2NDRcdCIsInBvc3RzIjoiMjUwIn0seyJpZCI6IjQiLCJ1c2VydGl0bGUiOiJcdTA2MzlcdTA2MzZcdTA2NDBcdTA2NDggXHUwNjQ2XHUwNjM0XHUwNjRhXHUwNjM3IiwicG9zdHMiOiI1MDAifSx7ImlkIjoiNSIsInVzZXJ0aXRsZSI6Ilx1MDYzOVx1MDYzNlx1MDY0MFx1MDY0OCBcdTA2NDVcdTA2MmFcdTA2NDVcdTA2NGFcdTA2MzIiLCJwb3N0cyI6Ijc1MCJ9LHsiaWQiOiI2IiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQ4IFx1MDY0NVx1MDYyYVx1MDYyM1x1MDY0NFx1MDY0MiIsInBvc3RzIjoiMTAwMCJ9LHsiaWQiOiI3IiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQwXHUwNjQ4IFx1MDYyOFx1MDY0MFx1MDYyN1x1MDYzMVx1MDYzMiIsInBvc3RzIjoiMTUwMCJ9LHsiaWQiOiI4IiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQwXHUwNjQ4IFx1MDYzM1x1MDY0OFx1MDYyOFx1MDYzMSIsInBvc3RzIjoiMjAwMCJ9LHsiaWQiOiI5IiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQwXHUwNjQ4IFx1MDYzM1x1MDYyYVx1MDY0MFx1MDYyN1x1MDYzMSIsInBvc3RzIjoiMzAwMCJ9LHsiaWQiOiIxMCIsInVzZXJ0aXRsZSI6Ilx1MDYzOVx1MDYzNlx1MDY0OCBcdTA2MzNcdTA2NDhcdTA2MjhcdTA2MzEgXHUwNjMzXHUwNjJhXHUwNjI3XHUwNjMxIiwicG9zdHMiOiI1MDAwIn0seyJpZCI6IjExIiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQ4IFx1MDY0MVx1MDYzNlx1MDY0YSIsInBvc3RzIjoiODAwMCJ9LHsiaWQiOiIxMiIsInVzZXJ0aXRsZSI6Ilx1MDYzOVx1MDYzNlx1MDY0OCBcdTA2MzBcdTA2NDdcdTA2MjhcdTA2NGEiLCJwb3N0cyI6IjExMDAwIn0seyJpZCI6IjEzIiwidXNlcnRpdGxlIjoiXHUwNjM5XHUwNjM2XHUwNjQ4IFx1MDY0NVx1MDYyN1x1MDYzM1x1MDY0YSIsInBvc3RzIjoiMTQwMDAifSx7ImlkIjoiMTQiLCJ1c2VydGl0bGUiOiJcdTA2MjdcdTA2NDVcdTA2NGFcdTA2MzEgXHUwNjI3XHUwNjQ0XHUwNjQ1XHUwNjQ2XHUwNjJhXHUwNjJmXHUwNjQ5IiwicG9zdHMiOiIxNzAwMCJ9LHsiaWQiOiIxNSIsInVzZXJ0aXRsZSI6Ilx1MDYyN1x1MDY0NVx1MDYyOFx1MDYzMVx1MDYyN1x1MDYzN1x1MDY0OFx1MDYzMSBcdTA2MjdcdTA2NDRcdTA2NDVcdTA2MmFcdTA2MmZcdTA2NDkiLCJwb3N0cyI6IjUwMDAwIn1d';
$last_posts_cache = 'a:10:{i:0;a:18:{s:2:"id";s:1:"1";s:7:"section";s:1:"2";s:6:"writer";s:5:"admin";s:5:"title";s:45:"مرحباً بك في منتديات PBBoard";s:12:"review_reply";s:1:"0";s:10:"write_time";s:10:"1710522994";s:4:"icon";s:24:"look/images/icons/i1.gif";s:7:"visitor";s:1:"0";s:12:"last_replier";s:0:"";s:11:"sec_subject";s:1:"0";s:14:"review_subject";s:1:"0";s:14:"prefix_subject";s:0:"";s:17:"native_write_time";s:10:"1710522994";s:7:"special";s:1:"0";s:14:"attach_subject";s:1:"0";s:14:"last_writer_id";s:1:"1";s:11:"avater_path";s:0:"";s:20:"username_style_cache";s:68:"<strong><em><span style="color: #800000;">admin</span></em></strong>";}i:1;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:2;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:3;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:4;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:5;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:6;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:7;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:8;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}i:9;a:18:{s:2:"id";N;s:7:"section";N;s:6:"writer";N;s:5:"title";N;s:12:"review_reply";N;s:10:"write_time";N;s:4:"icon";N;s:7:"visitor";N;s:12:"last_replier";N;s:11:"sec_subject";N;s:14:"review_subject";N;s:14:"prefix_subject";N;s:17:"native_write_time";N;s:7:"special";N;s:14:"attach_subject";N;s:14:"last_writer_id";N;s:11:"avater_path";N;s:20:"username_style_cache";N;}}';

$inserts[] = "INSERT INTO pbb_info (id, var_name, value) VALUES
(1, 'title', ''),
(2, 'show_onlineguest', '0'),
(3, 'perpage', '12'),
(4, 'subject_perpage', '12'),
(5, 'show_subject_all', '0'),
(6, 'send_email', ''),
(7, 'avatar_perpage', '18'),
(8, 'admin_email', ''),
(9, 'MySBB_version', '3.0.4'),
(10, 'Sat', '1'),
(11, 'Sun', '1'),
(12, 'Mon', '1'),
(13, 'Tue', '1'),
(14, 'Wed', '1'),
(15, 'Thu', '1'),
(16, 'Fri', '1'),
(17, 'fastreply_allow', '1'),
(18, 'download_path', 'download'),
(19, 'def_group', '4'),
(20, 'adef_group', '4'),
(21, 'def_style', '1'),
(22, 'board_close', '0'),
(23, 'board_msg', ''),
(24, 'use_list', 'xa-4c4700355e83e612'),
(25, 'supermember_logs', '0'),
(26, 'page_max', '5'),
(27, 'reg_o', '1'),
(28, 'captcha_o', '1'),
(29, 'time_out', '1440'),
(30, 'samesubject_show', '1'),
(31, 'reg_less_num', '3'),
(32, 'reg_max_num', '25'),
(33, 'reg_pass_min_num', '5'),
(34, 'reg_pass_max_num', '25'),
(35, 'post_text_min', '5'),
(36, 'post_text_max', '30000'),
(37, 'post_title_min', '4'),
(38, 'post_title_max', '99'),
(39, 'upload_avatar', '1'),
(40, 'max_avatar_width', '460'),
(41, 'max_avatar_height', '650'),
(42, 'reg_close', '0'),
(43, 'msg_title_temp', ''),
(44, 'msg_content_temp', ''),
(45, 'confirm_on_change_mail', '0'),
(46, 'confirm_on_change_pass', '0'),
(47, 'allow_avatar', '1'),
(48, 'allow_apsent', '1'),
(49, 'ads_num', '0'),
(50, 'smiles_cache', '{$smiles_cache}'),
(51, 'forums_cache', ''),
(52, 'subforums_cache', ''),
(53, 'sectiongroup_cache', ''),
(54, 'subject_number', '1'),
(55, 'reply_number', '0'),
(56, 'member_number', '1'),
(57, 'last_member', ''),
(58, 'last_member_id', '1'),
(59, 'floodctrl', '30'),
(60, 'description', 'هذا المنتدى يستخدم برنامج PBBoard لمعرفة المزيد عنه اذهب إلى https://pbboard.info'),
(61, 'keywords', 'منتدى,منتديات,PBBoard,Power,bulletin,board,arab,forums,forum,pbboard.info'),
(62, 'content_language', 'ar'),
(63, 'content_dir', 'rtl'),
(64, 'lasts_posts_bar_dir', 'right'),
(65, 'special_bar_dir', 'right'),
(66, 'max_online_date', ''),
(67, 'chat_bar_dir', 'out'),
(68, 'def_lang', '1'),
(69, 'charset', 'utf-8'),
(70, 'toolbox_show', '1'),
(71, 'smiles_show', '1'),
(72, 'icons_show', '1'),
(73, 'title_quote', '1'),
(74, 'close_stick_activate', '1'),
(75, 'timestamp', '0'),
(76, 'timesystem', 'h:i A'),
(77, 'online_now_section', '1'),
(78, 'online_now_subject', '1'),
(79, 'resize_imagesAllow', '1'),
(80, 'default_imagesW', '400'),
(81, 'default_imagesH', '600'),
(82, 'wordwrap', '50'),
(83, 'create_date', '".$now."'),
(84, 'icon_path', 'look/images/icons/'),
(85, 'sectiongroup_number', '0'),
(86, 'subsections_number', '0'),
(87, 'sections_number', '0'),
(88, 'smiles_number', '0'),
(89, 'today_date_cache', '0'),
(90, 'today_number_cache', '0'),
(91, 'adress_bar_separate', '&raquo;'),
(92, 'ajax_search', '0'),
(93, 'ajax_register', '0'),
(94, 'ajax_freply', '0'),
(95, 'get_group_username_style', '0'),
(96, 'ajax_moderator_options', '0'),
(97, 'reg_Sat', '1'),
(98, 'reg_Sun', '1'),
(99, 'reg_Mon', '1'),
(100, 'reg_Tue', '1'),
(101, 'reg_Wed', '1'),
(102, 'reg_Thu', '1'),
(103, 'reg_Fri', '1'),
(104, 'admin_notes', ''),
(105, 'pm_feature', '1'),
(106, 'default_avatar', 'default_avatar.gif'),
(107, 'no_describe', '1'),
(108, 'no_moderators', '1'),
(109, 'no_sub', '1'),
(110, 'warning_number_to_ban', '10'),
(111, 'members_send_pm', '7'),
(112, 'activate_last_static_list', '0'),
(113, 'last_static_num', '5'),
(114, 'last_posts_static_num', '10'),
(115, 'forum_id_not_in_static', '0'),
(116, 'activate_lasts_posts_bar', '0'),
(117, 'forum_id_not_in_lasts_posts_bar', '0'),
(118, 'lasts_posts_bar_num', '10'),
(119, 'activate_special_bar', '0'),
(120, 'subject_describe_show', '0'),
(121, 'rules', 'عليك قراءة هذه القوانين حتى لا تتعرض للتوقيف أو المخالفة: \n - يمنع طرح المواضيع والمشاركات بقصد التهجم نحو شخص أو بلد حكومةً أو شعب أوالتعرض للاديان. \n - يمنع استخدام إسم غير لائق لعضويتك عند التسجيل أو التسجيل بحروف مبهمة أو أرقام أو بريد الكتروني. \n - يمنع طرح مواضيع الاختراق والهكرز , أو طرح مواضيع بها مواقع مخالفة بقصد اختراقها أو إيقافها , منعاً لانتشارها .  \n - يجب الالتزام بآداب الحديث والحوار وعدم التعرض لأي شخص بالإهانة أو التجريح. \n بعد قراءتك الشروط والتسجيل فأي مخالفة تصدر منك سيتم الاتخاذ الاجراء اللازم بحقك أو إيقاف عضويتك'),
(122, 'censorwords', 'equiv'),
(123, 'activate_closestick', '1'),
(124, 'reputationallw', '1'),
(125, 'show_reputation_number', '10'),
(126, 'show_rating_num_max', '5'),
(127, 'rating_show', '1'),
(128, 'max_online', '1'),
(129, 'smiles_nm', '12'),
(130, 'random_ads', '0'),
(131, 'show_ads', '1'),
(132, 'show_online_list_today', '0'),
(133, 'show_list_last_5_posts_member', '0'),
(134, 'last_subject_writer_nm', '5'),
(135, 'last_subject_writer_not_in', '0'),
(136, 'activate_chat_bar', '0'),
(137, 'chat_message_num', '10'),
(138, 'chat_num_mem_posts', '5'),
(139, 'chat_num_characters', '650'),
(140, 'chat_hide_country', '0'),
(141, 'characters_keyword_search', '2'),
(142, 'flood_search', '40'),
(143, 'allowed_emailed', '1'),
(144, 'allowed_emailed_pm', '1'),
(145, 'rewriterule', '1'),
(146, 'sitemap', '1'),
(147, 'allowed_powered', '0'),
(148, 'visitor_message_chars', '8000'),
(149, 'active_addons', '1'),
(150, 'haid_links_for_guest', '0'),
(151, 'guest_message_for_haid_links', 'لمشاهدة الروابط يلزمك التسجيل'),
(152, 'add_tags_automatic', '1'),
(153, 'mailer', 'phpmail'),
(154, 'smtp_secure', 'TLS'),
(155, 'smtp_port', '25'),
(156, 'smtp_server', ''),
(157, 'smtp_username', ''),
(158, 'smtp_password', ''),
(159, 'mor_hours_online_today', '0'),
(160, 'mor_seconds_online', '300'),
(161, 'sub_columns_number', '2'),
(162, 'icon_columns_number', '6'),
(163, 'smil_columns_number', '3'),
(164, 'avatar_columns_number', '6'),
(165, 'icons_numbers', '12'),
(166, 'datesystem', 'd-m-Y'),
(167, 'timeoffset', 'Asia/Riyadh'),
(168, 'active_forum_online_number', '0'),
(169, 'active_birth_date', '1'),
(170, 'active_worms_pbb', '0'),
(171, 'shelluser', ''),
(172, 'shellpswd', ''),
(173, 'shelladminemail', ''),
(174, 'active_like_facebook', '0'),
(175, 'active_add_this', '0'),
(176, 'active_visitor_message', '1'),
(177, 'active_friend', '1'),
(178, 'active_archive', '1'),
(179, 'active_calendar', '1'),
(180, 'active_send_admin_message', '1'),
(181, 'active_reply_today', '1'),
(182, 'active_subject_today', '1'),
(183, 'active_static', '1'),
(184, 'active_team', '1'),
(185, 'active_rss', '1'),
(186, 'title_portal', 'PBB Portal'),
(187, 'active_portal', '1'),
(188, 'portal_section_news', '2'),
(189, 'portal_columns', '3'),
(190, 'portal_news_num', '4'),
(191, 'portal_news_along', '300'),
(192, 'cssprefs', 'default'),
(193, 'captcha_type', 'captcha_IMG'),
(194, 'questions', ''),
(195, 'answers', ''),
(196, 'adsense_limited_sections', '9,8'),
(197, 'activ_welcome_message', '0'),
(198, 'welcome_message_text', 'مرحباً بك معنا يا {username} في {title} الرجاء الإطلاع على قوانين المنتدى على الرابط التالي {rules} قبل المشاركة، ونتمنى لك قضاء وقت مفيد وممتع معنا !'),
(199, 'welcome_message_mail_or_private', '3'),
(200, 'num_entries_error', '7'),
(201, 'style_block_latest_news', '1'),
(202, 'search_engine_spiders', 'Googlebot,Yahoo!,msnbot,Googlebot-Image,Gaisbot,GalaxyBot,msnbot,Rambler,Bingbot,Slurp,DuckDuckBot,Baiduspider,Baiduspider-image,Baiduspider-ads,YandexBot,Sogou,facebot,ia_archiver,Googlebot-Mobile,Mediapartners-Google,AdsBot-Google,Mediapartners-Google,AdsBot-Google-Mobile-Apps,DuckDuckGo,Ask,Naver,Ecosia,AOL,Seznam,Qwant,Daum,Baidu,DataForSeoBot,PetalBot,SemrushBot,bingbot,DotBot'),
(203, 'users_ratings_cache', '{$users_ratings_cache}'),
(204, 'users_titles_cache', '{$users_titles_cache}'),
(205, 'users_security', '1'),
(206, 'sidebar_list_active', '1'),
(207, 'sidebar_list_align', 'left'),
(208, 'sidebar_list_pages', 'index'),
(209, 'sidebar_list_width', '26'),
(210, 'sidebar_list_exclusion_forums', '254,545'),
(211, 'sidebar_list_content', '{template}login_box{/template}\n{template}whatis_new{/template}'),
(212, 'last_posts_cache', '{$last_posts_cache}'),
(213, 'last_time_cache', ".$now."),
(214, 'groups_cache', ''),
(215, 'custom_bbcodes_list_cache', ''),
(216, 'rss_feeds_cache', ''),
(217, 'extrafields_cache', ''),
(218, 'awards_cache', ''),
(219, 'pages_cache', ''),
(220, 'adsenses_cache', ''),
(221, 'languages_list_cache', ''),
(222, 'styles_list_cache', ''),
(223, 'p_cache', ''),
(224, 'last_time_updates', ".$now."),
(225, 'mobile_style_id', '1'),
(226, 'mention_active', '1'),
(227, 'mention_exusergroups', '6,5'),
(228, 'mention_exusers', '0'),
(229, 'mention_exforum', '99'),
(230, 'download_subject', '0'),
(231, 'print_subject', '1'),
(232, 'send_subject_to_friend', '1'),
(233, 'adsense_limited_usergroups', '0');";

$inserts[] = "INSERT INTO pbb_lang (id, lang_title, lang_order, lang_on, lang_path) VALUES
(1, 'Arabic', 1, 1, 'rtl');";

$inserts[] = "INSERT INTO pbb_section (id, title, section_describe, parent, sort, section_password, show_sig, use_power_code_allow, section_picture, sectionpicture_type, use_section_picture, linksection, linkvisitor, linksite, subject_order, hide_subject, last_writer, last_subject, last_subjectid, last_date, sec_section, sig_iteration, subject_num, reply_num, forums_cache, moderators, sectiongroup_cache, footer, header, review_subject, icon, last_time, last_reply, last_berpage_nm, prefix_subject, active_prefix_subject, forum_title_color, trash, subjects_review_num, replys_review_num) VALUES
(1, 'القسم التجريبي', '', 0, 0, '', 0, 0, '', 0, 0, 0, 0, '', 0, 0, 'admin', 'مرحباً بك في منتديات PBBoard', 1, '".$now."', 0, 0, 0, 0, '', '[]', '', '', '', 0, 'look/images/icons/i1.gif', '".$now."', 0, 0, '', 0, '', 0, 0, 0),
(2, 'المنتدى التجريبي', 'منتدي تجريبي يمكنك حذفه وقتما تشاء ', 1, 1, '', 1, 1, '', 2, 0, 0, 0, '', 1, 0, 'admin', 'مرحباً بك في منتديات PBBoard', 1, '".$now."', 0, 0, 1, 0, '', '[]', '', '', '', 0, 'look/images/icons/i1.gif', '".$now."', 0, 0, '', 0, '', 0, 0, 0);";

$inserts[] = "INSERT INTO pbb_subject (id, title, text, writer, section, write_date, stick, close, delete_topic, reply_number, visitor, write_time, native_write_time, icon, subject_describe, action_by, sec_subject, lastreply_cache, last_replier, poll_subject, attach_subject, actiondate, tags_cache, close_reason, delete_reason, review_subject, special, review_reply, rating, last_time, reason_edit, prefix_subject, close_poll_subject) VALUES
(1, 'مرحباً بك في منتديات PBBoard', '\r\nالسلام عليكم ورحمة الله وبركاته ,,\r\n\r\nتهانينا  بنجاح تثبيت نسخة PBBoard الخاصة بك وتكوينها بشكل صحيح.\r\n\r\nتشكرك مجموعة PBBoard على دعمك في تثبيت برنامجنا ونأمل أن نراك في [url=https://pbboard.info/forums/]منتدى دعم PBBoard الرسمي[/url] إذا كنت بحاجة إلى مساعدة أو ترغب في أن تصبح جزء من مجتمع PBBoard.\r\n\r\nيمكنك الآن الاستمتاع بمنتداك الجديد من PBBoard.\r\n\r\nبالتوفيق :)\r\n\r\n', 'admin', 2, '', 0, 0, 0, 0, 0, '".$now."', ".$now.", 'look/images/icons/i1.gif', '', '', 0, '{\"1\":{\"user_id\":\"1\"},\"2\":{\"avater_path\":\"\"},\"3\":{\"username_style\":\"<strong><em><span style=\\\"color: #800000;\\\">admin<\\/span><\\/em><\\/strong>\"},\"4\":{\"section_title\":\"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062f\\u0649 \\u0627\\u0644\\u062a\\u062c\\u0631\\u064a\\u0628\\u064a\"}}', '', 0, 0, '', 'true', '', '', 0, 0, 0, 0, '', '', '', 0);";

$inserts[] = "INSERT INTO pbb_sectiongroup (id, section_id, group_id, view_section, view_subject, download_attach, write_subject, write_reply, upload_attach, edit_own_subject, edit_own_reply, del_own_subject, del_own_reply, write_poll, vote_poll, no_posts, main_section, group_name) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'مدير المنتدى'),
(2, 1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'المراقب العام'),
(3, 1, 3, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 'المشرفين'),
(4, 1, 4, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 'الأعضاء'),
(5, 1, 5, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 'الأعضاء الغير مفعلين'),
(6, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'الموقوفين'),
(7, 1, 7, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'الزوار'),
(8, 1, 8, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'المراقبين'),
(9, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'مدير المنتدى'),
(10, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'المراقب العام'),
(11, 2, 3, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 'المشرفين'),
(12, 2, 4, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 'الأعضاء'),
(13, 2, 5, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'الأعضاء الغير مفعلين'),
(14, 2, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'الموقوفين'),
(15, 2, 7, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'الزوار'),
(16, 2, 8, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'المراقبين');";

$inserts[] = "INSERT INTO pbb_smiles (id, smile_short, smile_path, smile_type) VALUES
(1, ':)', 'look/images/smiles/smile.gif', 0),
(2, ';)', 'look/images/smiles/wink_3.gif', 0),
(3, ':roll:', 'look/images/smiles/rolleyes.gif', 0),
(4, ':D', 'look/images/smiles/biggrin2.gif', 0),
(5, ':cool:', 'look/images/smiles/cool.gif', 0),
(6, ':lol:', 'look/images/smiles/laugh.gif', 0),
(7, ':(', 'look/images/smiles/sad.gif', 0),
(8, ':mad:', 'look/images/smiles/mad_1.gif', 0),
(9, ':#', 'look/images/smiles/blushing.gif', 0),
(10, ':@@:', 'look/images/smiles/blink.gif', 0),
(11, ':yes:', 'look/images/smiles/yes.gif', 0),
(12, ':no:', 'look/images/smiles/no_1.gif', 0),
(13, ':hmm:', 'look/images/smiles/g.gif', 0),
(14, '', 'look/images/icons/bomb.gif', 1),
(15, '', 'look/images/icons/boxed.gif', 1),
(16, '', 'look/images/icons/bye2.gif', 1),
(17, '', 'look/images/icons/clap_1.gif', 1),
(18, '', 'look/images/icons/coffee.gif', 1),
(19, '', 'look/images/icons/cry.gif', 1),
(20, '', 'look/images/icons/cupidarrow.gif', 1),
(21, '', 'look/images/icons/devil_2.gif', 1),
(22, '', 'look/images/icons/g.gif', 1),
(23, '', 'look/images/icons/icecream.gif', 1),
(24, '', 'look/images/icons/king.gif', 1),
(25, '', 'look/images/icons/lock.gif', 1),
(26, '', 'look/images/icons/marsa117.gif', 1),
(27, '', 'look/images/icons/mf_bookread.gif', 1),
(28, '', 'look/images/icons/smoke.gif', 1),
(29, '', 'look/images/icons/thumbup.gif', 1),
(30, '', 'look/images/icons/tooth.gif', 1),
(31, '', 'look/images/icons/vertag.gif', 1),
(32, '', 'look/images/icons/wub.gif', 1),
(33, '', 'look/images/icons/winner_first_h4h.gif', 1),
(34, '', 'look/images/icons/winner_second_h4h.gif', 1),
(35, '', 'look/images/icons/winner_third_h4h.gif', 1);";


$inserts[] = "INSERT INTO pbb_style (id, style_title, style_on, style_order, style_path, image_path, template_path, cache_path) VALUES
(1, 'default-style-304', 1, 1, '../styles/style_default/css/style.css', '../styles/style_default/images', '', '');";


$inserts[] = "INSERT INTO pbb_toolbox (id, name, tool_type) VALUES
(1, 'Tahoma', 1),
(2, 'Times new roman', 1),
(3, 'Courier new', 1),
(4, 'Arial', 1),
(5, 'skyblue', 2),
(6, 'royalblue', 2),
(7, 'blue', 2),
(8, 'darkblue', 2),
(9, 'orange', 2),
(10, 'orangered', 2),
(11, 'crimson', 2),
(12, 'red', 2),
(13, 'firebrick', 2),
(14, 'darkred', 2),
(15, 'green', 2),
(16, 'limegreen', 2),
(17, 'seagreen', 2),
(18, 'deeppink', 2),
(19, 'tomato', 2),
(20, 'coral', 2),
(21, 'purple', 2),
(22, 'indigo', 2),
(23, 'burlywood', 2),
(24, 'sandybrown', 2),
(25, 'sienna', 2),
(26, 'chocolate', 2),
(27, 'teal', 2),
(28, 'silver', 2);";

$inserts[] = "INSERT INTO pbb_userrating (id, rating, posts) VALUES
(1, 'look/images/rating/rating_0.gif', 10),
(2, 'look/images/rating/rating_1.gif', 100),
(3, 'look/images/rating/rating_2.gif', 200),
(4, 'look/images/rating/rating_3.gif', 400),
(5, 'look/images/rating/rating_4.gif', 600),
(6, 'look/images/rating/rating_5.gif', 1000);";

$inserts[] = "INSERT INTO pbb_usertitle (id, usertitle, posts) VALUES
(1, 'عضو جديد	', 2),
(2, 'عضـو مشارك', 100),
(3, 'عضو فعال	', 250),
(4, 'عضـو نشيط', 500),
(5, 'عضـو متميز', 750),
(6, 'عضو متألق', 1000),
(7, 'عضـو بـارز', 1500),
(8, 'عضـو سوبر', 2000),
(9, 'عضـو ستـار', 3000),
(10, 'عضو سوبر ستار', 5000),
(11, 'عضو فضي', 8000),
(12, 'عضو ذهبي', 11000),
(13, 'عضو ماسي', 14000),
(14, 'امير المنتدى', 17000),
(15, 'امبراطور المتدى', 50000);";
?>