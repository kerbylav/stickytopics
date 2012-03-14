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

/**
 * Регистрация хуков
 *
 */
class PluginStickytopics_HookStickytopics extends Hook {

	public function RegisterHook() {
		$this->AddHook('init_action', 'InitAction', __CLASS__);
		$this->AddHook('template_get_sticky_topics', 'GetStickyTopics', __CLASS__);

	}

	public function InitAction() {
		if (Router::GetAction()=='admin' and Router::GetActionEvent()=='stickytopics') {
			Router::Action('stickytopics','stickytopics');
		}
	}

	public function GetStickyTopics(&$aVars)
	{
		if ($aVars['aPaging'])
		if ($aVars['aPaging']['iCurrentPage']>1) return;

		$blog_id=$aVars['blog_id'];
		$oUser=$aVars['oUser'];
		$aStickyTopics=array();

		if (!isset($blog_id)) $blog_id=0;
		if ($blog_id==-1)
		{
			if (isset($oUser) and ($oBlog=$this->Blog_GetPersonalBlogByUserId($oUser->getId())))
			{
				$blog_id=$oBlog->getId();
			}
		}

		$aStickyTopics=$this->PluginStickytopics_Stickytopics_GetStickyTopics($blog_id,true);
		$c=$this->Topic_GetTopicsAdditionalData($aStickyTopics);
		$outName=$aVars['outName'];
		
		if (($aVars['removeSticky']) || (!$outName))
		{
			$b=$aVars['aTopics'];
			$d=array();
			if (isset($b))
			{
				foreach ($b as $k=>$o)
				{
					if (!isset($c[$k]))
					{
						$d[$k]=$o;
					}
				}
			}
		}
		if (!$outName)
		{
			$outName='aTopics';

			$c=array_merge($c,$d);
		}
		if ($aVars['removeSticky'])
		{
			$this->Viewer_Assign('aTopics',$d);
		}
		$this->Viewer_Assign($outName,$c);
	}

}
?>