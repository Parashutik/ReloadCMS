<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if ($system->current_point!='__MAIN__'){
//Show current polls
$cpolls = new polls;
$cpolls->openCurrentPolls();

if(!empty($_POST['vote'])){
    if(!$cpolls->voteInPoll($_POST['vote'], $_POST['poll_vote'], $_SERVER['REMOTE_ADDR'])){
        show_error($cpolls->lasterror);
    } else {
        $cpolls->close(true, false);
    }
}

$result = '';
if($polls = $cpolls->getCurrentPolls()){
    foreach ($polls as $poolid => $poll){
        $poll['voted'] = $cpolls->isVotedInPoll($poolid, $_SERVER['REMOTE_ADDR']);
        $poll['id'] = $poolid;
        $result .= rcms_parse_module_template('poll.tpl', $poll);
    }
}
show_window(__('Poll'), $result, 'center');
} else {
//Show Archive of Polls
$polls = new polls;
$polls->openCurrentPolls();

$result = '';
$cpolls = array_reverse($polls->getArchivedPolls());
if(!empty($cpolls)){
	foreach ($cpolls as $poll){
    	$poll['voted'] = true;
    	$result .= rcms_parse_module_template('poll.tpl', $poll);
	}
} else {
	$result = __('Archive poll is empty');
}
$system->config['pagename'] = __('Polls archive');
show_window(__('Polls archive'), $result);
}
?>