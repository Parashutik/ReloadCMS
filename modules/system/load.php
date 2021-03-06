<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Ban check function                                                         //
////////////////////////////////////////////////////////////////////////////////
function ifbanned($ip){
if (!is_file(CONFIG_PATH . 'bans.ini')) return false;
$banlist = file(CONFIG_PATH . 'bans.ini'); 
    foreach ($banlist as $banstring){
        $ban = '/^' . str_replace('*', '(\d*)', str_replace('.', '\\.', trim($banstring))) . '$/';
        if(preg_match($ban, $ip)){
            return true;
        }
    }
    return false;
}

////////////////////////////////////////////////////////////////////////////////
// Ban check                                                                  //
////////////////////////////////////////////////////////////////////////////////
if (ifbanned($_SERVER['REMOTE_ADDR'])) {
	rcms_log_put('Notification', $this->user['username'], 'Attempt to access from banned IP');
	die('You are banned from this site');
}

// UMASK Must be 000!
umask(000);

////////////////////////////////////////////////////////////////////////////////
// Loading system libraries                                                   //
////////////////////////////////////////////////////////////////////////////////
include_once(SYSTEM_MODULES_PATH . 'filesystem.php');
include_once(SYSTEM_MODULES_PATH . 'etc.php');
include_once(SYSTEM_MODULES_PATH . 'templates.php');
include_once(SYSTEM_MODULES_PATH . 'user-classes.php');
include_once(SYSTEM_MODULES_PATH . 'tar.php');
include_once(SYSTEM_MODULES_PATH . 'system.php');
include_once(SYSTEM_MODULES_PATH . 'compatibility.php');
include_once(SYSTEM_MODULES_PATH . 'formsgen.php');

////////////////////////////////////////////////////////////////////////////////
// Initializing session                                                       //
////////////////////////////////////////////////////////////////////////////////
$system = new rcms_system(post('lang_form'), post('user_selected_skin'));
if(!empty($_POST['login_form'])) {
    $system->logInUser(post('username'), post('password'), !empty($_POST['remember']) ? true : false);
}
if(!empty($_POST['logout_form'])) {
    $system->logOutUser();
}
define('LOGGED_IN', $system->logged_in);
if (empty($system->config['admin_file'])) define('ADMIN_FILE','admin.php');
else define('ADMIN_FILE',$system->config['admin_file']);
// Show some messages about activation or initialization
if(!empty($system->results['user_init'])) show_window('', $system->results['user_init'], 'center');
?>