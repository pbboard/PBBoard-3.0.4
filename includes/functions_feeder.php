<?php
class Feeder
{
    function _RunFeedRss()
    {
        global $PowerBB;
        require_once('FeedParser.php');

        $feeds_query = "SELECT * FROM " . $PowerBB->table['feeds'] . "
                        WHERE options = '1' AND feeds_time < (" . $PowerBB->_CONF['now'] . " - ttl)
                        ORDER BY feeds_time ASC";
        $feeds_result = $PowerBB->DB->sql_query($feeds_query);

        while ($FeedsInfo = $PowerBB->DB->sql_fetch_array($feeds_result))
        {

            $MemberArr = array();
            $MemberArr['where'] = array('id', $FeedsInfo['userid']);
            $MemberInfo = $PowerBB->core->GetInfo($MemberArr, 'member');

            $forum_id = intval($FeedsInfo['forumid']);
            $section_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$forum_id' ");
            $section_row = $PowerBB->DB->sql_fetch_array($section_query);

            if (!$MemberInfo || !$section_row) continue;

            $this->FeedParser = new FeedParser;
            $this->FeedParser->parse($FeedsInfo['rsslink']);
            $Items = $this->FeedParser->getItems();

            if ($Items)
            {

                foreach($Items as $Item)
                {
                    if (empty($Item['TITLE'])) continue;

                    $CleanTitle = $this->CleanTitleForCheck($Item['TITLE']);
                    $EscapedTitle = $PowerBB->DB->sql_escape($CleanTitle);

                    $check_query = $PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . "
                                                           WHERE section = '$forum_id'
                                                           AND title = '$EscapedTitle'
                                                           LIMIT 1");

                    if ($PowerBB->DB->sql_num_rows($check_query) == 0)
                    {
                        $lang_str = 'الموضوع الأصلي';
                        $link_text = stristr($FeedsInfo['text'], "{rss:link}") ? "\n\n [url=".$Item['LINK']."]".$lang_str."[/url]" : "";

                        $content = isset($Item['CONTENT:ENCODED']) ? $Item['CONTENT:ENCODED'] : (isset($Item['DESCRIPTION']) ? $Item['DESCRIPTION'] : '');
                        $bb_text = $PowerBB->Powerparse->html2bb($content) . $link_text;

                        $SubjectArr = array();
                        $SubjectArr['get_id'] = true;
                        $SubjectArr['field'] = array();
                        $SubjectArr['field']['title'] = $CleanTitle;
                        $SubjectArr['field']['text'] = $bb_text;
                        $SubjectArr['field']['writer'] = $MemberInfo['username'];
                        $SubjectArr['field']['write_time'] = $PowerBB->_CONF['now'];
                        $SubjectArr['field']['native_write_time'] = $PowerBB->_CONF['now'];
                        $SubjectArr['field']['section'] = $forum_id;
                        $SubjectArr['field']['icon'] = 'look/images/icons/i1.gif';
                        $SubjectArr['field']['review_subject'] = $section_row['review_subject'] ? '1' : '0';
                        $SubjectArr['field']['sec_subject'] = ($section_row['sec_section'] || $section_row['hide_subject']) ? '1' : '0';
                        $Insert = $PowerBB->subject->InsertSubject($SubjectArr);
                        $subject_id = $PowerBB->subject->id;

                        if($Insert) {

                            $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET posts=posts+1, lastpost_time='" . $PowerBB->_CONF['now'] . "' WHERE id='" . $MemberInfo['id'] . "'");
                            $this->AddAutomaticTags($subject_id, $CleanTitle);
                           $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['feeds'] . " SET feeds_time ='" . $PowerBB->_CONF['now'] . "' where id = '" . $FeedsInfo['id'] . "'");

                        }
                    }
                }


            }
        }
    }

    private function CleanTitleForCheck($title) {
        global $PowerBB;
        $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
        $title = strip_tags($title);
        $title = $PowerBB->functions->CleanVariable($title, 'html');
        return trim($title);
    }

    private function AddAutomaticTags($subject_id, $title) {
        global $PowerBB;
        if ($PowerBB->_CONF['info_row']['add_tags_automatic'] != '1') return;

        $words = explode(' ', $title);
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) >= 6) {
                $InsertArr = array();
                $InsertArr['field'] = array();
                $InsertArr['field']['tag_id'] = "";
                $InsertArr['field']['subject_id'] = $subject_id;
                $InsertArr['field']['tag'] = $PowerBB->functions->CleanVariable($word, 'html');
                $InsertArr['field']['subject_title'] = $PowerBB->functions->CleanVariable($title, 'html');
                $PowerBB->core->Insert($InsertArr, 'tags_subject');
            }
        }
    }


}