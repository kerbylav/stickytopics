<?php
/*
 *
 * Project Name : Sticky topics
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * $Rev: 12 $
 * $Date: 2011-01-30 17:28:15 +0300 (Sun, 30 Jan 2011) $
 * $LastChangedDate: 2011-01-30 17:28:15 +0300 (Sun, 30 Jan 2011) $
 *
 */

// Обрабатываем отчеты по визитам роботов
class PluginStickytopics_ActionStickytopics extends ActionPlugin {
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser
	 */
	protected $oUserCurrent=null;

	/**
	 *
	 * Текущий пользователь - администратор сайта
	 * @var bool
	 */
	protected $bIsAdmin=false;

	/**
	 *
	 * Текущий пользователь имеет персональный блог
	 * @var bool
	 */
	protected $bHasPersonal=false;

	/**
	 *
	 * Текущий пользовать имеет блоги, в которых может прикреплять топики
	 * @var unknown_type
	 */
	protected $bCanSet=false;
	
	/**
	 * 
	 * Массив блоков.
	 * 
	 * @var unknown_type
	 */
	protected $aBlocks=array();

	/**
	 * Инициализация
	 *
	 * @return null
	 */
	public function Init() {
		$this->sMenuHeadItemSelect='stickytopics';
		$this->sMenuItemSelect='stickytopics';
		$this->sMenuSubItemSelect='list';

		$this->Viewer_AddHtmlTitle($this->Lang_Get('stickytopics_title'));
			
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error');
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->bIsAdmin=$this->oUserCurrent->isAdministrator();
		/**
		 * Проверяем является ли юзер администратором
		 */
		/*		if (!$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error');
			}*/

		$this->Viewer_AppendStyle(Plugin::GetTemplateWebPath('stickytopics').'css/style.css');
		$this->SetDefaultEvent('list');
	}

	protected function RegisterEvent() {
		$this->AddEventPreg('/^stickytopics$/i','/^$/','EventList');
		$this->AddEventPreg('/^stickytopics$/i','/^list$/i','/^$/','EventList');
		$this->AddEventPreg('/^stickytopics$/i','/^ajax$/i','EventAjax');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	protected function IsUserBlogAdmin($oBlog, $oUser)
	{
		$aUsers=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
		return ((isset($aUsers[$oUser->getId()])) || ($oUser->isAdministrator()));
	}

	protected function EventList() {
		$this->Viewer_AddHtmlTitle($this->Lang_Get('stickytopics_action_list_title'));
		$this->sMenuItemSelect='stickytopics';
		$this->sMenuSubItemSelect='list';

		$uid=$this->oUserCurrent->getId();

		$this->aBlocks['right'][] = Plugin::GetTemplatePath('stickytopics').'block.info.tpl';

		// Получаем список блогов, в которых текущий пользователь является администратором
		$aBlogs=$this->Blog_GetBlogsAllowByUser($this->oUserCurrent);

		$ab=array();
		foreach ($aBlogs as $oBlog)
		{
			if ($this->bIsAdmin)
			{
				$ab[$oBlog->getId()]=$oBlog;
			}
			else
			{
				if (Config::Get('plugin.stickytopics.allow_blog_admins'))
				{
					if ($this->IsUserBlogAdmin($oBlog, $this->oUserCurrent))
					{
						$ab[$oBlog->getId()]=$oBlog;
					}
				}
			}
		}

		$this->bHasPersonal=Config::Get('plugin.stickytopics.allow_personal_blogs') && ($this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId())!=false);

		$this->bCanSet=$this->bIsAdmin || $this->bHasPersonal || (count($ab)>0);

		$this->Viewer_Assign('aBlogsAllow',$ab);
		$this->SetTemplateAction('list');
	}

	protected  function canManageBlog($blog_id)
	{
		if (!$this->oUserCurrent->isAdministrator())
		{
			if (!$oBlog=$this->Blog_GetBlogById($blog_id)) return false;

			if (($oBlog->getType()=='personal') && ($oBlog->getOwnerId()==$this->oUserCurrent->getId()) && (Config::Get('plugin.stickytopics.allow_personal_blogs'))) return true;
				
			if (!$this->IsUserBlogAdmin($oBlog, $this->oUserCurrent)) return false;
				
			if (!Config::Get('plugin.stickytopics.allow_blog_admins')) return false;
		}

		return true;
	}

	protected function EventAjax() {
		$debug=false;
		//$debug=true;

		if (!$debug) $this->Viewer_SetResponseAjax('json');
		$params=Router::GetParams();

		switch ($params[1])
		{
			case 'topics':
				{
					$oViewer=$this->Viewer_GetLocalViewer();
					if (getRequest('blog_id')==-1)
					{
						if ($blog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId()))
						{
							$blog_id=$blog->getId();
						}
						else $blog_id=-1;
					} else $blog_id=getRequest('blog_id');

					if (!$this->canManageBlog($blog_id)) return false;

					$aT=$this->PluginStickytopics_Stickytopics_GetTopicsByTitlePart(getRequest('value'),$blog_id);
					$aT1=$this->PluginStickytopics_Stickytopics_GetStickyTopics($blog_id,true,false,false);
					$aT=array_diff($aT, $aT1);
					$aTopics=$this->Topic_GetTopicsByArrayId($aT);
					$oViewer->Assign("blog_id",$blog_id);
					$oViewer->Assign("aTopics",$aTopics);
					$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath('stickytopics')."/actions/ActionStickytopics/ajax/filter_topics.tpl");
					$this->Viewer_AssignAjax('sText',$sTextResult);
					break;
				}
			case 'stickytopics':
				{
					if (getRequest('blog_id')==-1)
					{
						if ($blog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId()))
						{
							$blog_id=$blog->getId();
						}
						else $blog_id=-1;
					} else $blog_id=getRequest('blog_id');

					if (!$this->canManageBlog($blog_id)) return false;

					$oViewer=$this->Viewer_GetLocalViewer();
					$aInactivates=array();
					$aT=$this->PluginStickytopics_Stickytopics_GetStickyTopics($blog_id,true,false,false);
					if ($aT)
					{
						$aFT=$this->PluginStickytopics_Stickytopics_GetStickyTopicsByArrayId($aT);
						if ($aFT)
						{
							foreach ($aFT as $oST)
							{
								if ((!$oST->getActive())) $aInactivates[$oST->getTopicId()]=true;
							}
						}
					}
					if ($debug)
					{
						print "<pre>";var_dump($aInactivates);print "</pre>";
						print "<pre>";var_dump($aT);print "</pre>";
						print "<pre>";var_dump($aFT);print "</pre>";
					}
					$aTopics=$this->Topic_GetTopicsByArrayId($aT);
					$oViewer->Assign("blog_id",$blog_id);
					$oViewer->Assign("aTopics",$aTopics);
					$oViewer->Assign("aInactivates",$aInactivates);
					$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath('stickytopics')."/actions/ActionStickytopics/ajax/stickytopics.tpl");
					$this->Viewer_AssignAjax('sText',$sTextResult);
					break;
				}
			case 'addsticky':
				{
					if (!$this->canManageBlog(getRequest('blog_id'))) return false;

					$oTopic=Engine::GetEntity('PluginStickytopics_ModuleStickytopics_EntityStickytopic');
					$oTopic->setBlogId(getRequest('blog_id'));
					$oTopic->setTopicId(getRequest('topic_id'));
					$oTopic->setActive(true);
					$oTopic->setDateStart("0000-01-01");
					$oTopic->setDateFinish("3000-01-01");
					if ($this->PluginStickyTopics_Stickytopics_AddStickyTopic($oTopic)===false)
					{
						return false;
					}
					break;
				}
			case 'deletesticky':
				{
					if (!$this->canManageBlog(getRequest('blog_id'))) return false;

					if (getRequest('blog_id')==-1)
					{
						if ($blog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId()))
						{
							$blog_id=$blog->getId();
						}
						else $blog_id=null;
					} else $blog_id=getRequest('blog_id');
					if ($oSTopic=$this->PluginStickyTopics_Stickytopics_GetStickyTopicByTopicId(getRequest('topic_id'),$blog_id))
					{
						if (!$this->PluginStickyTopics_Stickytopics_DeleteStickyTopic($oSTopic->getId()))
						{
							return false;
						}
					}
					break;
				}
			case 'ordersticky':
				{
					// Security
					if (!$oTopic=$this->Topic_GetTopicById(getRequest('topic_id'))) return false;

					if (!$this->canManageBlog($oTopic->getBlogId())) return false;

					if ($oSTopic=$this->PluginStickyTopics_Stickytopics_GetStickyTopicByTopicId(getRequest('topic_id')))
					{
						$aT=$this->PluginStickytopics_Stickytopics_GetStickyTopics($oSTopic->getBlogId(),true,false,false);
						$idx=array_search($oSTopic->getTopicId(), $aT);

						if (getRequest('type')=='up')
						{
							if ($idx>0)
							{
								$oPrev=$this->PluginStickyTopics_Stickytopics_GetStickyTopicByTopicId($aT[$idx-1]);
								$a=$oSTopic->getTopicOrder();
								$oSTopic->setTopicOrder($oPrev->getTopicOrder());
								$oPrev->setTopicOrder($a);
								$this->PluginStickytopics_Stickytopics_UpdateStickyTopic($oSTopic);
								$this->PluginStickytopics_Stickytopics_UpdateStickyTopic($oPrev);
							}
						}
						else
						if (getRequest('type')=='down')
						{
							if ($idx<count($aT)-1)
							{
								$oPrev=$this->PluginStickyTopics_Stickytopics_GetStickyTopicByTopicId($aT[$idx+1]);
								$a=$oSTopic->getTopicOrder();
								$oSTopic->setTopicOrder($oPrev->getTopicOrder());
								$oPrev->setTopicOrder($a);
								$this->PluginStickytopics_Stickytopics_UpdateStickyTopic($oSTopic);
								$this->PluginStickytopics_Stickytopics_UpdateStickyTopic($oPrev);
							}
						}
						else
						return false;
					}
					else
					return false;
					break;
				}
			case 'togglesticky':
				{
					// Security
					if (!$oTopic=$this->Topic_GetTopicById(getRequest('topic_id'))) return false;

					if (!$this->canManageBlog($oTopic->getBlogId())) return false;

					if ($oSTopic=$this->PluginStickyTopics_Stickytopics_GetStickyTopicByTopicId(getRequest('topic_id')))
					{
						$oSTopic->setActive(getRequest('type'));
						return $this->PluginStickytopics_StickyTopics_UpdateStickyTopic($oSTopic);
					}
					else
					return false;
					break;
				}
		}
	}


	/**
	 * Завершение работы Action`a
	 *
	 */
	public function EventShutdown() {
		$this->Viewer_AddMenu('stickytopics', Plugin::GetTemplatePath('stickytopics').'menu.stickytopics.tpl');
		$this->Viewer_Assign('menu', 'stickytopics');
		$this->Viewer_Assign('oUserProfile', $this->oUserCurrent);
		$this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
		$this->Viewer_Assign('sTemplatePath', Plugin::GetTemplatePath('stickytopics'));
		$this->Viewer_Assign('plugin_root_url', '/admin/stickytopics');
		$this->Viewer_Assign('bIsAdmin', $this->bIsAdmin);
		$this->Viewer_Assign('bHasPersonal', $this->bHasPersonal);
		$this->Viewer_Assign('bCanSet', $this->bCanSet);
		foreach ($this->aBlocks as $sGroup=>$aGroupBlocks) {
			$this->Viewer_AddBlocks($sGroup, $aGroupBlocks);
		}

	}
}
?>