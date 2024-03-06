<?php
/**
 * PBBoard 303 English Language Pack
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 */

/* INSTALL LANGUAGE VARIABLES */
$l['none'] = 'لا شيء';
$l['not_installed'] = 'غير مثبته';
$l['installed'] = 'مثبته';
$l['not_writable'] = 'غير قابل للكتابة';
$l['writable'] = 'قابل للكتابة';
$l['done'] = 'انتهى ';
$l['next'] = 'التالي';
$l['error'] = 'خطأ';
$l['multi_byte'] = 'Multi-Byte';
$l['recheck'] = 'اعادة الفحص';

$l['title'] = "معالج تثبيت PBBoard";
$l['welcome'] = 'مرحباً';
$l['license_agreement'] = 'اتفاقية الترخيص';
$l['req_check'] = 'فحص المتطلبات';
$l['db_config'] = 'معلومات قاعدة البيانات';
$l['table_creation'] = 'انشاء الجداول';
$l['data_insertion'] = 'إدخال البيانات';
$l['theme_install'] = 'تركيب الإستايلات';
$l['lang_install'] = 'تركيب اللغات';
$l['board_config'] = 'تكوين الاعدادات';
$l['admin_user'] = 'مسؤول المنتدى';
$l['finish_setup'] = 'نهاية التثبيت';
$l['table_population'] = 'إدخال البيانات الافتراضية';
$l['theme_installation'] = 'تركيب الاستايل الافتراضي وانشاء القوالب الخاصه به';
$l['create_admin'] = 'إنشاء حساب مسؤول المنتدى';

$l['already_installed'] = "PBBoard تم تثبيته بالفعل";
$l['PBBoard_already_installed'] = "<p>مرحباً بك في معالج تثبيت PBBoard {1} . اكتشف PBBoard أنه بالفعل تم تكوين ملف config من قبل في هذا الدليل.</p>
<p>يرجى اختيار الإجراء المناسب ( تثبيت جديد أو ترقية ) أدناه:</p>

<div class=\"border_wrapper upgrade_note\" style=\"padding: 4px;\">
	<h3>ترقية النسخة الحالية من PBBoard إلى {1} <span style=\"font-size: 80%; color: maroon;\">(موصى به)</span></h3>
	<p>سيؤدي هذا الخيار إلى ترقية الإصدار الحالي من PBBoard إلى PBBoard {1}.</p>
	<p>يجب عليك اختيار هذا الخيار عندما ترغب في الاحتفاظ بمواضيع المنتدى الخاصة بك ، والمشاركات ، والمستخدمين وغير ذلك من المعلومات.</p>
	<form method=\"post\" action=\"upgrade.php\">
		<div class=\"next_button\"><input type=\"submit\" class=\"submit_button\" value=\"ترقية PBBoard إلى {1} &raquo;\" /></div>
	</form>
</div>

<div style=\"padding: 4px;\">
	<h3>تثبيت نسخة جديدة من PBBoard</h3>
	<p>هذا الخيار سوف <span style=\"color: red;\">يحذف المنتدى الحالي الذي قد أعددته</span> ويثبت نسخة جديدة من PBBoard.</p>
	<p>يجب عليك اختيار هذا الخيار لمحو نسختك الحالية من PBBoard إذا كنت ترغب في البدء مرة أخرى.</p>
	<form method=\"post\" action=\"index.php\" onsubmit=\"return confirm('هل أنت متأكد من أنك ترغب في تثبيت نسخة جديدة من PBBoard؟\\n\\nسيؤدي هذا إلى حذف المنتدى الموجود لديك. لا يمكن إلغاء هذه العملية.');\">
		<input type=\"hidden\" name=\"action\" value=\"intro\" />
		<div class=\"next_button\"><input type=\"submit\" class=\"submit_button\" value=\"تثبيت PBBoard {1} &raquo;\" /></div>
	</form>
</div>";

$l['PBBoard_incorrect_folder'] = "<div class=\"border_wrapper upgrade_note\" style=\"padding: 4px;\">
	<h3>اكتشف PBBoard أنه يعمل من \"Upload\" وهو اسم المجلد الافتراضي.</h3>
	<p>في حين لا يوجد شيء خاطئ في هذا ، فمن المستحسن أن تقوم بتحميل محتويات \"Upload\" الدليل وليس الدليل نفسه.<br /></p>
	</div>";

$l['welcome_step'] = '<p>مرحبًا بك في معالج التثبيت لـ PBBoard {1}. سيقوم هذا المعالج بتثبيت وتكوين نسخة من PBBoard على الخادم الخاص بك.</p>
<p>الآن بعد أن قمت بتحميل ملفات PBBoard ، يجب إنشاء قاعدة بيانات جديدة وتجهيز معلوماتها لإدخالها في الخطوة القادمة. يوجد أدناه مخطط لما سيتم إنجازه أثناء التثبيت.</p>
<ul>
	<li> فحص متطلبات تشغيل PBBoard</li>
	<li>تكوين قاعدة البيانات</li>
	<li>إنشاء جداول قاعدة البيانات</li>
	<li>ادخال البيانات الافتراضية</li>
	<li>تركيب الاستايل الافتراضي</li>
	<li>تركيب اللغات الافتراضية</li>
	<li>إنشاء حساب آدمن المنتدى</li>
	<li>تكوين اعدات المنتدى الافتراضية</li>
</ul>
<p>بعد اكتمال كل خطوة بنجاح ، انقر على التالي للانتقال إلى الخطوة التالية.</p>
<p>انقر "التالى" لعرض اتفاقية ترخيص PBBoard.</p>';
$l['license_step'] = '<div class="license_agreement">
{1}
</div>
<p><strong>بالنقر فوق التالي ، فإنك توافق على الشروط المنصوص عليها في اتفاقية ترخيص PBBoard المذكورة أعلاه.</strong></p>';


$l['req_step_top'] = '<p>قبل أن تتمكن من تثبيت PBBoard ، يجب أن نتحقق من أنك تستوفي الحد الأدنى من المتطلبات للتثبيت.</p>';
$l['req_step_reqtable'] = '<div class="border_wrapper">
			<div class="title">فحص المتطلبات</div>
		<table class="general" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2" class="first last">المتطلبات</th>
			</tr>
		</thead>
		<tbody>
		<tr class="first">
			<td class="first">إصدار PHP:</td>
			<td class="last alt_col">{1}</td>
		</tr>
		<tr class="alt_row">
			<td class="first">ملحقات DB المدعومة:</td>
			<td class="last alt_col">{2}</td>
		</tr>
		<tr class="alt_row">
			<td class="first">ملحقات الترجمة المدعومة:</td>
			<td class="last alt_col">{3}</td>
		</tr>
		<tr class="alt_row">
			<td class="first">ملحقات PHP XML :</td>
			<td class="last alt_col">{4}</td>
		</tr>
		<tr class="alt_row">
			<td class="first">تصريح الكتابة لمجلد الملحقات: addons</td>
			<td class="last alt_col">{5}</td>
		</tr>
		<tr>
			<td class="first">تصريح الكتابة لمجلد تحميل المرفقات: download</td>
			<td class="last alt_col">{6}</td>
		</tr>
		<tr>
			<td class="first">تصريح الكتابة لمجلد الكاش: cache</td>
			<td class="last alt_col">{7}</td>
		</tr>
		<tr class="alt_row">
			<td class="first">رفع الملفات على المسار الرئيسي:</td>
			<td class="last alt_col">{8}</td>
		</tr>
		<tr class="last">
			<td class="first">تصريح الكتابة لمجلد الصور الشخصية: download/avatar</td>
			<td class="last alt_col">{9}</td>
		</tr>
		</tbody>
		</table>
		</div>';
$l['req_step_reqcomplete'] = '<p><strong>تهانينا ، تستوفي متطلبات تشغيل PBBoard.</strong></p>
<p>انقر فوق التالي لمتابعة عملية التثبيت.</p>';

$l['req_step_span_fail'] = '<span class="fail"><strong>{1}</strong></span>';
$l['req_step_span_pass'] = '<span class="pass">{1}</span>';

$l['req_step_error_box'] = '<p><strong>{1}</strong></p>';
$l['req_step_error_phpversion'] = 'PBBoard Requires PHP 5.2.0 or later to run. You currently have {1} installed.';
$l['req_step_error_dboptions'] = 'PBBoard requires one or more suitable database extensions to be installed. Your server reported that none were available.';
$l['req_step_error_xmlsupport'] = 'PBBoard requires PHP to be compiled with support for XML Data Handling. Please see <a href="http://www.php.net/xml" target="_blank">PHP.net</a> for more information.';
$l['req_step_error_configdefaultfile'] = 'The configuration file (inc/config.default.php) could not be renamed. Please manually rename the <u>config.default.php</u> file to <u>config.php</u> to allow it to be written to or contact <a href="https://pbboard.info/forums" target="_blank">PBBoard Support.</a>';
$l['req_step_error_configfile'] = 'The configuration file (inc/config.php) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_settingsfile'] = 'The settings file (inc/settings.php) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_cachedir'] = 'The cache directory (cache/) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_uploaddir'] = 'The uploads directory (uploads/) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_avatardir'] = 'The avatars directory (uploads/avatars/) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_cssddir'] = 'The css directory (css/) is not writable. Please adjust the <a href="http://docs.pbboard.info/CHMOD_Files.html" target="_blank">chmod</a> permissions to allow it to be written to.';
$l['req_step_error_tablelist'] = '<div class="error">
<h3>Error</h3>
<p>The PBBoard Requirements check failed due to the reasons below. PBBoard installation cannot continue because you did not meet the PBBoard requirements. Please correct the errors below and try again:</p>
{1}
</div>';


$l['db_step_config_db'] = '<p>لقد حان الوقت الآن لتكوين قاعدة البيانات التي سيستخدمها PBBoard بالإضافة إلى تفاصيل توثيق قاعدة البيانات الخاصة بك. إذا لم يكن لديك هذه المعلومات ، فيمكن الحصول عليها عادة من مضيفك.</p>';
$l['db_step_config_table'] = '<div class="border_wrapper">
<div class="title">تكوين قاعدة البيانات</div>
<table class="general" cellspacing="0">
<tr>
	<th colspan="2" class="first last">إعدادات قاعدة البيانات</th>
</tr>
<tr class="first">
	<td class="first"><label for="dbengine">محرك إدارة قواعد البيانات:</label></td>
	<td class="last alt_col"><select name="dbengine" id="dbengine" onchange="updateDBSettings();">{1}</select></td>
</tr>
{2}
</table>
</div>
<p>بمجرد التحقق من صحة هذه التفاصيل ، انقر فوق التالي للمتابعة.</p>';

$l['database_settings'] = "إعدادات قاعدة البيانات";
$l['database_path'] = "Database Path:";
$l['database_host'] = "اسم خادم قاعدة البيانات:";
$l['database_user'] = "اسم مستخدم قاعدة البيانات:";
$l['database_pass'] = "كلمة مرور قاعدة البيانات:";
$l['database_name'] = "اسم قاعدة البيانات:";
$l['table_settings'] = "إعدادات الجداول";
$l['table_prefix'] = "بادئة الجداول:";
$l['table_encoding'] = "ترميز الجداول:";

$l['db_step_error_config'] = '<div class="error">
<h3>خطأ</h3>
<p>يبدو أن هناك خطأ واحدًا أو أكثر في معلومات تهيئة قاعدة البيانات التي قدمتها:</p>
{1}
<p>بمجرد تصحيح ما ورد أعلاه ، استمر في التثبيت.</p>
</div>';
$l['db_step_error_invalidengine'] = 'لقد حددت محرك قاعدة بيانات غير صالح. يرجى الاختيار من القائمة أدناه.';
$l['db_step_error_noconnect'] = 'تعذر الاتصال بخادم قاعدة البيانات على \'{1}\' مع اسم المستخدم وكلمة المرور الموفرة. هل أنت متأكد من أن اسم المضيف وتفاصيل المستخدم صحيحة؟';
$l['db_step_error_nodbname'] = 'لا يمكن تحديد قاعدة البيانات \'{1}\'. هل أنت متأكد من وجودها واسم المستخدم وكلمة المرور المحددين يمكنهم الوصول إليها؟';
$l['db_step_error_missingencoding'] = 'لم تقم باختيار ترميز حتى الآن. يرجى التأكد من اختيار الترميز قبل المتابعة. (اختر \'UTF-8 Unicode\' إذا كنت غير متأكد)';
$l['db_step_error_sqlite_invalid_dbname'] = 'You may not use relative URLs for SQLite databases. Please use a file system path (ex: /home/user/database.db) for your SQLite database.';
$l['db_step_error_invalid_tableprefix'] = 'يمكنك فقط استخدام تسطير سفلي (_) والأحرف الأبجدية الرقمية في بادئة الجدول. الرجاء استخدام بادئة جدول صالحة قبل المتابعة.';
$l['db_step_error_tableprefix_too_long'] = 'لا يجوز لك استخدام بادئة جدول إلا بطول 40 حرفًا أو أقل. الرجاء استخدام بادئة جدول أقصر قبل المتابعة.';
$l['db_step_error_utf8mb4_error'] = '\'4-Byte UTF-8 Unicode\' يتطلب MySQL 5.5.3 أو أعلى. يرجى تحديد ترميز متوافق مع إصدار MySQL الخاص بك.';

$l['tablecreate_step_connected'] = '<p>تم الاتصال بقاعدة البيانات بنجاح من خلال البيانات التي قدمتها..</p>
<p>محرك قاعدة البيانات: {1} {2}</p>
<p>سيتم الآن إنشاء جداول قاعدة بيانات PBBoard.</p>';
$l['tablecreate_step_created'] = 'إنشاء الجدول {1}...';
$l['tablecreate_step_done'] = '<p>تم إنشاء كل الجداول ، انقر على التالي لتعبئتها.</p>';

$l['populate_step_insert'] = '<p>الآن بعد أن تم إنشاء الجداول الأساسية ، حان الوقت لإدخال البيانات الافتراضية.</p>';
$l['populate_step_inserted'] = '<p>تم إدخال البيانات الافتراضية بنجاح في قاعدة البيانات. انقر فوق التالي لتركيب ستايل  PBBoard الافتراضي.</p>';


$l['theme_step_importing'] = '<p>جارٍ تحميل واستيراد الاستايل وملف الاستايل ...</p>';
$l['theme_step_imported'] = '<p>تم إدراج الاستايل الافتراضي ومجموعات القوالب بنجاح. انقر فوق التالي لتكوين الخيارات الأساسية للوحة الخاصة بك.</p>';


$l['config_step_table'] = '<p>لقد حان الوقت الآن لتهيئة الإعدادات الأساسية للمنتدى مثل اسم المنتدى وعنوان URL وتفاصيل موقعك الإلكتروني ، بالإضافة إلى نطاق والمسارات . يمكن تغيير هذه الإعدادات بسهولة في المستقبل من خلال لوحة تحكم إدارة PBBoard.</p>
		<div class="border_wrapper">
			<div class="title">إدخال البيانات</div>
			<table class="general" cellspacing="0">
				<tbody>
				<tr>
					<th colspan="2" class="first last">تفاصيل المنتدى</th>
				</tr>
				<tr class="first">
					<td class="first"><label for="bbname">أسم المنتدى:</label></td>
					<td class="last alt_col"><input type="text" class="text_input" name="bbname" id="bbname" value="{1}" /></td>
				</tr>
				<tr class="alt_row last">
					<td class="first"><label for="bburl">عنوان URL للمنتدى (بدون شرطة مائلة):</label></td>
					<td class="last alt_col"><input type="text" class="text_input" name="bburl" id="bburl" value="{2}" onkeyup="warnUser(هذه, \'تم ضبط هذا الخيار تلقائيًا. لا تغيره إذا لم تكن متأكدًا من القيمة الصحيحة ، وإلا فقد تكون الروابط الموجودة في منتداك معطلة.\')" onchange="warnUser(هذه, \'تم ضبط هذا الخيار تلقائيًا. لا تغيره إذا لم تكن متأكدًا من القيمة الصحيحة ، وإلا فقد تكون الروابط الموجودة في منتداك معطلة.\')" /></td>
				</tr>
				<tr>
					<th colspan="2" class="first last">تفاصيل الموقع</th>
				</tr>
				<tr>
					<td class="first"><label for="websitename">اسم الموقع:</label></td>
					<td class="last alt_col"><input type="text" class="text_input" name="websitename" id="websitename" value="{3}" /></td>
				</tr>
				<tr class="alt_row last">
					<td class="first"><label for="websiteurl">رابط الموقع:</label></td>
					<td class="last alt_col"><input type="text" class="text_input" name="websiteurl" id="websiteurl" value="{4}" /></td>
				</tr>
				<tr>
					<th colspan="2" class="first last">بريد المنتدى</th>
				</tr>
				<tr class="last">
					<td class="first"><label for="contactemail">بريد التواصل الالكتروني:</label></td>
					<td class="last alt_col"><input type="text" class="text_input" name="contactemail" id="contactemail" value="{7}" /></td>
				</tr>
				</tbody>
			</table>
		</div>

	<p>بمجرد قيامك بإدخال التفاصيل أعلاه بشكل صحيح وتكون مستعدًا للمتابعة ، انقر فوق التالي.</p>';

$l['config_step_error_config'] = '<div class="error">
<h3>Error</h3>
<p>There seems to be one or more errors with the board configuration you supplied:</p>
{1}
<p>Once the above are corrected, continue with the installation.</p>
</div>';
$l['config_step_error_url'] = 'You did not enter the URL to your forums.';
$l['config_step_error_name'] = 'You did not enter a name for your copy of PBBoard.';
$l['config_step_revert'] = 'Click to revert this setting to original value.';


$l['admin_step_setupsettings'] = '<p>Setting up basic board settings...</p>';
$l['admin_step_insertesettings'] = '<p>Inserted {1} settings into {2} groups.</p>
<p>Updating settings with user defined values.</p>';
$l['admin_step_insertedtasks'] = '<p>Inserted {1} scheduled tasks.</p>';
$l['admin_step_insertedviews'] = '<p>Inserted {1} admin views.</p>';
$l['admin_step_createadmin'] ='<p>تحتاج إلى إنشاء حساب مسؤول أولي لك لتسجيل الدخول وإدارة نسخة PBBoard الخاصة بك. يرجى ملء الحقول المطلوبة أدناه لإنشاء هذا الحساب.</p>';
$l['admin_step_admintable'] = '<div class="border_wrapper">
			<div class="title">تفاصيل حساب المسؤول</div>

		<table class="general" cellspacing="0">
		<thead>
		<tr>
			<th colspan="2" class="first last">تفاصيل الحساب</th>
		</tr>
		</thead>
		<tr class="first">
			<td class="first"><label for="adminuser">اسم المستخدم:</label></td>
			<td class="alt_col last"><input type="text" class="text_input" name="adminuser" id="adminuser" value="{1}" /></td>
		</tr>
		<tr class="alt_row">
			<td class="first"><label for="adminpass">كلمه السر:</label></td>
			<td class="alt_col last"><input type="password" class="text_input" name="adminpass" id="adminpass" value="" autocomplete="off" onchange="comparePass()" /></td>
		</tr>
		<tr class="last">
			<td class="first"><label for="adminpass2">أعد إدخال كلمة السر:</label></td>
			<td class="alt_col last"><input type="password" class="text_input" name="adminpass2" id="adminpass2" value="" autocomplete="off" onchange="comparePass()"  /></td>
		</tr>
		<tr>
			<th colspan="2" class="first last">بيانات الاتصال</th>
		</tr>
		<tr class="first last">
			<td class="first"><label for="adminemail">عنوان البريد الإلكتروني:</label></td>
			<td class="alt_col last"><input type="text" class="text_input" name="adminemail" id="adminemail" value="{2}" /></td>
		</tr>
	</table>
	</div>

	<p>بمجرد قيامك بإدخال التفاصيل أعلاه بشكل صحيح وتكون مستعدًا للمتابعة ، انقر فوق التالي.</p>';

$l['admin_step_error_config'] = '<div class="error">
<h3>خطأ</h3>
<p>يبدو أن هناك خطأ واحدًا أو أكثر باستخدام تهيئة اللوحة التي قدمتها:</p>
{1}
<p>بمجرد تصحيح ما ورد أعلاه ، استمر في التثبيت.</p>
</div>';
$l['admin_step_error_nouser'] = 'لم تدخل اسم مستخدم لحساب المسؤول الخاص بك.';
$l['admin_step_error_nopassword'] = 'لم تقم بإدخال كلمة مرور لحساب المسؤول الخاص بك.';
$l['admin_step_error_nomatch'] = 'كلمات المرور التي أدخلتها غير متطابقة.';
$l['admin_step_error_noemail'] = 'لم تدخل عنوان بريدك الإلكتروني لحساب المسؤول.';
$l['admin_step_nomatch'] = 'لا تتطابق كلمة المرور التي تم إعادة كتابتها مع كلمة المرور من الإدخال الأول. يرجى تصحيحها قبل المتابعة.';

$l['done_step_usergroupsinserted'] = "<p>استيراد مجموعات المستخدمين..";
$l['done_step_admincreated'] = '<p>إنشاء حساب المسؤول..';
$l['done_step_adminoptions'] = '<p>إنشاء خيارات حساب المسؤول ..';
$l['done_step_cachebuilding'] = '<p>Building data caches..';
$l['done_step_success'] = '<p class="success">تم بنجاح تثبيت نسخة PBBoard الخاصة بك وتكوينها بشكل صحيح.</p>
<p>تشكرك مجموعة PBBoard على دعمك في تثبيت برنامجنا ونأمل أن نراك في <a href="https://pbboard.info/forums/" target="_blank">PBBoard Community Forums</a> إذا كنت بحاجة إلى مساعدة أو ترغب في أن تصبح جزء من PBBoard community Forums.</p>';
$l['done_step_locked'] = '<p>تم قفل المثبت الخاص بك. لفتح المثبت ، يرجى حذف \'<b>lock</b>\' من مجلد install.</p><p>يمكنك الآن المتابعة إلى نسختك الجديدة من PBBoard. اذهب إلى <a href="../index.php"><big>المنتدى</big></a> أو <a href="../admincp/index.php"><big>لوحة تحكم الإدارة</big></a>.</p>';
$l['done_step_dirdelete'] = '<p><strong><span style="color:red">ينصح بحذف مجلد install لحماية المنتدى.</span></strong></p>';
$l['done_whats_next'] = '<div class="error"><p><strong>للتحويل من برنامج منتدى آخر؟</strong></p><p>يقدم PBBoard نظام دمج لسهولة دمج المنتديات المتعددة من مختلف برامج المنتدى الشائعة ، مما يتيح عملية تحويل سهلة إلى PBBoard. إذا كنت تتطلع للتبديل إلى PBBoard ، فأنت تتوجه في الاتجاه الصحيح! تفحص ال <a target="_blank" href="https://pbboard.info/categorie_12">سكربتات التحويل </a> للمزيد من المعلومات.</p>';

/* UPGRADE LANGUAGE VARIABLES */
$l['upgrade'] = "عمليات الترقية";
$l['upgrade_welcome'] = "<p>مرحبًا بك في معالج الترقية لـ PBBoard {1}.</p><p>قبل المتابعة ، يرجى التأكد من معرفة إصدار PBBoard الذي كنت تستخدمه سابقًا حيث ستحتاج إلى تحديده أدناه.</p><p><strong>نوصي بشدة أن تحصل أيضًا على نسخة احتياطية كاملة من قاعدة البيانات والملفات الخاصة بك قبل محاولة الترقية </ strong> حتى إذا حدث خطأ ما ، يمكنك العودة بسهولة إلى الإصدار السابق. تأكد أيضًا من اكتمال النسخ الاحتياطية قبل المتابعة. </ p> <p> تأكد من النقر فقط فوق التالي مرة واحدة على كل خطوة من عملية الترقية. قد تستغرق بعض الصفحات وقتًا للتحميل وفقًا لحجم المنتدى الخاص بك.</p><p>بمجرد أن تكون جاهزًا ، يرجى تحديد الإصدار القديم أدناه والنقر فوق التالي للمتابعة.</p>";
$l['upgrade_templates_reverted'] = 'Templates Reverted';
$l['upgrade_templates_reverted_success'] = "<p>All of the templates have successfully been reverted to the new ones contained in this release. Please press next to continue with the upgrade process.</p>";
$l['upgrade_settings_sync'] = 'Settings Synchronization';
$l['upgrade_settings_sync_success'] = "<p>The board settings have been synchronized with the latest in PBBoard.</p><p>{1} new settings inserted along with {2} new setting groups.</p><p>To finalize the upgrade, please click next below to continue.</p>";
$l['upgrade_datacache_building'] = 'Data Cache Building';
$l['upgrade_building_datacache'] = '<p>Building caches...';
$l['upgrade_continue'] = 'Please press next to continue';
$l['upgrade_locked'] = "<p>Your installer has been locked. To unlock the installer please delete the 'lock' file in this directory.</p><p>You may now proceed to your upgraded copy of <a href=\"../index.php\">PBBoard</a> or its <a href=\"../{1}/index.php\">Admin Control Panel</a>.</p>";
$l['upgrade_removedir'] = 'Please remove this directory before exploring your upgraded PBBoard.';
$l['upgrade_congrats'] = "<p>Congratulations, your copy of PBBoard has successfully been updated to {1}.</p>{2}<p><strong>What's Next?</strong></p><ul><li>Please use the 'Find Updated Templates' tool in the Admin CP to find customized templates updated during this upgrade process. Edit them to contain the changes or revert them to originals.</li><li>Ensure that your board is still fully functional.</li></ul>";
$l['upgrade_template_reversion'] = "Template Reversion Warning";
$l['upgrade_template_reversion_success'] = "<p>All necessary database modifications have successfully been made to upgrade your board.</p><p>This upgrade requires all templates to be reverted to the new ones contained in the package so please back up any custom templates you have made before clicking next.";
$l['upgrade_send_stats'] = "<p><input type=\"checkbox\" name=\"allow_anonymous_info\" value=\"1\" id=\"allow_anonymous\" checked=\"checked\" /> <label for=\"allow_anonymous\"> Send anonymous statistics about your server specifications to the PBBoard Group</label> (<a href=\"http://docs.pbboard.info/Anonymous_Statistics.html\" style=\"color: #555;\" target=\"_blank\"><small>What information is sent?</small></a>)</p>";

$l['please_login'] = "الرجاء تسجيل الدخول";
$l['login'] = "تسجيل الدخول";
$l['login_desc'] = "يرجى إدخال اسم المستخدم وكلمة المرور لبدء عملية الترقية. يجب أن تكون مسؤول منتدى لإجراء الترقية.";
$l['login_username'] = "اسم المستخدم";
$l['login_password'] = "كلمه السر";
$l['login_password_desc'] = "يرجى ملاحظة أن كلمة السر حساسة لحالة الأحرف.";

/* Error messages */
$l['development_preview'] = "<div class=\"error\"><h2 class=\"fail\">تحذير</h2><p>This version of PBBoard is a development preview and is to be used for testing purposes only.</p><p>No official support, other than for plugins and theme development, will be provided for this version. By continuing with this install/upgrade you do so at your own risk.</p></div>";
$l['locked'] = 'ملف المثبت \'<b>lock</b>\' مؤمن حاليًا ، الرجاء إزالته من  مجلد install للمتابعة';
$l['no_permision'] = "You do not have permissions to run this process. You need administrator permissions to be able to run the upgrade procedure.<br /><br />If you need to logout, please click <a href=\"upgrade.php?action=logout&amp;logoutkey={1}\">here</a>. From there you will be able to log in again under your administrator account.";

$l['task_versioncheck_ran'] = "The version check task successfully ran.";
$l['lang_lnsertion'] = "تركيب اللغات";
$l['languages_step_imported'] = '<p>تم تركيب الاستايل الافتراضي ومجموعات القوالب الخاصة به بنجاح. انقر فوق التالي لتركيب لغات PBBoard الافتراضية.</p>';
$l['languages_step_successfully'] = '<p>تم إدراج اللغة العربية الافتراضية بنجاح انقر فوق التالي لتكوين الخيارات الأساسية للوحة.</p>';
$l['password_incorrect'] = "كلمة المرور التي أدخلتها غير صحيحة. إذا نسيت كلمة المرور ، فاتقر <a href=\"../index.php?page=forget&index=1\" target=\"_blank\">هنا</a>. وإلا ، ارجع وحاول مرة أخرى.";
$l['username_invalid'] = "يبدو أن اسم المستخدم الذي أدخلته غير صالح.";
$l['check_logoutkey'] = "لا يمكن التحقق من كود المستخدم الخاص بك لتسجيل خروجك. ربما كان هذا بسبب محاولة جافا سكريبت الخبيثة تسجيل خروجك تلقائيًا. إذا كنت تنوي تسجيل الخروج ، يرجى النقر على زر تسجيل الخروج في القائمة العلوية.";
$l['done_step_upgraded_success'] = '<p class="success">تمت ترقية PBBoard بنجــاح.</p>';
$l['no_latest_upgraded'] = "PBBoard على أحدث إصدار الآن ";
$l['finish_upgrade'] = "إنتهاء الترقية";
$l['convert_latin1'] = "PBBoard تم بنجاح تحويل الجداول إلى UTF8MP4 .";
$l['req_convert_reqcomplete'] = '<p><strong>تم الإنتهاء من تحويل ترميز أحرف PBBoard من latin1 إلى UTF8MP4 ..</strong></p>
<b>انقر فوق التالي لمتابعة تحديث الكاش لجميع المنتديات</b> ..<br />';
$l['choose_languag'] = "اختيار اللغة - choose language";
$l['choose_a_language'] = "<div class=\"border_wrapper upgrade_note\" style=\"padding: 4px;\">
<form method=\"post\" action=\"index.php\">
<select data-placeholder=\"Choose a Language...\" name=\"selectlang\">
  <option value=\"ar\">(اللغة العربية) Arabic language</option>
  <option value=\"en\">(اللغة الإنجليزية) English Language</option>
  </select>
		<div class=\"next_button\">
		<input type=\"hidden\" name=\"action\" value=\"lang\" />
		<input type=\"submit\" class=\"submit_button\" value=\"Submit - إعتمد \" /></div>
  </form>
</div>";
$l['choose_a_language_upgrade'] = "<div class=\"border_wrapper upgrade_note\" style=\"padding: 4px;\">
<form method=\"post\" action=\"upgrade.php\">
<select data-placeholder=\"Choose a Language...\" name=\"selectlang\">
  <option value=\"ar\">(اللغة العربية) Arabic language</option>
  <option value=\"en\">(اللغة الإنجليزية) English Language</option>
  </select>
		<div class=\"next_button\">
		<input type=\"hidden\" name=\"action\" value=\"lang\" />
		<input type=\"submit\" class=\"submit_button\" value=\"Submit - إعتمد \" /></div>
  </form>
</div>";
$l['title_upgrade'] = " معالج ترقية PBBoard";
$l['convert_table'] = "تحويل جدول:";
$l['go_update_section_cache'] = "تم الفحص .<br /><br /><b>انقر فوق التالي لمتابعة تحديث الكاش لجميع المنتديات</b> ..<br />";
$l['update_section_cache'] = "تحديث منتدى :";
$l['update_all_sections_cache'] = "تحديث كاش جميع المنتديات";
$l['recheck_server_character'] = 'فحص ترميز أحرف قاعدة البيانات';
$l['req_convert_utf'] = '<p><strong>انقر فوق التالي لتحويل ترميز أحرف PBBoard من latin1 إلى UTF8MP4 , هذه الخطوة ستأخذ بعض الوقت حسب حجم قاعدة البيانات فيرجى الإنتظار حتى اتمام العملية بالكامل ..</strong></p>';
$l['finish_upgrade_sections_cache'] = '<br /><p>تم الإنتهاء من تحديث اقسام المنتدى ,انقر فوق "التالي" لمتابعة عملية "إنهاء الترقية"..</p>';
