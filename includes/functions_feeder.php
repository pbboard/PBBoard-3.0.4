<?php
class Feeder
{
	// slurp all enabled feeds from the database
	function _RunFeedRss()
	{
	    global $PowerBB;
	    require_once('FeedParser.php');

	// 1. تحسين الاستعلام الأولي: جلب التغذيات التي حان وقت فحصها فقط.
	    // ORDER BY feeds_time ASC لضمان معالجة الأقدم أولاً.
	    $feeds_query = "SELECT * FROM " . $PowerBB->table['feeds'] . "
	                    WHERE options = '1' AND feeds_time < (" . $PowerBB->_CONF['now'] . " - ttl)
	                    ORDER BY feeds_time ASC";
	    $feeds_result = $PowerBB->DB->sql_query($feeds_query);

	    while ($FeedsInfo = $PowerBB->DB->sql_fetch_array($feeds_result))
	    {
	        // تم دمج التحقق من الوقت في الاستعلام الأولي، لكن يمكن إبقاؤه للسلامة
	        if ($FeedsInfo['options'])
	        {
	            $MemberArr = array();
	            $MemberArr['where'] = array('id', $FeedsInfo['userid']);
	            $MemberInfo = $PowerBB->core->GetInfo($MemberArr, 'member');

	            $section = $FeedsInfo['forumid'];
	            $FROM_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '" . $section . "' ");
	            $FROM__row = $PowerBB->DB->sql_fetch_array($FROM_query);

	            // تأكدنا من جلب البيانات، الآن يمكننا المتابعة
	            if (!$MemberInfo || !$FROM__row) {
	                // تخطي إذا لم يتم العثور على العضو أو القسم، وتحديث وقت الفحص لتجنب المحاولة المتكررة مباشرة.
	                $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['feeds'] . " SET feeds_time ='" . $PowerBB->_CONF['now'] . "' where id = '" . $FeedsInfo['id'] . "'");
	                continue;
	            }

	            $this->FeedParser = new FeedParser;
	            $this->FeedParser->parse($FeedsInfo['rsslink']);
	            $Items = $this->FeedParser->getItems();

	            if ($Items)
	            {
	                // 3. التحقق المجمع من وجود الموضوع (تجميع العناوين أولاً)
	                $titles_to_check = [];
	                foreach ($Items as $Item) {
	                    if (!empty($Item['TITLE'])) {
	                        // تأكد من الهروب من السلاسل لتجنب حقن SQL
	                        $titles_to_check[] = addslashes($Item['TITLE']);
	                    }
	                }

					$existing_titles = [];
					if (!empty($titles_to_check)) {
						// بناء قائمة العناوين المُهربة لتضمينها في جملة IN
						$titles_list = "'" . implode("','", $titles_to_check) . "'";

						// استعلام واحد كبير للتحقق من جميع العناوين المجلوبة
						$exist_query_bulk = $PowerBB->DB->sql_query("SELECT title FROM " . $PowerBB->table['subject'] . " WHERE title IN ({$titles_list})");

						while ($row = $PowerBB->DB->sql_fetch_array($exist_query_bulk)) {
						// تخزين العناوين الموجودة بشكل مُوحد لتسهيل التحقق
						$existing_titles[strtolower(trim($row['title']))] = true;
						}
					}

	                $x = 0;
	                $y = $x++; // القيمة الافتراضية لـ $y هي 0، وستزيد $x لتصبح 1.

	                foreach($Items as $Item)
	                {
	                    $ItemTitle = $Item['TITLE'];
	                    $normalized_title = strtolower(trim($ItemTitle));

	                    // التحقق السريع من وجود الموضوع في المصفوفة المجمعة
	                    // استبدال التحقق القديم بالاستعلام داخل الحلقة
	                    if (!isset($existing_titles[$normalized_title]))
	                    {
	                        // *** بقية كود إعداد الموضوع كما هو، لكن مع تبديل استخدام المتغيرات

	                        $find = "{rss:link}";
	                        $LINK = ""; // تهيئة المتغير
	                        if(stristr($FeedsInfo['text'], $find))
	                        {
	                            if (empty($PowerBB->_CONF['template']['_CONF']['lang']['url_Original_repeat']))
	                            {
	                                $PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic'] = $PowerBB->_CONF['template']['_CONF']['lang']['url_Original_repeat'];
	                            }
	                            $LINK = "\n\n [url=".$Item['LINK']."]".$PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic']."[/url]";
	                        }

	                        $bad_characters = array_diff(range(chr(0), chr(31)), array(chr(9), chr(10), chr(13)));
	                        $text = $PowerBB->Powerparse->html2bb($Item['CONTENT:ENCODED']).$LINK;
	                        $text = str_replace($bad_characters, "", $text);
	                        $Item['TITLE'] = str_replace($bad_characters, "", $Item['TITLE']);
	                        $Item['TITLE'] = $PowerBB->functions->CleanVariable($Item['TITLE'], 'html');
	                        $Item['TITLE'] = $PowerBB->sys_functions->CleanSymbols($Item['TITLE']);
	                        $section = $FeedsInfo['forumid'];
	                        $section = $PowerBB->functions->CleanVariable($section, 'intval');
	                        $ItemTitle = $Item['TITLE']; // إعادة التعيين بعد التنظيف

	                        // بما أننا قمنا بالتحقق من وجود $FROM__row مسبقاً، لم نعد نحتاج إلى استعلامه هنا.

	                        $regexcodew['[code]'] = '#\[code\](.*)\[/code\]#siU';
	                        $text = preg_replace_callback($regexcodew, function($matchesw) {
	                            $matchesw[1] = str_replace("<br />", "\r", $matchesw[1]);
	                            return '[code]'.$matchesw[1].'[/code]';
	                        }, $text);

	                        if($FROM__row) // التحقق من وجود القسم
	                        {
	                            $SubjectArr	=	array();
	                            $SubjectArr['get_id']						=	true;
	                            $SubjectArr['field']	=	array();
	                            $SubjectArr['field']['title']	=	$Item['TITLE'];
	                            $SubjectArr['field']['text']	=	$text;
	                            $SubjectArr['field']['writer']	=	$MemberInfo['username'];
	                            $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
	                            $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
	                            if($FROM__row['review_subject'])
	                            {
	                            $SubjectArr['field']['review_subject'] = '1';
	                            }
	                            if($FROM__row['sec_section']
	                            or $FROM__row['hide_subject'])
	                            {
	                            $SubjectArr['field']['sec_subject'] = '1';
	                            }
	                            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
	                            $SubjectArr['field']['section']	=	$FeedsInfo['forumid'];
	                            $Insert = $PowerBB->subject->InsertSubject($SubjectArr);

	                            if($Insert)
	                            {
	                                // تحديث مشاركات العضو
	                                $posts = $MemberInfo['posts'] + 1;
	                                $MemberArr 				= 	array();
	                                $MemberArr['field'] 	= 	array();
	                                $MemberArr['field']['posts']			=	$posts;
	                                $MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
	                                $MemberArr['where']						=	array('id',$MemberInfo['id']);
	                                $UpdateMember = $PowerBB->member->UpdateMember($MemberArr);

	                                // ... كود إضافة الـ Tags التلقائي ...

	                                if ($PowerBB->_CONF['info_row']['add_tags_automatic'] == '1')
	                                {
	                                    //add tags Automatic from subject title
	                                    $string_title = implode(' ', array_unique(explode(' ', $Item['TITLE'])));

	                                    $excludedWords = array();
	                                    $censorwords = preg_split('/\s+/s', $string_title, -1, PREG_SPLIT_NO_EMPTY);
	                                    $excludedWords = array_merge($excludedWords, $censorwords);
	                                    unset($censorwords);

	                                    // Trim current exclusions
	                                    for ($i = 0; $i < count($excludedWords); $i++)
	                                    {
	                                        $excludedWords[$i] = trim($excludedWords[$i]);

	                                        if (function_exists('mb_strlen'))
	                                        {
	                                            $tag_less_num = mb_strlen($excludedWords[$i], 'UTF-8') >= 6;
	                                        }
	                                        else
	                                        {
	                                            $tag_less_num = strlen(utf8_decode($excludedWords[$i])) >= 6;
	                                        }

	                                        if($tag_less_num)
	                                        {

	                                            $InsertArr 						= 	array();
	                                            $InsertArr['field']				=	array();
	                                            $InsertArr['field']['tag_id'] 			= 	"";
	                                            $InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
	                                            $InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($excludedWords[$i], 'html');
	                                            $InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'], 'html');
	                                            // Note, this function is from tag system not subject system
	                                            $insert_tags = $PowerBB->core->Insert($InsertArr, 'tags_subject');
	                                        }
	                                    }
	                                }
	                            }
	                        }
	                    } // نهاية التحقق من وجود الموضوع

	                    $x++;
	                    // هذا الشرط غير فعال في الكود الأصلي حيث أن $y هي 0، و $x تبدأ من 1.
	                    // إذا كنت تقصد جلب عنصر واحد فقط، يجب أن يكون الشرط $x >= 1
	                    /* if($x == $y) break; */

	                } // نهاية حلقة foreach($Items as $Item)
	            }

	            // تحديث ذاكرة التخزين المؤقت للقسم
	            $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($FeedsInfo['forumid']);

	            // تحديث وقت آخر فحص للتغذية (سواء نجحت أو فشلت)
	            $feeds_time = $PowerBB->_CONF['now'];
	            $feeds_id = $FeedsInfo['id'];
	            $Update_Feeds = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['feeds'] . " SET feeds_time ='" . $feeds_time . "' where id = '" . $feeds_id . "'");
	        }
	    } // نهاية حلقة while ($FeedsInfo = $PowerBB->DB->sql_fetch_array($feeds_result))

	    // ... بقية الكود الخاص بتغيير حدود الذاكرة والوقت
	    if (($current_memory_limit = $PowerBB->functions->size_to_bytes(@ini_get('memory_limit'))) < 128 * 1024 * 1024 AND $current_memory_limit > 0)
	    {
	        @ini_set('memory_limit', 128 * 1024 * 1024);
	    }
	    @set_time_limit(0);
	}

}
?>

