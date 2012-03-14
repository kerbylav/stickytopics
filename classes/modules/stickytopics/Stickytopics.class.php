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
 * Модуль Stickytopics
 *
 */
class PluginStickytopics_ModuleStickytopics extends Module {
	/**
	 * Меппер для сохранения логов в базу данных и формирования выборок по данным из базы
	 *
	 * @var PluginStickytopics_ModuleStickytopics_MapperStickytopics Mapper_Profiler
	 */
	protected  $oMapper;

	/**
	 * Инициализация модуля
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}

	public function AddStickyTopic(PluginStickytopics_ModuleStickytopics_EntityStickytopic $oTopic)
	{
		return $this->oMapper->AddStickyTopic($oTopic);
	}

	public function UpdateStickyTopic(PluginStickytopics_ModuleStickytopics_EntityStickytopic $oTopic)
	{
		return $this->oMapper->UpdateStickyTopic($oTopic);
	}

	public function DeleteStickyTopic($iId)
	{
		return $this->oMapper->DeleteStickyTopic($iId);
	}
	
	public function GetStickyTopicByTopicId($topic_id,$blog_id=null)
	{
		return $this->oMapper->GetStickyTopicByTopicId($topic_id,$blog_id);
	}
	
	public function GetStickyTopicById($topic_id)
	{
		return $this->oMapper->GetStickyTopicById($topic_id);
	}
	
	public function GetStickyTopicsByArrayId($topic_ids)
	{
		return $this->oMapper->GetStickyTopicsByArrayId($topic_ids);
	}
	
	public function GetStickyTopics($blogId,$only_ids=false,$only_active=true,$checkDate=true)
	{
		return $this->oMapper->GetStickyTopics($blogId,$only_ids,$only_active,$checkDate);
	}
	
	public function GetTopicsByTitlePart($part,$blogId=null){
		return $this->oMapper->GetTopicsByTitlePart($part,$blogId);
		
	}
	
	
}
?>