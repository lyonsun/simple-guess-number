<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forumnav.php 31700 2012-09-24 03:46:59Z zhangjie $
 */
//note 版块forum >> forumnav(版块列表) @ Discuz! X2.5

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

include_once 'forum.php';

class mobile_api {

	//note 程序模块执行前需要运行的代码
	function common() {
		global $_G;
		$forums = array();
		$sql = !empty($_G['member']['accessmasks']) ?
			"SELECT f.fid, f.type, f.name, f.fup, f.status, ff.password, ff.redirect, ff.viewperm, ff.postperm, ff.threadtypes, ff.threadsorts
				FROM ".DB::table('forum_forum')." f
				LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid
				LEFT JOIN ".DB::table('forum_access')." a ON a.uid='$_G[uid]' AND a.allowview>'0' AND a.fid=f.fid
				WHERE f.status='1' ORDER BY f.type, f.displayorder"
			: "SELECT f.fid, f.type, f.name, f.fup, f.status, ff.password, ff.redirect, ff.viewperm, ff.postperm, ff.threadtypes, ff.threadsorts
				FROM ".DB::table('forum_forum')." f
				LEFT JOIN ".DB::table('forum_forumfield')." ff USING(fid)
				WHERE f.status='1' ORDER BY f.type, f.displayorder";

		$query = DB::query($sql);
		//$query = DB::query("SELECT f.fid, f.type, f.name, f.fup, f.status, ff.password, ff.redirect, ff.viewperm, ff.postperm, ff.threadtypes, ff.threadsorts FROM ".DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid LEFT JOIN ".DB::table('forum_access')." a ON a.fid=f.fid AND a.allowview>'0' WHERE f.status='1' ORDER BY f.type, f.displayorder");
		while($forum = DB::fetch($query)) {
			if($forum['redirect'] || $forum['password']) {
				continue;
			}
			if(!$forum['viewperm'] || ($forum['viewperm'] && forumperm($forum['viewperm']))) {
				if($forum['threadsorts']) {
					$forum['threadsorts'] = mobile_core::getvalues(unserialize($forum['threadsorts']), array('required', 'types'));
				}
				if($forum['threadtypes']) {
					$forum['threadtypes'] = unserialize($forum['threadtypes']);
					$unsetthreadtype = false;
					if($_G['adminid'] == 3 && strpos($forum['moderators'], $_G['username']) === false) {
						$unsetthreadtype = true;
					}
					if($_G['adminid'] == 0) {
						$unsetthreadtype = true;
					}
					if($unsetthreadtype) {
						foreach ($forum['threadtypes']['moderators'] AS $k => $v) {
							if(!empty($v)) {
								unset($forum['threadtypes']['types'][$k]);
							}
						}
					}
					$flag = 0;
					foreach($forum['threadtypes']['types'] as $k => $v) {
						if($k == 0) {
							$flag = 1;
							break;
						}
					}
					if($flag == 1) {
						krsort($forum['threadtypes']['types']);
					}
					$forum['threadtypes'] = mobile_core::getvalues($forum['threadtypes'], array('required', 'types'));
				}
				$forums[] = mobile_core::getvalues($forum, array('fid', 'type', 'name', 'fup', 'viewperm', 'postperm', 'status', 'threadsorts', 'threadtypes'));
			}
		}
		$variable['forums'] = $forums;
		mobile_core::result(mobile_core::variable($variable));
	}

	//note 程序模板输出前运行的代码
	function output() {}

}

?>