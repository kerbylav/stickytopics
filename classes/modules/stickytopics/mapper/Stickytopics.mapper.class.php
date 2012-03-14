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

function addAnd($what,$was)
{
	if (!empty($was)) return "$was AND $what"; else return $what;
}

Class PluginStickytopics_ModuleStickytopics_MapperStickytopics extends Mapper {

	public function GetTopicsByTitlePart($part,$blogId=null){
		if (($blogId!==null) && ($blogId!=0))
		{
			$sWhere=" blog_id=?";
		}
		else $sWhere=" 1=1";

		$sWhere=" topic_title LIKE ? AND ".$sWhere;

		$sql="SELECT topic_id FROM ".Config::Get('db.table.topic')." WHERE $sWhere LIMIT 0,".Config::Get('plugin.stickytopics.limit_filter_topics');

		$res=array();
		if ($aRows=$this->oDb->select($sql,"%$part%",$blogId))
		{
			foreach ($aRows as $aRow)
			$res[]=$aRow['topic_id'];
		}
		return $res;
	}

	public function GetStickyTopicByTopicId($topic_id,$blog_id=null)
	{
		$sql = "SELECT * FROM ".Config::Get('plugin.stickytopics.table.stickytopics')." WHERE topic_id=?";
		if ($blog_id!==null) $sql.=" AND blog_id=?";

		if ($aRow=$this->oDb->selectRow($sql,$topic_id,$blog_id)) {
			return Engine::GetEntity('PluginStickytopics_ModuleStickytopics_EntityStickytopic',$aRow);
		}
		return false;
	}

	public function GetStickyTopicsByArrayId($aTopicId)
	{
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);

		$sql = "SELECT * FROM ".Config::Get('plugin.stickytopics.table.stickytopics')." WHERE topic_id IN(?a)";
		$res=array();
		if ($aRows=$this->oDb->select($sql,$aTopicId))
		{
			foreach ($aRows as $aRow)
			{
				$res[$aRow['topic_id']]=Engine::GetEntity('PluginStickytopics_ModuleStickytopics_EntityStickytopic',$aRow);
			}
		} else return false;
		return $res;
	}

	public function GetStickyTopicById($sticky_id)
	{
		$sql = "SELECT * FROM ".Config::Get('plugin.stickytopics.table.stickytopics')." WHERE sticky_id=?";
		if ($aRow=$this->oDb->selectRow($sql,$sticky_id)) {
			return Engine::GetEntity('PluginStickytopics_ModuleStickytopics_EntityStickytopic',$aRow);
		}
		return false;
	}

	public function GetStickyTopics($blogId,$only_ids=false,$only_active=true,$checkDate=true)
	{
		$sql = "SELECT * FROM ".Config::Get('plugin.stickytopics.table.stickytopics');
		$filter="";
		if ($blogId!==null) $filter=addAnd("blog_id=?d",$filter);
		if ($only_active) $filter=addAnd("active=TRUE",$filter);
		if ($checkDate) $filter=addAnd("(date_start<=CURDATE()) and (date_finish>CURDATE())",$filter);
		if (!empty($filter)) $sql.=" WHERE $filter";
		$sql.=" ORDER BY topic_order";
		if ($aRows=$this->oDb->select($sql,$blogId)) {
			$rid=array();
			foreach ($aRows as $aRow) $rid[]=$aRow['topic_id'];

			if ($only_ids) return $rid;

			return $this->Topic_GetTopicsByArrayId($rid);
		}
		return array();
	}

	public function AddStickyTopic(PluginStickytopics_ModuleStickytopics_EntityStickytopic $oTopic)
	{
		$sql="select max(topic_order) as m from ".Config::Get('plugin.stickytopics.table.stickytopics')." where blog_id=?";
		if ($aRow=$this->oDb->selectRow($sql,$oTopic->getBlogId()))
		{
			$sql = "INSERT INTO ".Config::Get('plugin.stickytopics.table.stickytopics')."
			(
				active,
				blog_id,
				topic_id,
				date_start,
				date_finish,
				topic_order
			)
			VALUES(?,?d,?d,?,?,?)
		";			
			if (($iId=$this->oDb->query($sql,$oTopic->getActive(),$oTopic->getBlogId(),$oTopic->getTopicId(),
			$oTopic->getDateStart(),$oTopic->getDateFinish(),$aRow['m']+1)))
			{
				return $iId;
			}
		}
		return false;
	}

	public function UpdateStickyTopic(PluginStickytopics_ModuleStickytopics_EntityStickytopic $oTopic)
	{
		$sql = "UPDATE ".Config::Get('plugin.stickytopics.table.stickytopics')."
			SET
				active=?,
				blog_id=?d,
				topic_id=?d,
				date_start=?,
				date_finish=?,
				topic_order=?
			WHERE
			sticky_id=?
		";			
		if (($this->oDb->query($sql,$oTopic->getActive(),$oTopic->getBlogId(),$oTopic->getTopicId(),
		$oTopic->getDateStart(),$oTopic->getDateFinish(),$oTopic->getTopicOrder(),$oTopic->getId())))
		{
			return true;
		}
		return false;
	}

	public function DeleteStickyTopic($iStickyId) {
		$sql = "DELETE FROM ".Config::Get('plugin.stickytopics.table.stickytopics')."
			WHERE
				sticky_id = ?d				
		";			
		if ($this->oDb->query($sql,$iStickyId)) {
			return true;
		}
		return false;
	}

}
?>