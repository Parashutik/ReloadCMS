<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

/**
 * Misc ReloadCMS packages
 *
 * @author DruidVAV
 * @package ReloadCMS
 */
$lightbox_config = unserialize(@file_get_contents(CONFIG_PATH . 'lightbox.ini'));

/**
 * Function recursively check if $needle is present in $haystack
 *
 * @param mixed $needle
 * @param array $haystack
 * @return boolean
 */

function rcms_in_array_recursive($needle, $haystack) {
    foreach ($haystack as $value){
        if(is_array($value)) return rcms_in_array_recursive($needle, $value);
        else return in_array($needle, $haystack);
    }
}

function in_array_i($needle, $haystack) {
    $needle = strtolower(htmlentities($needle));
    if(!is_array($haystack)) return false;
    foreach ($haystack as $value){
        $value = strtolower(htmlentities($value));
        if($needle == $value) return true;
    }
    return false;
}

function rcms_htmlspecialchars_recursive($array) {
    foreach ($array as $key =>$value){
        if(is_array($value)) $array[$key] = rcms_htmlspecialchars_recursive($value);
        else $array[$key] = htmlspecialchars($value);
    }
    return $array;
}
/**
 * Recursively stripslashes array.
 *
 * @param array $array
 * @return boolean
 */
function stripslash_array(&$array){
    foreach ($array as $key => $value) {
        if(is_array($array[$key])) stripslash_array($array[$key]);
        else $array[$key] = stripslashes($value);
    }
    return true;
}

/**
 * Shows redirection javascript.
 *
 * @param string $url
 */
function rcms_redirect($url, $header = false) {
    if($header){
        @header('Location: ' . $url);
    } else {
        echo '<script type="text/javascript">document.location.href="' . $url . '";</script>';
    }
}

/**
 * Sends e-mail.
 *
 * @param string $to
 * @param string $from
 * @param string $sender
 * @param string $encoding
 * @param string $subj
 * @param string $text
 * @return boolean
 */
function rcms_send_mail($to, $from, $sender, $encoding, $subj, $text) {
	$headers = 'From: =?'.$encoding.'?B?' . base64_encode($sender) . '?= <' . $from . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= 'Message-ID: <' . md5(uniqid(time())) . "@" . $sender . ">\n";
	$headers .= 'Date: ' . gmdate('D, d M Y H:i:s T', time()) . "\n";
	$headers .= "Content-type: text/plain; charset={$encoding}\n";
	$headers .= "Content-transfer-encoding: 8bit\n";
	$headers .= "X-Mailer: ReloadCMS\n";
	$headers .= "X-MimeOLE: ReloadCMS\n";
	return mail($to, '=?'.$encoding.'?B?' . base64_encode($subj). '?=', $text, $headers);
}

/**
 * Returns random string with selected length
 *
 * @param integer $num_chars
 * @return string
 */
function rcms_random_string($num_chars) {
	$chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');

	list($usec, $sec) = explode(' ', microtime());
	mt_srand($sec * $usec);

	$max_chars = sizeof($chars) - 1;
	$rand_str = '';
	for ($i = 0; $i < $num_chars; $i++)	{
		$rand_str .= $chars[mt_rand(0, $max_chars)];
	}

	return $rand_str;
}

/**
 * Just returns current time 
 *
 * @return integer
 */
function rcms_get_time(){
global $system;
    return (time() + ($system->config['default_tz'])*3600);
}

/**
 * Function that formats date. Similar to date() function but
 * uses timezone and returns localised string
 *
 * @param string $format
 * @param integer $gmepoch
 * @param integer $tz
 * @return string
 */
function rcms_format_time($format, $gmepoch, $tz = ''){
    global $lang, $system;

    if(empty($tz)) $tz = $system->user['tz'];
    
    if ($system->language != 'english'){
        @reset($lang['datetime']);
        while (list($match, $replace) = @each($lang['datetime'])){
            $translate[$match] = $replace;
        }
    }
    return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

/**
 * Return localised date from string generated by date()
 *
 * @param string $string
 * @return string
 */
function rcms_date_localise($string){
    global $lang, $system;
    
    if ($system->language != 'english'){
        @reset($lang['datetime']);
        while (list($match, $replace) = @each($lang['datetime'])){
            $translate[$match] = $replace;
        }
    }
    return ( !empty($translate) ) ? strtr($string, $translate) : $string;
}

/*
 * Return unix format time from mysql time
 * if invalid format return null
 *
 * @param string $string
 * @return string
 */
function sql_to_unix_time($string){
global $system;
//Y-m-d H:i:s => array(H,i,s,m,d,Y);
preg_match_all("#(\d\d\d\d)-(\d\d)-(\d\d) (\d\d):(\d\d):(\d\d)#", $string, $date_time_array);
if (empty($date_time_array[1][0])) $time='';
else $time = mktime(
    $date_time_array[4][0] + $system->config['default_tz'],
    $date_time_array[5][0],
    $date_time_array[6][0],
    $date_time_array[2][0],
    $date_time_array[3][0],
    $date_time_array[1][0]
    );
	return $time;
}

/*
 * Return button, when deleting parent node in DOM
 *
 * @return string
 */
function button_delete_parent(){
return '<img src="'.SKIN_PATH.'neok.gif" onclick="this.parentNode.parentNode.removeChild(this.parentNode)" style="cursor:pointer;vertical-align:middle;" title="'.__('Delete').'" />';
}

function rcms_parse_text_by_mode($str, $mode){
	switch ($mode){
		default:
		case 'check':
			return rcms_parse_text($str, false, false, false, false, false, false);
			break;
		case 'text':
			return rcms_parse_text($str, true, false, true, false, true, true);
			break;
		case 'text-safe':
			return rcms_parse_text($str, true, false, true, false, false, false);
			break;
		case 'html':
			return rcms_parse_text($str, false, true, false, false, true, true);
			break;
		case 'php':
			ob_start();
			eval($str);
			$text = ob_get_contents();
			ob_end_clean();
			return $text;
			break;
		case 'htmlbb':
			return rcms_parse_text($str, true, true, true, false, true, true);
			break;
	}
}

/**
 * Just a stub for backward compatibility.
 *
 * @param string $str
 * @param boolean $bbcode
 * @param boolean $html
 * @param boolean $nl2br
 * @param boolean $wordwrap
 * @param boolean $imgbbcode
 * @return string
 */
function rcms_parse_text($str, $bbcode = true, $html = false, $nl2br = true, $wordwrap = false, $imgbbcode = false, $htmlbbcode = false){
	$level = intval($bbcode);
	if($imgbbcode && $bbcode && $level < 2) $level = 2;
	if($htmlbbcode && $bbcode && $level < 3) $level = 3;
	
    $message = new message($str, $level, $html, $nl2br);
    $message->init_bbcodes();
    $message->parse();
    if($wordwrap) {
    	return '<div style="overflow: auto">' . $message->str . '</div>';
    } else {
    	return $message->str;
    }
}

/**
 * Validates e-mail
 *
 * @param string $text
 * @return boolean
 */
function rcms_is_valid_email($text) {
    if(preg_match('/^([a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*)@((([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))\.)*(([a-z]([-a-z0-9]*[a-z0-9])?)|(#[0-9]+)|(\[((([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\.){3}(([01]?[0-9]{0,2})|(2(([0-4][0-9])|(5[0-5]))))\]))$/', $text))
	   return true;
	else return false;
}

/**
 * Returns bbcode panel code for selected textarea
 *
 * @param string $textarea
 * @return string
 */
function rcms_show_bbcode_panel($textarea){
    return rcms_parse_module_template('bbcodes-panel.tpl', array('textarea' => $textarea));
}

function get_animated_to_array()
{
	$arr=rcms_scandir(SMILES_PATH);
	$arr2 = array();
	foreach ($arr as $key) {
		if (file_exists(SMILES_PATH.basename($key, ".gif").".gif")){
			$arr2['#\['.basename($key, ".gif").'\]#is'] = '<img src="'.SMILES_PATH.$key.'" alt = "'.basename($key, ".gif").'" />';
		}
	}
	return $arr2;
}

function return_hidden_bb_text() {
		if (LOGGED_IN) {
			return '<div class="hidden">\\1</div>';
		} else {
			return '<div class="hidden">'.__('This block only for registered users').'</div>';
		}
	}

/**
 * Message parser class
 *
 * @package ReloadCMS
 */
class message{
    /**
     * Message container
     *
     * @var string
     */
    var $str = '';
    /**
     * Level of bbcode security.
     * 
     * @var integer
     */
    var $bbcode_level = 0; // 0 - no bbcode, 1 - save bbcodes, 2 - all bbcodes
    /**
     * Allow HTML in message
     *
     * @var boolean
     */
    var $html = false;
    /**
     * Perform nl2br in message
     *
     * @var boolean
     */
    var $nl2br = false;
    /**
     * Array of regexps for bbcodes
     *
     * @var array
     */
    var $regexp = array();
    
    var $sr_temp = array();
    
    /**
     * Class constructor
     *
     * @param string $message
     * @param integer $bbcode_level
     * @param boolean $html
     * @param boolean $nl2br
     * @return message
     */
    function message($message, $bbcode_level = 0, $html = false, $nl2br = false){
        $this->str = $message;
        $this->nl2br = $nl2br;
        $this->bbcode_level = $bbcode_level;
        $this->html = $html;
    }
    
    /**
     * BBCodes initialisation. Filling in message::regexp array
     *
     */
    function init_bbcodes(){
	global $system,$lightbox_config;
	$addtolink=empty($system->config['addtolink'])?'':$system->config['addtolink'];

    	$this->regexp[0] = array();
		$this->regexp[1] = array(
		"#\[b\](.*?)\[/b\]#is" => '<span style="font-weight: bold">\\1</span>',
		"#\[(h[1-5])\](.*?)\[/(h[1-5])\]#is" => '<\\1>\\2</\\3>',
		"#\[i\](.*?)\[/i\]#is" => '<span style="font-style: italic">\\1</span>',
		"#\[u\](.*?)\[/u\]#is" => '<span style="text-decoration: underline">\\1</span>',
		"#\[del\](.*?)\[/del\]#is" => '<span style="text-decoration: line-through">\\1</span>',
		"#\[\[ul\]\](.*?)\[/\[ul\]\]#is"     => '<ul>\\1</ul>',
		"#\[\*\](.*?)\[/\*\]#is"             => '<li>\\1</li>',
		"#\[url\][\s\n\r]*(((https?|ftp|ed2k|irc)://|" . RCMS_ROOT_PATH . ")[^ \"\n\r\t\<]*)[\s\n\r]*\[/url\]#is" => '<a href="\\1" '.$addtolink.'>\\1</a>',
		"#\[url\][\s\n\r]*((" . RCMS_ROOT_PATH . ")[^ \"\n\r\t\<]*)[\s\n\r]*\[/url\]#is" => '<a href="\\1" >\\1</a>',
		"#\[url\][\s\n\r]*(www\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is" => '<a href="http://\\1" >\\1</a>',
		"#\[url\][\s\n\r]*((ftp)\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is" => '<a href="\\2://\\1" target="_blank">\\1</a>',
		"#\[url=(\"|&quot;|)(((https?|ftp|ed2k|irc)://)[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="\\2" '.$addtolink.'>\\6</a>',
		"#\[url=(\"|&quot;|)((" . RCMS_ROOT_PATH . ")[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="\\2" >\\5</a>',
		"#\[url=(\"|&quot;|)(www\.[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="http://\\2" >\\4</a>',
		"#\[url=(\"|&quot;|)((ftp)\.[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="\\3://\\2" target="_blank">\\5</a>',
		"#\[mailto\][\s\n\r]*([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)[\s\n\r]*\[/mailto\]#is" => '<a href="mailto:\\1">\\1</a>',
		"#\[mailto=(\"|&quot;|)([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)(\"|&quot;|)\](.*?)\[/mailto\]#is" => '<a href="mailto:\\2">\\5</a>',
		"#\[color=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/color(.*?)\]#is" => '<span style="color:\\2">\\4</span>',
		"#\[size=(\"|&quot;|)([0-9]*)(\"|&quot;|)\](.*?)\[/size(.*?)\]#is" => '<span style="font-size: \\2pt">\\4</span>',
		"#\[align=(\"|&quot;|)(left|right|center|justify)(\"|&quot;|)\](.*?)\[/align(.*?)\]#is" => '<span style="text-align: \\2">\\4</span>',
		"#\[user\]([\d\w]*?)\[/user\]#is" => ' [ <a href="' . RCMS_ROOT_PATH . '?module=user.list&amp;user=\\1">\\1</a> ] ',
		"#\[user=([\d\w]*?)\](.*?)\[/user\]#is" => ' [ <a href="' . RCMS_ROOT_PATH . '?module=user.list&amp;user=\\1">\\2</a> ] ',
		"#\[hidden\](.*?)\[/hidden\]#is" => return_hidden_bb_text()
		);
		
		$this->regexp[1] = array_merge(get_animated_to_array(), $this->regexp[1]);
		if (@$lightbox_config['articles']) {
		$this->regexp[2] = array(
	    "#[\s\n\r]*\[img\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br /><a href="\\1.\\2"  class="gallery"><img src="\\1.\\2" alt="\\2" width="'.$lightbox_config['width'].'px"/></a><br />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(left|right)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br /><img src="\\4.\\5" alt="\\5" align="\\2" style="padding: 5px;" /><br />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(\d+)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br /><img src="\\4.\\5" alt="\\5" width="\\2px" /><br />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(100%|[1-9]?[0-9]%)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br/><img src="\\4.\\5" alt="\\5" width="\\2" /><br/>'
		);
		} else {
		$this->regexp[2] = array(
		"#[\s\n\r]*\[img\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br /><img src="\\1.\\2" alt="\\5" /><br />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(left|right)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<img src="\\4.\\5" alt="\\5" align="\\2" style="padding: 5px;" />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(\d+)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?|".RCMS_ROOT_PATH."[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br /><img src="\\4.\\5" alt="\\5" width="\\2px" /><br />',
		"#[\s\n\r]*\[img=(\"|&quot;|)(100%|[1-9]?[0-9]%)(\"|&quot;|)\][\s\n\r]*([\w]+?://[^ \"\n\r\t<]*?)\.(gif|png|jpe?g)[\s\n\r]*\[/img\][\s\n\r]*#is" => '<br/><img src="\\4.\\5" alt="\\5" width="\\2" /><br/>'
		);		
		}
    }
    
    /**
     * Main parse method. Parses message::str
     *
     */
    function parse(){
		    if (!empty($this->bbcode_level)&&$this->bbcode_level > 2){
			 preg_match_all("#\[html\](.*?)\[/html\]#is", $this->str, $matches);
			 if (!empty($matches[1])) 
			 $this->str = preg_replace("#\[html\](.*?)\[/html\]#is", '{{{html}}}', $this->str);
            }
        if(!$this->html) $this->str = htmlspecialchars($this->str);
        if(!empty($this->bbcode_level)){
            $this->str = preg_replace(array_keys($this->regexp[0]), array_values($this->regexp[0]), ' ' . $this->str . ' ');
            if($this->bbcode_level > 0){
                $this->parseCodeTag();
                $this->parseQuoteTag();
                $this->str = preg_replace_callback("#\[spoiler(=(\"|&quot;|)(.*?)(\"|&quot;|)|)\](.*?)\[/spoiler\]#is", 'rcms_spoiler_tag', $this->str);
                $this->str = preg_replace_callback("#\[offtop(=(\"|&quot;|)(.*?)(\"|&quot;|)|)\](.*?)\[/offtop\]#is", 'rcms_offtop_tag', $this->str);
                $this->str = preg_replace(array_keys($this->regexp[1]), array_values($this->regexp[1]), ' ' . $this->str . ' ');
            }
            if($this->bbcode_level > 1){
                $this->str = preg_replace(array_keys($this->regexp[2]), array_values($this->regexp[2]), ' ' . $this->str . ' ');
            }
            if($this->nl2br){
                $this->str = nl2br($this->str);
				$this->str = preg_replace("#\[nobr\]#","\r\n",$this->str);
            }
            $this->parseUrls();
        }
        $this->str = str_replace(array_keys($this->sr_temp), array_values($this->sr_temp), $this->str);
        if (!empty($matches[1])) {
		$html = array_fill(0,count($matches[1]),'{{{html}}}');
		$this->str = str_replace($html, $matches[1], $this->str);}
        $this->result = $this->str;
    }
    
    /**
     * Parses message::str [qoute|quote="Who"]..[/qoute] bbtag
     *
     */
    function parseQuoteTag(){
		$this->str = preg_replace("#[\s\n\r]*\[quote\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is", '<div class="codetitle"><b>' . __('Quote') . ':</b></div><div class="codetext">\\1</div>', $this->str);
		$this->str = preg_replace("#[\s\n\r]*\[quote=(\"|&quot;|)(.*?)(\"|&quot;|)\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is", '<div class="codetitle"><b>\\2 ' . __('wrote') . ':</b></div><div class="codetext">\\4</div>', $this->str);
		if (preg_match("#\[quote(?:=.*|)\](?:.*)\[/quote\]#is", $this->str)) $this->parseQuoteTag();
    }
    
    /**
     * Parses message::str [code]..[/code] bbtag
     *
     */
    function parseCodeTag(){
        preg_match_all("#[\s\n\r]*\[code\][\s\n\r]*(.*?)[\s\n\r]*\[/code\][\s\n\r]*#is", $this->str, $matches);
		foreach($matches[1] as $oldpart) {
			$newpart = preg_replace("#[\n\r]+#", '', highlight_string(strtr($oldpart, array_flip(get_html_translation_table(HTML_SPECIALCHARS))), true));
			$newpart = preg_replace(array('#\[#', '#\]#'), array('&#91;', '&#93;'), $newpart);
			$tmp = '{SR:' . rcms_random_string(6) . '}';
			$this->sr_temp[$tmp] = $newpart;
			$this->str = str_replace($oldpart, $tmp, $this->str);
		}
		$this->str = preg_replace("#[\s\n\r]*\[code\][\s\n\r]*(.*?)[\s\n\r]*\[/code\][\s\n\r]*#is", '<div class="codetitle"><b>' . __('Code') . ':</b></div><div class="codetext" style="overflow: auto; white-space: nowrap;">\\1</div>', $this->str);
    }
    
    function parseUrls(){
		$this->str = $this->highlightUrls($this->str);
		return true;
	}

	function highlightUrls($string){
		$string = ' ' . $string;
		$string = preg_replace_callback("#(^|[\n\s\r])((https?|ftp|ed2k|irc)://[^ \"\n\r\t<]*)#is", 'rcms_prc_link', $string);
		$string = preg_replace_callback("#(^|[\n\s\r])((www|ftp)\.[^ \"\t\n\r<]*)#is", 'rcms_prc_link_short', $string);
		$string = preg_replace_callback("#(^|[\n\s\r])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", 'rcms_prc_mail', $string);
		$string = mb_substr($string, 1);
		return $string;
	}

}

/**
 * Callback for link replacement
 *
 * @param array $matches
 * @return string
 */
function rcms_prc_link($matches){
global $system;
$addtolink=empty($system->config['addtolink'])?'':$system->config['addtolink'];
    if(mb_strlen($matches[2])>25){
        return ' <a href="' . $matches[2] . '" '.$addtolink.'>' . mb_substr($matches[2], 0, 11) . '...' . mb_substr($matches[2], -11) . '</a>';
    } else return ' <a href="' . $matches[2] . '" '.$addtolink.'>' . $matches[2] . '</a>';
}

/**
 * Callback for short link replacement
 *
 * @param array $matches
 * @return string
 */
function rcms_prc_link_short($matches){
    if(mb_strlen($matches[2])>25){
        return ' <a href="http://' . $matches[2] . '" >' . mb_substr($matches[2], 0, 11) . '...' . mb_substr($matches[2], -11) . '</a>';
    } else return ' <a href="http://' . $matches[2] . '" >' . $matches[2] . '</a>';
}

/**
 * Callback for e-mail replacement
 *
 * @param array $matches
 * @return string
 */
function rcms_prc_mail($matches){
    if(mb_strlen($matches[2])>25){
        return ' <a href="mailto:' . $matches[2] . '@' . $matches[3] . '" target="_blank">' . mb_substr($matches[2], 0, 11) . '...' . mb_substr($matches[2], -11) . '</a>';
    } else return ' <a href="mailto:' . $matches[2] . '@' . $matches[3] . '" target="_blank">' . $matches[2] . '</a>';
}

function rcms_spoiler_tag($matches){
	if(!empty($matches[3])) $title = $matches[3]; else $title = __('Spoiler') . ' (' . __('click to view') . ')';
	return '<div class="codetitle" ><span onclick="openBlock(this);" style="cursor: pointer; cursor: hand;"> + </span>'. $title . '<div class="codetext" style="display:none; margin:0;">'.$matches[5].'</div></div>';
} 

function rcms_offtop_tag($matches){
	if(!empty($matches[3])) $title = $matches[3]; else $title = __('Offtop') . ' (' . __('click to view') . ')';
	return '<div class="codetitle" ><span onclick="openBlock(this);" style="cursor: pointer; cursor: hand;"> + </span>'. $title . '<div class="codetext" style="display:none; margin:0;">'.$matches[5].'</div></div>';
}

function rcms_remove_index($key, &$array, $preserve_keys = false) {
	$temp_array = $array;
	$array = array();
	foreach ($temp_array as $ckey => $value){
		if($key != $ckey){
			if($preserve_keys) {
				$array[$ckey] = $value;
			} else {
				$array[] = $value;
			}
		}
	}
}

/*
* Function for return Get, Post, Request, Cookie values
* @param mixed
* @return mixed
*/
function post($value,$no_value=''){
return (isset($_POST[$value])?$_POST[$value]:$no_value);
}

function get($value,$no_value=''){
return (isset($_GET[$value])?$_GET[$value]:$no_value);
}

function request($value,$no_value=''){
return (isset($_REQUEST[$value])?$_REQUEST[$value]:$no_value);
}

function cookie($value,$no_value=''){
return (isset($_COOKIE[$value])?$_COOKIE[$value]:$no_value);
}

/*
* Short alias for $system->checkForRight($right) 
*
* @return boolean
*/
function cfr($right) {
    global $system;
    if($system->checkForRight($right)) return true;
    else return false;
}

/*
* code from http://detectmobilebrowsers.com/
* checks whether the mobile browser
*
* @return boolean
*/
function is_mobile() {
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) return true;
else return false;
}

function strip_bb_tags($string) {
    return preg_replace('/\[[^\]]+\]/', '', $string);
} 

/*
* Eval the code or function, 
* without loading system templates
* use in ajax technique
* @param string
* @return mixed 
*/
function ajax_answer($function) {
eval($function);
exit();
}

/*
* Return string of initialize TinyMCE
* @param string
* @return string 
*/
function tinymce_init($textarea_id) {
return	'
 tinyMCE.init({
 mode : \'exact\',
 elements : \''.$textarea_id.'\',
 theme : \'advanced\',
 language : \'ru\',
 plugins : \'layer,paste,table,cyberim\',
 theme_advanced_buttons1_add : \'fontselect,fontsizeselect\',
 theme_advanced_buttons2_add : \'pastetext,pasteword,selectall,blockquote,|,forecolor,backcolor\',
 theme_advanced_buttons3_add : \'tablecontrols,|,insertlayer,moveforward,movebackward,absolute\',
 theme_advanced_toolbar_location : \'top\',
 theme_advanced_toolbar_align : \'left\',
 theme_advanced_statusbar_location : \'bottom\',
 theme_advanced_resizing : true,
 paste_auto_cleanup_on_paste : true,
 content_css: \'/css/tinymce.css\',
 extended_valid_elements : \'script[type|language|src]\'
})';
}

/*
* Return string select of enabled/disabled TinyMCE
* @param string
* @return string 
*/
function tinymce_selector($textarea_id,$enable_default=false) {
if ($enable_default) $res='<script type="text/javascript">'.tinymce_init($textarea_id).'</script>'; else $res='';
return '<br/>
'.__('Editor').': 
	<select name = "editor" 
	onchange = "if (this.options[selectedIndex].value==\'show\') {
'.tinymce_init($textarea_id).'
	} else { 
	tinyMCE.get(\''.$textarea_id.'\').remove();
	}">
	<option value="show" '.($enable_default?'selected':'').'>'.__('Show').'</option>
	<option value="hide" '.($enable_default?'':'selected').'>'.__('Hide').'</option>
	</select>
	'.$res.'
	';
}
?>
