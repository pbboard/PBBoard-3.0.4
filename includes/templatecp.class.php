<?php
class PBBTemplate
{
	protected $templates_dir;
	protected $compiler_dir;
	protected $templates_ex;
	protected $template;
	protected $while_num;

	protected $x_loop				=	0;
	protected $size_loop			=	0;
	protected $_while_var			=	null;
	protected $_while_var_num		=	0;
	protected $_foreach_var			=	null;
	protected $_foreach_var_num		=	0;
	private $vars_list				=	array();

	public $vars 					= 	array();
	public $method				=	'file';
	public $while_array				=	array();
	public $foreach_array			=	array();



	public function __construct($PowerBB)
	{
		$this->while_array 		= 	array();
		$this->foreach_array 	= 	array();
		$this->_vars 			= 	array();
		$this->_while_var 		= 	array();
		$this->_foreach_var 	= 	array();
	}

	/**
	 * Set the information
	 */
	function SetInformation($templates_dir,$templates_ex,$method)
	{
		$this->templates_dir 		= 	$templates_dir;
		$this->templates_ex			=	$templates_ex;
		$this->method				=	$method;
	}

	function GetTemplateDir()
	{
		return $this->templates_dir;
	}

	function GetCompilerDir()
	{
		return $this->compilter_dir;
	}

	function GetTemplateExtention()
	{
		return $this->templates_ex;
	}

	/**
	 * Display the template after compile it
	 */
	function display($template_name)
	{
		global $PowerBB;


			if ($PowerBB->_GET['debug'] != 1)
			{
				$this->_TemplateFromFile($template_name);
			}

	}


	function content($template_name)
	{
		global $PowerBB;

		if ($PowerBB->_GET['debug'] != 1)
		{
			return $this->_TemplatepagerFromFile($template_name,true);
		}
	}

	function _TemplateFromFile($template_name,$content=false)
	{
		global $PowerBB;

		if (filesize($this->templates_dir . $template_name . $this->templates_ex) > 0)
		{
					$fp = fopen($this->templates_dir . $template_name . $this->templates_ex,'r');

					if (!$fp)
					{
						return 'ERROR::CAN_NOT_OPEN_THE_FILE';
					}

					$fr = fread($fp,filesize($this->templates_dir . $template_name . $this->templates_ex));

					fclose($fp);

					$this->_CompileTemplate($fr,$template_name);

		}
		else
		{
			trigger_error('ERROR::FILE_SIZE_IS_ZERO',E_USER_ERROR);
		}
	}

	function _TemplatepagerFromFile($template_name,$content=false)
	{
		global $PowerBB;

			if (!$content)
			{
				$this->_GetCompiledFile($template_name,$content);
			}
			else
			{
				return $this->_GetCompiledFile($template_name,$content);
			}
	}


	function _CompileTemplate($string, $filename)
	{
		global $PowerBB;

        if ($filename == 'main_body')
		{
           $string = str_replace("<!--PBBoard_Updates-->",$PowerBB->functions->PBBoard_Updates(),$string);
           $string = str_replace("<!--versioncheck-->",$PowerBB->functions->check_version_date(),$string);
		}

			$string = str_replace("}look/","}look/", $string);

			// CSRF protect all your forms
			//$string = str_ireplace("</form>",'<input type="hidden" name="csrf" value="{$csrf_key}" />'."\n</form>",$string);
			@eval($PowerBB->functions->get_fetch_hooks('admin_template_class_start'));

			// We have loop
			if (preg_match('~\{Des::while}{([^[<].*?)}~',$string)
			or preg_match('~\{Des::while::complete}~',$string))
			{
			$string = $this->_ProccessWhile($string);
			}

			if (preg_match('~\{Des::foreach}{([^[<].*?)}~',$string))
			{
			$string = $this->_ProccessForeach($string);
			}

			if (preg_match('~\{if (.*)\}~',$string))
			{
			$string = $this->_ProccessIfStatement($string);
			}

			$search_array 	= 	array();
			$replace_array 	= 	array();

			$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\[\'([^[<].*?)\'\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\[([^[<].*?)\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\']; ?>';

			$search_array[] 	= 	'~\{template}([^[<].*?){/template}~';
			$replace_array[] 	= 	'<?php $this->display(\'\\1\'); ?>';

			$search_array[] 	= 	'~\{include}([^[<].*?){/include}~';
			$replace_array[] 	= 	'<?php include(\\1); ?>';

			$search_array[] 	= 	'~\{info_row}([^[<].*?){/info_row}~';
			$replace_array[] 	= 	'<?php $this->info_row(\'\\1\'); ?>';

			$search_array[] 	= 	'~\{get_hook}([^[<].*?){/get_hook}~';
			$replace_array[] 	= 	'<?php $this->get_hooks_template(\'\\1\'); ?>';

			//////////

			$string = preg_replace($search_array,$replace_array,$string);

			@eval($PowerBB->functions->get_fetch_hooks('admin_template_class_end'));

			$string = $PowerBB->sys_functions->ReplaceMysqlExtension($string);

          $string = str_replace("['template']['lang']","['template']['_CONF']['lang']",$string);
 		  $string = str_replace("['lang']['addons']","['lang']",$string);
          $string = str_replace("<!--copyright-->",$PowerBB->functions->copyright(),$string);
               $string = str_replace("php if","phpif",$string);
               $string = str_replace("if($.","T54T",$string);

            	$regex_if = '#phpif(.*?){#is';
				$string = preg_replace_callback($regex_if, function($matches) {
					$matches[1] = str_replace('<?php echo ','',$matches[1]);
					$matches[1] = str_replace('; ?>','',$matches[1]);

				    return 'php if'.$matches[1].'{ ';
				}, $string);

               $string = str_replace("T54T","if($.",$string);

            	$regex_elseif = '#elseif(.*?){#is';
				$string = preg_replace_callback($regex_elseif, function($matches) {
					$matches[1] = str_replace('<?php echo ','',$matches[1]);
					$matches[1] = str_replace('; ?>','',$matches[1]);

				    return 'elseif'.$matches[1].'{ ';
				}, $string);

             $string = str_replace(">time(",">_date(",$string);
             $string = str_replace(">date(",">_date(",$string);

			$write  = eval(" ?>".$string."<?php ");
	}

	/**
	 * We have {Des::while} loop , that's mean probably we will fetch information from database
	 */
	function _ProccessWhile($string)
	{
		global $PowerBB;
		$search_array 		= 	array();
		$replace_array 		= 	array();

		// I am sorry, but we _must_ do that
           $string =preg_replace_callback("~\{Des::while}{([^[<].*?)}~i", function ($matches) {
        return $this->_StoreWhileVarName($matches[1]);
         }, $string);

		// If statement _must_ be first
		if (preg_match('~\{if (.*)\}~',$string)
			or preg_match('~if (.*) {~',$string))
		{
			$string = $this->_ProccessIfStatement($string,'while');
		}

		foreach ($this->_while_var as $var_name)
		{
			$search_array[] 	=	'~\{\{\$' . $var_name . '\[([^[<].*?)\]\}\}~';
			$replace_array[] 	=	'$PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]';

			$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
			$replace_array[] 	=	'<?php echo $PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]; ?>';
		}

		$string 	= 	preg_replace($search_array,$replace_array,$string);

		$string 	= 	str_replace('{/Des::while}','<?php $this->x_loop = $this->x_loop + 1; } ?>',$string);
		$string 	= 	str_replace('{Des::while::complete}','',$string);
		$string 	= 	str_replace('{/Des::while::complete}','',$string);

		$this->_while_var 		= 	null;
		$this->_while_var_num 	= 	0;

		return $string;
	}


	function _StoreWhileVarName($varname)
	{
		global $PowerBB;
		$this->_while_var[$this->_while_var_num] = $varname;

		$this->_while_var_num += 1;

		return '<?php $this->x_loop = 0; $this->size_loop = sizeof($PowerBB->_CONF[\'template\'][\'while\'][\'' . $varname . '\']); while ($this->x_loop < $this->size_loop) { ?>';
	}

	function _ProccessForeach($string)
	{
		global $PowerBB;
		$search_array 		= 	array();
		$replace_array 		= 	array();


        $string =preg_replace_callback("~\{Des::foreach}{([^[<].*?)}{([^[<].*?)}~Ui", function ($matches) {
        return $this->_StoreForeachVarName($matches[2],$matches[1]);
         }, $string);

		if (preg_match('~\{if (.*)\}~',$string)
			or preg_match('~if (.*) {~',$string))
		{
			$string = $this->_ProccessIfStatement($string,'foreach');
		}

		foreach ($this->_foreach_var as $var_name)
		{
			// Variable (Without print) :
			//				{$var} -> $var
			$search_array[] 	= 	'~\{{\$' . $var_name . '\}}~';
			$replace_array[] 	= 	'$' . $var_name;

			$search_array[] 	=	'~\{{\$' . $var_name . '\[([^[<].*?)\]}}~';
			$replace_array[] 	=	'$' . $var_name . '[\\1]';

			// Variable :
			//				{$var} -> $var
			$search_array[] 	= 	'~\{\$' . $var_name . '\}~';
			$replace_array[] 	= 	'<?php echo $' . $var_name . '; ?>';

			$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
			$replace_array[] 	=	'<?php echo $' . $var_name . '[\\1]; ?>';
		}

		$search_array[] 	=	'~\{counter}~';
		$replace_array[] 	=	'<?php echo $this->x_loop ?>';

		$search_array[] 	=	'~\{{counter}}~';
		$replace_array[] 	=	'$this->x_loop';

		$string 			= 	preg_replace($search_array,$replace_array,$string);

		$string 			= 	str_replace('{/Des::foreach}','<?php $this->x_loop += 1; } ?>',$string);

		return $string;
	}

	function _StoreForeachVarName($varname,$oldname)
	{
		global $PowerBB;
		$this->_foreach_var[$this->_foreach_var_num] = $varname;

		$this->_foreach_var_num += 1;

		return '<?php foreach ($PowerBB->_CONF[\'template\'][\'foreach\'][\'' . $oldname . '\'] as $' . $varname . ') { ?>';
	}


	function _ProccessIfStatement($string,$type = null)
	{
		global $PowerBB;

		$search_array = array();
		$replace_array = array();

		$search_array[] 	= 	'~\{if (.*)}(.*){/if}~'; // SEE : We have a problem here, \\2 may contain "else" or "elseif"
		$replace_array[] 	= 	'<?php if (\\1) { ?> \\2 <?php } ?>';

		$search_array[] 	= 	'~\{if (.*)}~';
		$replace_array[] 	= 	'<?php if (\\1) { ?>';

		$search_array[] 	= 	'~\{/if}~';
		$replace_array[] 	= 	'<?php } ?>';

		// Elseif statement
		$search_array[] 	= 	'~\{elseif (.*)}(.*){/if}~';
		$replace_array[] 	= 	'<?php elseif (\\1) { ?> \\2 <?php } ?>';

		$search_array[] 	= 	'~\{elseif (.*)}~';
		$replace_array[] 	= 	'<?php } elseif (\\1) { ?>';

		// Else statement
		$search_array[] 	= 	'~\{else}~';
		$replace_array[] 	= 	'<?php } else { ?>';

		$string = preg_replace($search_array,$replace_array,$string);


         //  $string =preg_replace_callback('~\if (.*) \{~i', function ($matches) {
      //  return $this->_ProccessIfStatementVariables($matches[1],$type);
       //  }, $string);

		return $string;
	}

	function _ProccessIfStatementVariables($input,$type = null)
	{
		global $PowerBB;

		$string = 'if ' . $input . ' { ';

		if ($type == 'while')
		{
			foreach ($this->_while_var as $var_name)
			{
				$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
				$replace_array[] 	=	'$PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]';
			}
		}
		elseif ($type == 'foreach')
		{
			foreach ($this->_foreach_var as $var_name)
			{
				// Variable (Without print) :
				//				{$var} -> $var
				$search_array[] 	= 	'~\{\$' . $var_name . '\}~';
				$replace_array[] 	= 	'$' . $var_name;

				$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]}~';
				$replace_array[] 	=	'$' . $var_name . '[\\1]';
			}
		}


		$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\[\'([^[<].*?)\'\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\[([^[<].*?)\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\']';

		$string = preg_replace($search_array,$replace_array,$string);
  		$string = str_replace("['template']['lang']","['template']['_CONF']['lang']",$string);
 		$string = str_replace("['lang']['addons']","['lang']",$string);

		return $string;
	}

	/**
	 * If the template is already compiled , so include it
	 */
	/**
	 * If the template is already compiled , so include it
	 */
	function _GetCompiledFile($template_name,$content=false)
	{
		global $PowerBB;

		// Yeah it's here , include it .
		if (filesize($this->templates_dir . $template_name . $this->templates_ex) > 0)
		{
					$fp = fopen($this->templates_dir . $template_name . $this->templates_ex,'r');

					if (!$fp)
					{
						return 'ERROR::CAN_NOT_OPEN_THE_FILE';
					}

					$fr = fread($fp,filesize($this->templates_dir . $template_name . $this->templates_ex));

					fclose($fp);

					return $fr;

		}
		else
		{
			trigger_error('ERROR::FILE_SIZE_IS_ZERO',E_USER_ERROR);
		}
	}

	// Define variable to use it in template
	function assign($varname,$value)
	{
		global $PowerBB;

		$PowerBB->_CONF['template'][$varname] = $value;
	}

	// Define variable Info Rows to use it in template
	function info_row($value)
	{
		global $PowerBB;
		$this->_CompileTemplate($PowerBB->_CONF['info_row'][$value],0);
	}

	function get_hooks_template($value)
	{
		global $PowerBB;
		return $PowerBB->functions->get_hooks_template($value);
	}
	// Stop script
	function _error($msg)
	{
		global $PowerBB;

		die($msg);

	}

}

?>
