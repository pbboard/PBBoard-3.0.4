<?php
class PowerBBPager
{
	var $total;			// The total number of rows
	var $perpage;			// How many rows per page
	var $count;			// Variable which help us to do pagers
	var $location;			// The web page location
    var $current_page;
	var $pages_number;		// The number of pages
	var $var_name;			// The count's variable name
	var $print_style = array();	// The style of print
	var $limit = 7;                 // How many of pages will print?
	var $i = 0;			// Will use it in loop
	var $x;				// Will use it in loop
	var $p;

	function SetInformation($style)
	{
		$this->print_style = $style;

	}

	/**
	 * This function setup important variables and count the pages number
	 */
	function start($total,$perpage,$count,$location,$var)
	{
            global $PowerBB;
            $this->total 		= 	($total < 0) ? 0 : $total;
            $this->perpage 		= 	($perpage < 0) ? 10 : $perpage;
            $this->count 		= 	($count < 0) ? 0 : $count;
            $this->location		=	$PowerBB->functions->rewriterule($location);

            $this->var_name		=	$var;

            if (($this->count <> 0) && ($this->count > 0) && ($this->count < $this->total) && (($this->count % $this->perpage) === 0)){
                $this->current_page =  $this->count / $this->perpage;
            }else{
                $this->current_page = 0;

            }
            $this->current_page++;
            $this->pages_number = 	@ceil($this->total/$this->perpage);
            if ($PowerBB->_CONF['info_row']['page_max'] == 1
            or !intval($PowerBB->_CONF['info_row']['page_max']))
            {
             $this->limit = $PowerBB->_CONF['info_row']['page_max']+1;
            }
            elseif ($PowerBB->_CONF['info_row']['page_max'] == 2)
            {
             $this->limit = $PowerBB->_CONF['info_row']['page_max'];
            }
            elseif ($PowerBB->_CONF['info_row']['page_max'] == 2)
            {
             $this->limit = $PowerBB->_CONF['info_row']['page_max'] - 1;
            }
            elseif ($PowerBB->_CONF['info_row']['page_max'] > 3)
            {
             $this->limit = $PowerBB->_CONF['info_row']['page_max'] - 2;
            }
	}

	/**
	 * Return output which contain pages with links
	 */
	function show()
	{
            global $PowerBB;

        $this->print_style 	= 	array();
  		$this->print_style[0] 	= 	$PowerBB->template->content('pager_style_part1');
  		$this->print_style[1] 	= 	$PowerBB->template->content('pager_style_part2');
  		$this->print_style[2] 	= 	$PowerBB->template->content('pager_style_part3');
  		$this->print_style[3] 	= 	$PowerBB->template->content('pager_style_part4');

                /* check if there is any pages to display pager */
                if ($this->pages_number !== 0){
                    $output = $this->_proc($this->print_style[0]);
                    $vactor = floor(($this->limit) / 2);
                    if (($this->limit + 2) <= $this->pages_number){
                        if (($this->current_page - $vactor) <= 1){
                            for($this->x = 1;$this->x <= ($this->limit + 1);$this->x++)
                                $output .= $this->output_style($this->x);
                            $this->x = $this->pages_number;
                            $output .= $this->output_style($this->x);
                        }elseif (($this->current_page + $vactor) >= $this->pages_number){
                            $this->x = 1;
                            $output .= $this->output_style($this->x);
                            for($this->x = ($this->pages_number - $this->limit);$this->x <= $this->pages_number; $this->x++)
                                $output .= $this->output_style($this->x);
                        }else{
                            $this->x = 1;
                            $output .= $this->output_style($this->x);
                            $oddeven = ($this->limit % 2) ? 0 : 1;
                            for($this->x = ($this->current_page - $vactor + $oddeven);$this->x <= ($this->current_page + $vactor); $this->x++)
                                $output .= $this->output_style($this->x);
                            $this->x = $this->pages_number;
                            $output .= $this->output_style($this->x);
                        }
                    }else{
                        for($this->x = 1;$this->x <= $this->pages_number;$this->x++){
                            $output .= $this->output_style($this->x);
                        }
                    }

                }
                          $output .= $this->_proc($this->print_style[3]);

		return $output;
	}

        function output_style($_x){
            $this->i = ($_x - 1) * $this->perpage;
            if ($_x == $this->current_page)
                return $this->_proc($this->print_style[1]);
            else
                return $this->_proc($this->print_style[2]);
        }

	function _proc($string)
	{
	global $PowerBB;

		/**
		 * [l] = location
		 * [v] = var_name
		 * [c] = i
		 * [p] = x
		 */
		$start = $PowerBB->_GET['count'];
		$per_page =  $this->perpage;
		$next = $start + 1;

        $prev = $start - 1;
        //$this->last = $this->pages_number*$this->perpage-$this->perpage;
          $this->last = $this->pages_number;

        if($start == "0")
        {
          $n_page = "1";
        }
        else
        {
          $n_page = $start/$per_page+1;
        }
        // $this->pages_number ÚÏÏ ÌãíÚ ÇáÕÝÍÇÊ
        // $per_page ÚÏÏ ÇáÑÏæÏ Ýí ßá ÕÝÍÉ
        // $this->total ÚÏÏ ÌãíÚ ÇáÑÏæÏ
        // $this->x ÑÞã ÇáÕÝÍÉ

		$string = str_replace('[l]',$this->location,$string);
		$string = str_replace('[v]',$this->var_name,$string);
		$string = str_replace('[c]',$this->x,$string);
        $string = str_replace('[p]',$this->x,$string);
        $this->pages_number = 	@ceil($this->total/$this->perpage);
			if ($PowerBB->_CONF['info_row']['content_dir'] == 'ltr'
			or $PowerBB->_CONF['LangDir'] == 'ltr')
			{
		       $align ='right';
			}
			else
			{
		       $align ='left';
			}
        $PowerBB->_CONF['template']['_CONF']['lang']['Pagenum'] = str_replace('%no%',@ceil($n_page),$PowerBB->_CONF['template']['_CONF']['lang']['Pagenum']);
        $PowerBB->_CONF['template']['_CONF']['lang']['Pagenum'] = str_replace('%pnu%',$this->pages_number,$PowerBB->_CONF['template']['_CONF']['lang']['Pagenum']);
        $string = str_replace('[align]',$align,$string);
        $string = str_replace('[Pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Pagenum'],$string);
        $string = str_replace('[last_page]',$PowerBB->_CONF['template']['_CONF']['lang']['last_page'],$string);
        $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
        $string = str_replace('[Go_to_the_page]',$PowerBB->_CONF['template']['_CONF']['lang']['Go_to_the_page'],$string);
        $string = str_replace('[Go]',$PowerBB->_CONF['template']['_CONF']['lang']['Go'],$string);
        //
         $menu_open_display ='style="visibility: hidden; display: none;"';
        if ($PowerBB->_GET['page'] == 'forum')
         {
         $string = str_replace('[id]',$PowerBB->_GET['id'],$string);
         $string = str_replace('[action]','index.php?page=misc&amp;pagenav_forum=1',$string);
         $string = str_replace('[name]','section_id',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         }
         elseif ($PowerBB->_GET['page'] == 'topic')
         {
         $string = str_replace('[id]',$PowerBB->_GET['id'],$string);
         $string = str_replace('[action]','index.php?page=misc&amp;pagenav=1',$string);
         $string = str_replace('[name]','subject_id',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         }
         elseif ($PowerBB->_GET['page'] == 'pm_list')
         {
         $string = str_replace('[id]',$PowerBB->_GET['folder'],$string);
         $string = str_replace('[action]','index.php?page=misc&amp;pagenav_pm=1',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         }
         elseif ($PowerBB->_GET['page'] == 'search')
         {
         $string = str_replace('[id]',$PowerBB->_CONF['template']['PagerLocation'],$string);
         $string = str_replace('[action]','index.php?page=misc&amp;pagenav_search=1',$string);
         $string = str_replace('[name]','location',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         }
         elseif ($PowerBB->_GET['page'] == 'member_list')
         {
         $string = str_replace('[id]','',$string);
         $string = str_replace('[action]','index.php?page=misc&amp;pagenav_memberlist=1',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         }
         elseif ($PowerBB->_GET['page'] == 'latest_reply')
         {
         $string = str_replace('[id]','',$string);
         $string = str_replace('[action]','index.php?page=latest_reply&amp;today=1',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         $string = str_replace('&amp;count=','-',$string);

         }
         elseif ($PowerBB->_GET['page'] == 'latest')
         {
         $string = str_replace('[id]','',$string);
         $string = str_replace('[action]','index.php?page=latest&amp;today=1',$string);
         $string = str_replace('[menu_open_gif]',$menu_open,$string);
         $string = str_replace('[Jump_between_pages]',$PowerBB->_CONF['template']['_CONF']['lang']['Jump_between_pages'],$string);
         $string = str_replace('&amp;count=','-',$string);
         }
         else
         {
         $string = str_replace('[menu_open_gif]',$menu_open_display,$string);
         }
          /////

         if (!($start>=$this->total-$this->perpage)){
          $pnext =  "<a href='".$this->location."&amp;".$this->var_name."=".$next."'>&gt;</a>";
          $string = str_replace('[next]',$pnext,$string);
          }
          else
          {
          $string = str_replace('[next]',"&gt;",$string);
          }
         if ($start){
          $pprev =  "<a href='".$this->location."&amp;".$this->var_name."=".$prev."'>&lt;</a>";
          $string = str_replace('[prev]',$pprev,$string);
          }
          else
          {
          $string = str_replace('[prev]',"&lt;",$string);
          }
         $string = str_replace('[last]',$this->last,$string);
         $pagenav = $PowerBB->functions->range_key();
         $string = str_replace('pageid',"pagenav.".$pagenav,$string);
         $PowerBB->template->assign('pageid',"pagenav.".$pagenav);
        if(@ceil($n_page) > $this->pages_number)
        {
         $url = $this->location."&amp;".$this->var_name."=".$this->last;
	  	 echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=$url\">\n";
        }
		return $string;
	}
}

?>
