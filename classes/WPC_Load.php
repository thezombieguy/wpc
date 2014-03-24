<?php

/**
 * Loads extra modules
 *
 * @author Bryan Trudel
 * @package WPC
 */
class WPC_Load
{

	/**
	 * load all files in the wpc directory with wpc_ folders.
	 * @return void require the files
	 */
	public static function load()
	{
		$loadDir = WPC_TEMPLATE_DIR.'/'.WPC_NAMESPACE.'/';

		$dirs = scandir($loadDir);
		
		foreach($dirs as $dir)
		{
			if(preg_match('/'.WPC_NAMESPACE.'_/', $dir))
			{
				require_once($loadDir.$dir.'/'.$dir.'.php');
			}
		}

	}


}