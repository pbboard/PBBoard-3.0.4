<?php

class PowerBBFixup
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function RepairTables()
	{
		$returns = array();

		foreach ($this->Engine->table as $k => $v)
		{
			$query = $this->Engine->DB->sql_query('REPAIR TABLE ' . $v);

			if ($query)
			{
				$returns[$v] = true;
			}
			else
			{
				$returns[$v] = false;
			}
		}

		return $returns;
	}
}

?>
