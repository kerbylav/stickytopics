<?php
/*
 * 
 * Project Name : Sticky topics
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * $Rev: 6 $
 * $Date: 2011-01-30 15:41:27 +0300 (Sun, 30 Jan 2011) $
 * $LastChangedDate: 2011-01-30 15:41:27 +0300 (Sun, 30 Jan 2011) $
 * 
 */

if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginStickytopics extends Plugin
{
	protected $sTemplatesUrl = "";

	public function Activate()
	{
		if (!$this->isTableExists('prefix_stickytopics')) {
			$this->ExportSQL(dirname(__FILE__).'/install.sql');
		}

		return true;
	}


	public function Deactivate()
	{
		return true;
	}

	public function Init()
	{
		$sTemplatesUrl = Plugin::GetTemplatePath('PluginStickytopics');
	}

}

?>
