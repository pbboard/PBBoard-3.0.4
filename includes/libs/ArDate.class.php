<?php
// ----------------------------------------------------------------------
// Copyright (C) 2006 by Khaled Al-Shamaa.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Class Name: Arabic Date
// Filename:   ArDate.class.php
// Original    Author(s): Khaled Al-Sham'aa <khaled.alshamaagmail.com>
// Purpose:    Arabic customization for PHP date function
// ----------------------------------------------------------------------

class ArDate {
      var $mode = 1;

      function ArDate($mode = 1){
          $this->mode = $mode;

          define(ISLAMIC_EPOCH, 1948439.5);
      }

      /**
      * @return TRUE if success, or FALSE if fail
      * param  Integer $mode Output mode of date function where:
      *                              1) Hegri format (Islamic calendar)
      *                              2) Arabic month names used in Middle East countries
      *                              3) Arabic Transliteration of Gregorian month names
      *                              4) Both of 2 and 3 formats together
      * @desc setMode Setting value for $mode scalar
      * @author Khaled Al-Shamaa
      */
      function setMode($mode = 1){
          $flag = true;

          $this->mode = $mode or $flag = false;

          return $flag;
      }

      /**
      * @return Integer Value of $mode properity
      * @desc getMode Getting $mode propority value that refer to output mode format
      *                       1) Hegri format (Islamic calendar)
      *                       2) Arabic month names used in Middle East countries
      *                       3) Arabic Transliteration of Gregorian month names
      *                       4) Both of 2 and 3 formats together
      * @author Khaled Al-Shamaa
      */
      function getMode(){
          return $this->mode;
      }

      /**
      * @return String  Formatted Arabic date string according to the given format string
      *                 using the given integer timestamp or the current local time
      *                 if no timestamp is given.
      * param  String  Format string (same as PHP date function)
      *         Integer Unix timestamp or the current local time if no timestamp is given
      * @desc   Format a local time/date in Arabic string
      * @author Khaled Al-Shamaa
      */
      function _date($format, $timestamp = time){
            if ($this->mode == 1){
                $hj_txt_month[1] = 'محرم';
                $hj_txt_month[2] = 'صفر';
                $hj_txt_month[3] = 'ربيع الأول';
                $hj_txt_month[4] = 'ربيع الثاني';
                $hj_txt_month[5] = 'جمادى الأولى';
                $hj_txt_month[6] = 'جمادى الثانية';
                $hj_txt_month[7] = 'رجب';
                $hj_txt_month[8] = 'شعبان';
                $hj_txt_month[9] = 'رمضان';
                $hj_txt_month[10] = 'شوال';
                $hj_txt_month[11] = 'ذو القعدة';
                $hj_txt_month[12] = 'ذو الحجة';

                $patterns     = array();
                $replacements = array();

                array_push($patterns, '/Y/');    array_push($replacements, 'x1');
                array_push($patterns, '/y/');    array_push($replacements, 'x2');
                array_push($patterns, '/[MF]/'); array_push($replacements, 'x3');
                array_push($patterns, '/n/');    array_push($replacements, 'x4');
                array_push($patterns, '/m/');    array_push($replacements, 'x5');
                array_push($patterns, '/j/');    array_push($replacements, 'x6');
                array_push($patterns, '/d/');    array_push($replacements, 'x7');

                $format =preg_replace($patterns, $replacements, $format);

                $str = date($format, $timestamp);
                $str = $this->en2ar($str);

                list($Y, $M, $D) = split(' ', date('Y m d',$timestamp));

                list($hj_y, $hj_m, $hj_d) = $this->hj_convert($Y, $M, $D);

                $patterns     = array();
                $replacements = array();

                array_push($patterns, '/x1/'); array_push($replacements, $hj_y);
                array_push($patterns, '/x2/'); array_push($replacements, substr($hj_y, -2));
                array_push($patterns, '/x3/'); array_push($replacements, $hj_txt_month[$hj_m]);
                array_push($patterns, '/x4/'); array_push($replacements, $hj_m);
                array_push($patterns, '/x5/'); array_push($replacements, sprintf("%02d", $hj_m));
                array_push($patterns, '/x6/'); array_push($replacements, $hj_d);
                array_push($patterns, '/x7/'); array_push($replacements, sprintf("%02d", $hj_d));

                $str =preg_replace($patterns, $replacements, $str);
            }else{
                $str = date($format, $timestamp);
                $str = $this->en2ar($str);
            }

            return $str;
      }

      /**
      * @return String  Date/time string using Arabic terms
      * param  String  Date/time string using English terms
      * @desc   Translate English date/time terms into Arabic langauge respect selected mode
      * @author Khaled Al-Shamaa
      */
      function en2ar($str){
            $patterns     = array();
            $replacements = array();

            $str = strtolower($str);

            array_push($patterns, '/saturday/');        array_push($replacements, 'السبت');
            array_push($patterns, '/sunday/');          array_push($replacements, 'الأحد');
            array_push($patterns, '/monday/');          array_push($replacements, 'الاثنين');
            array_push($patterns, '/tuesday/');         array_push($replacements, 'الثلاثاء');
            array_push($patterns, '/wednesday/');       array_push($replacements, 'الأربعاء');
            array_push($patterns, '/thursday/');        array_push($replacements, 'الخميس');
            array_push($patterns, '/friday/');          array_push($replacements, 'الجمعة');

            if($this->mode == 2){
                  array_push($patterns, '/january/');   array_push($replacements, 'كانون ثاني');
                  array_push($patterns, '/february/');  array_push($replacements, 'شباط');
                  array_push($patterns, '/march/');     array_push($replacements, 'آذار');
                  array_push($patterns, '/april/');     array_push($replacements, 'نيسان');
                  array_push($patterns, '/may/');       array_push($replacements, 'أيار');
                  array_push($patterns, '/june/');      array_push($replacements, 'حزيران');
                  array_push($patterns, '/july/');      array_push($replacements, 'تموز');
                  array_push($patterns, '/august/');    array_push($replacements, 'آب');
                  array_push($patterns, '/september/'); array_push($replacements, 'أيلول');
                  array_push($patterns, '/october/');   array_push($replacements, 'تشرين أول');
                  array_push($patterns, '/november/');  array_push($replacements, 'تشرين ثاني');
                  array_push($patterns, '/december/');  array_push($replacements, 'كانون أول');
            }elseif($this->mode == 3){
                  array_push($patterns, '/january/');   array_push($replacements, 'يناير');
                  array_push($patterns, '/february/');  array_push($replacements, 'فبراير');
                  array_push($patterns, '/march/');     array_push($replacements, 'مارس');
                  array_push($patterns, '/april/');     array_push($replacements, 'أبريل');
                  array_push($patterns, '/may/');       array_push($replacements, 'مايو');
                  array_push($patterns, '/june/');      array_push($replacements, 'يونيو');
                  array_push($patterns, '/july/');      array_push($replacements, 'يوليو');
                  array_push($patterns, '/august/');    array_push($replacements, 'أغسطس');
                  array_push($patterns, '/september/'); array_push($replacements, 'سبتمبر');
                  array_push($patterns, '/october/');   array_push($replacements, 'أكتوبر');
                  array_push($patterns, '/november/');  array_push($replacements, 'نوفمبر');
                  array_push($patterns, '/december/');  array_push($replacements, 'ديسمبر');
            }elseif($this->mode == 4){
                  array_push($patterns, '/january/');   array_push($replacements, 'كانون ثاني/يناير');
                  array_push($patterns, '/february/');  array_push($replacements, 'شباط/فبراير');
                  array_push($patterns, '/march/');     array_push($replacements, 'آذار/مارس');
                  array_push($patterns, '/april/');     array_push($replacements, 'نيسان/أبريل');
                  array_push($patterns, '/may/');       array_push($replacements, 'أيار/مايو');
                  array_push($patterns, '/june/');      array_push($replacements, 'حزيران/يونيو');
                  array_push($patterns, '/july/');      array_push($replacements, 'تموز/يوليو');
                  array_push($patterns, '/august/');    array_push($replacements, 'آب/أغسطس');
                  array_push($patterns, '/september/'); array_push($replacements, 'أيلول/سبتمبر');
                  array_push($patterns, '/october/');   array_push($replacements, 'تشرين أول/أكتوبر');
                  array_push($patterns, '/november/');  array_push($replacements, 'تشرين ثاني/نوفمبر');
                  array_push($patterns, '/december/');  array_push($replacements, 'كانون أول/ديسمبر');
            }

            array_push($patterns, '/sat/');       array_push($replacements, 'السبت');
            array_push($patterns, '/sun/');       array_push($replacements, 'الأحد');
            array_push($patterns, '/mon/');       array_push($replacements, 'الاثنين');
            array_push($patterns, '/tue/');       array_push($replacements, 'الثلاثاء');
            array_push($patterns, '/wed/');       array_push($replacements, 'الأربعاء');
            array_push($patterns, '/thu/');       array_push($replacements, 'الخميس');
            array_push($patterns, '/fri/');       array_push($replacements, 'الجمعة');

            if($this->mode == 2){
                  array_push($patterns, '/jan/'); array_push($replacements, 'كانون ثاني');
                  array_push($patterns, '/feb/'); array_push($replacements, 'شباط');
                  array_push($patterns, '/mar/'); array_push($replacements, 'آذار');
                  array_push($patterns, '/apr/'); array_push($replacements, 'نيسان');
                  array_push($patterns, '/may/'); array_push($replacements, 'أيار');
                  array_push($patterns, '/jun/'); array_push($replacements, 'حزيران');
                  array_push($patterns, '/jul/'); array_push($replacements, 'تموز');
                  array_push($patterns, '/aug/'); array_push($replacements, 'آب');
                  array_push($patterns, '/sep/'); array_push($replacements, 'أيلول');
                  array_push($patterns, '/oct/'); array_push($replacements, 'تشرين أول');
                  array_push($patterns, '/nov/'); array_push($replacements, 'تشرين ثاني');
                  array_push($patterns, '/dec/'); array_push($replacements, 'كانون أول');
            }elseif($this->mode == 3){
                  array_push($patterns, '/jan/'); array_push($replacements, 'يناير');
                  array_push($patterns, '/feb/'); array_push($replacements, 'فبراير');
                  array_push($patterns, '/mar/'); array_push($replacements, 'مارس');
                  array_push($patterns, '/apr/'); array_push($replacements, 'أبريل');
                  array_push($patterns, '/may/'); array_push($replacements, 'مايو');
                  array_push($patterns, '/jun/'); array_push($replacements, 'يونيو');
                  array_push($patterns, '/jul/'); array_push($replacements, 'يوليو');
                  array_push($patterns, '/aug/'); array_push($replacements, 'أغسطس');
                  array_push($patterns, '/sep/'); array_push($replacements, 'سبتمبر');
                  array_push($patterns, '/oct/'); array_push($replacements, 'أكتوبر');
                  array_push($patterns, '/nov/'); array_push($replacements, 'نوفمبر');
                  array_push($patterns, '/dec/'); array_push($replacements, 'ديسمبر');
            }elseif($this->mode == 4){
                  array_push($patterns, '/jan/'); array_push($replacements, 'كانون ثاني/يناير');
                  array_push($patterns, '/feb/'); array_push($replacements, 'شباط/فبراير');
                  array_push($patterns, '/mar/'); array_push($replacements, 'آذار/مارس');
                  array_push($patterns, '/apr/'); array_push($replacements, 'نيسان/أبريل');
                  array_push($patterns, '/may/'); array_push($replacements, 'أيار/مايو');
                  array_push($patterns, '/jun/'); array_push($replacements, 'حزيران/يونيو');
                  array_push($patterns, '/jul/'); array_push($replacements, 'تموز/يوليو');
                  array_push($patterns, '/aug/'); array_push($replacements, 'آب/أغسطس');
                  array_push($patterns, '/sep/'); array_push($replacements, 'أيلول/سبتمبر');
                  array_push($patterns, '/oct/'); array_push($replacements, 'تشرين أول/أكتوبر');
                  array_push($patterns, '/nov/'); array_push($replacements, 'تشرين ثاني/نوفمبر');
                  array_push($patterns, '/dec/'); array_push($replacements, 'كانون أول/ديسمبر');
            }

            array_push($patterns, '/am/');        array_push($replacements, 'صباحاً');
            array_push($patterns, '/pm/');        array_push($replacements, 'مساءً');

            array_push($patterns, '/st/');        array_push($replacements, '');
            array_push($patterns, '/nd/');        array_push($replacements, '');
            array_push($patterns, '/rd/');        array_push($replacements, '');
            array_push($patterns, '/th/');        array_push($replacements, '');

            $str =preg_replace($patterns, $replacements, $str);

            return $str;
      }

      /**
      * @return Array          Hegri date [int Year, int Month, int Day] (Islamic calendar)
      * param  Integer Year   Gregorian year
      *         Integer Month  Gregorian month
      *         Integer Day    Gregorian day
      * @desc   hj_convert will convert given Gregorian date into Hegri date (Islamic calendar)
      * @author Khaled Al-Shamaa
      */
      function hj_convert($Y, $M, $D){
            $jd = GregorianToJD($M, $D, $Y);

            list($year, $month, $day) = $this->jd_to_islamic($jd);

            return array($year, $month, $day);
      }

      /**
      * @return Array        Hegri date [int Year, int Month, int Day] (Islamic calendar)
      * param  Integer jd   Julian day
      * @desc   jd_to_islamic will convert given Julian day into Hegri date (Islamic calendar)
      * @author Khaled Al-Shamaa
      */
      function jd_to_islamic($jd){
          $jd = floor($jd) + 0.5;
          $year = floor(((30 * ($jd - ISLAMIC_EPOCH)) + 10646) / 10631);
          $month = min(12,ceil(($jd - (29 + $this->islamic_to_jd($year, 1, 1))) / 29.5) + 1);
          $day = ($jd - $this->islamic_to_jd($year, $month, 1)) + 1;

          return array($year, $month, $day);
      }

      /**
      * @return Integer        Julian day
      * param  Integer Year   Hegri year
      *         Integer Month  Hegri month
      *         Integer Day    Hegri day
      * @desc   islamic_to_jd will convert given Hegri date (Islamic calendar) into Julian day
      * @author Khaled Al-Shamaa
      */
      function islamic_to_jd($year, $month, $day){
          return ($day +
                  ceil(29.5 * ($month - 1)) +
                  ($year - 1) * 354 +
                  floor((3 + (11 * $year)) / 30) +
                  ISLAMIC_EPOCH) - 1;
      }

}
?>