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

class PluginStickytopics_ModuleStickytopics_EntityStickytopic extends Entity
{
	public function getId() {
		return $this->_aData['sticky_id'];
	}
	public function getActive() {
		return $this->_aData['active'];
	}
	public function getBlogId() {
		return $this->_aData['blog_id'];
	}
	public function getTopicId() {
		return $this->_aData['topic_id'];
	}
	public function getDateStart() {
		return substr($this->_aData['date_start'],0,10);
	}
	public function getDateFinish() {
		return substr($this->_aData['date_finish'],0,10);
	}
	public function getTopicOrder() {
		return $this->_aData['topic_order'];
	}
	public function setBlogId($data) {
		$this->_aData['blog_id']=$data;
	}
	public function setId($data) {
		$this->_aData['sticky_id']=$data;
	}
	public function setActive($data) {
		$this->_aData['active']=$data;
	}
	public function setTopicId($data) {
		$this->_aData['topic_id']=$data;
	}
	public function setTopicOrder($data) {
		$this->_aData['topic_order']=$data;
	}
	public function setDateStart($data) {
		$this->_aData['date_start']=$data;
	}
	public function setDateFinish($data) {
		$this->_aData['date_finish']=$data;
	}
}
?>