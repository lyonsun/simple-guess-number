<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hotforum.php 27451 2012-02-01 05:48:47Z monkey $
 */
//note 版块forum >> hotforum(热门版块) @ Discuz! X2.5

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

include_once 'forum.php';

class mobile_api {

	//note 程序模块执行前需要运行的代码
	function common() {
		global $_G;
		loadcache('mobile_hotforum');
		if(!$_G['cache']['mobile_hotforum'] || TIMESTAMP - $_G['cache']['mobile_hotforum']['expiration'] > 3600) {
			$query = DB::query("SELECT f.*, ff.redirect FROM ".DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid WHERE f.status='1' AND f.type='forum' ORDER BY f.todayposts DESC");
			$data = array();
			while($row = DB::fetch($query)) {
				if($row['redirect']) {
					continue;
				}
				list($row['lastpost_tid'], $row['lastpost_subject'], $row['lastpost'], $row['lastposter']) = explode("\t", $row['lastpost']);
				$row['lastpost'] = dgmdate($row['lastpost']);
				$data[] = mobile_core::getvalues($row, array('fid', 'name', 'threads', 'posts', 'lastpost', 'lastposter', 'lastpost_tid', 'lastpost_subject', 'todayposts'));
			}
			$variable = array(
				'data' => $data,
			);
			savecache('mobile_hotforum', array('variable' => $variable, 'expiration' => TIMESTAMP));
		} else {
			$variable = $_G['cache']['mobile_hotforum']['variable'];
		}
		mobile_core::result(mobile_core::variable($variable));
	}

	//note 程序模板输出前运行的代码
	function output() {
	}

}

?>