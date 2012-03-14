<?php
/*
 * 
 * Project Name : Sticky topics
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * $Rev: 13 $
 * $Date: 2011-01-30 17:41:35 +0300 (Sun, 30 Jan 2011) $
 * $LastChangedDate: 2011-01-30 17:41:35 +0300 (Sun, 30 Jan 2011) $
 * 
 */
$config=array();

define('STICKYTOPICS_VERSION', '1.0.6');

$config['table']['stickytopics']                = '___db.table.prefix___stickytopics';

// Позволять администраторам блогов управлять прикрепленными записями в их блогах
$config['allow_blog_admins'] = true;

// Позволять пользователям управлять прикрепленными записями в их персональных блогах
$config['allow_personal_blogs'] = true;

// Выводить максимум столько записей при фильтрации топиков по названию. Это МАКСИМУМ.
// Зачастую их может получится меньше
$config['limit_filter_topics'] = 100;

Config::Set('router.page.stickytopics', 'PluginStickytopics_ActionStickytopics');

return $config;
?>