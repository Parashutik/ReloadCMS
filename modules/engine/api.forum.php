<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
define('FORUM_PATH',    DATA_PATH . 'forum/');
class forum{
	var $topics = array();
	var $error = '';
	var $sort_all = array();
	
	function  __construct(){
		if(!$this->loadTopicsData()){
			return false;
		}
	}
	
	function loadTopicsData(){
		if(!is_readable(FORUM_PATH . 'topic_index.dat')){
			$this->error = __('Cannot load topics data');
			return false;
		}
		$content = file_get_contents(FORUM_PATH . 'topic_index.dat');
		if(($topics = @unserialize($content)) == false){
			$this->error = __('Topics data corrupted');
			return false;
		}
		$this->topics = $topics;
		return true;
	}
	
	function getTopicData($topic_id){
		return $this->topics[$topic_id];
	}
	
	function getFreshTopics($number){
		$this->sortTopics($this->sort_all);
		return array_slice($this->sort_all, 0, $number);
	}
	
	function sortTopics(&$output, $method = 'all'){
		switch ($method){
			case 'all' :
				$output = array();
				foreach ($this->topics as $topic_id => $topic) {
    				if(!empty($topic)){
        				$output[$topic['last_reply']] = $topic_id;
    				}
				}
				krsort($output);
				return true;
				break;
		}
	}
}

?>