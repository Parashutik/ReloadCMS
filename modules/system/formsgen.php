<?php
class InputForm {
	var $options = array();

	var $_rows;
	var $_hiddens;

	var $_elements = array(
	'hidden' => '<input type="hidden" name="%1" value="%2" />',
	'file' => '<input type="file" name="%1" value="" />',
	);

	function __construct ($action = '', $method = 'get', $submit = 'Submit', $reset = '', $target = '', $enctype = '', $name = '', $events = '') {
		$this->options = array(
		'action'  => $action,
		'method'  => $method,
		'target'  => $target,
		'enctype' => $enctype,
		'events'  => $events,
		'submit'  => $submit,
		'name'    => $name,
		'reset'   => $reset,
		);
	}

	function addrow($title, $contents = '', $valign = 'middle', $align = 'left') {
		$a = explode( ",", $align );
		$talign=empty($a[0])?'':$a[0]; 
		$calign=empty($a[1])?$talign:$a[1]; 
		$b = explode( ",", $valign );
		$tvalign=empty($b[0])?'':$b[0]; 
		$cvalign=empty($b[1])?$tvalign:$b[1]; 
		$this->_rows[]=array(
			'title' => $title,
			'contents' => $contents,
			'title_valign' => $tvalign,
			'content_valign' => $cvalign,
			'title_align' => $talign,
			'content_align' => $calign
		);
		end($this->_rows);
		return key($this->_rows);
	}

	function hidden($name, $value) {
		$this->_hiddens[$name] = $value;
	}

	function addbreak($break = "&nbsp;") {
		$this->_rows[] = array('break' => $break);
		end($this->_rows);
		return key($this->_rows);
	}

	function addmessage( $message ){
		$this->_rows[]=array("message"=>$message);
	}


	function show($return = false,$cols=2) {
		$result = '<form action="' . $this->options['action'] . '" method="' . $this->options['method'] . '" name="' . $this->options['name'] . '"';
		$result .= empty($this->options['target']) ? '' : ' target="' . $this->options['target'] . '"';
		$result .= empty($this->options['enctype']) ? '' : ' enctype="' . $this->options['enctype'] . '"';
		$result .= empty($this->options['events']) ? '' : ' ' . $this->options['events'];
		$result .= '>' . "\n";

		if(is_array($this->_hiddens)) {
			foreach($this->_hiddens as $name => $value){
				$result .= str_replace(array('%1', '%2'), array($name, $value), $this->_elements['hidden']) . "\n";
			}
		}

		$result .= '<table border="0" cellspacing="2" cellpadding="2" width="100%">' . "\n";

		if(is_array($this->_rows)) {
			foreach($this->_rows as $key=>$row){
				if(!empty($row['break'])) $result .= "<tr>\n".'  <th colspan="'.$cols.'">' . $row['break'] . "</th>\n</tr>\n";
				elseif(!empty($row['message'])) $result .= "<tr>\n".'  <td colspan="'.$cols.'">' . $row['message'] . "</td>\n</tr>\n";
				elseif (!empty($row['fieldset_start'])) $result .= $row['fieldset_start'];
				elseif (!empty($row['fieldset_end'])) $result .= $row['fieldset_end'];
				else {
					$result .= "<tr>\n";
					$result .= '  <td valign="' . $row['title_valign'] . '" align="' . $row['title_align'] . '" class="row2" ' . ((empty($row['contents'])) ? ' colspan="'.$cols.'"' : '') . '>' . $row['title'] . "</td>\n";
					if(!empty($row['contents'])){
						if (is_array($row['contents'])) {
							foreach ($row['contents'] as $content) {
								$result .= '  <td valign="' . $row['title_valign'] . '" align="' . $row['title_align'] . '" class="row3">' . $content . "</td>\n";
							}
						} else {					
							$result .= '  <td valign="' . $row['title_valign'] . '" align="' . $row['title_align'] . '" class="row3">' . $row['contents'] . "</td>\n";
						}
					}
					$result .= "</tr>\n";					
				}
			}
		}
		$result .= '<tr>' . "\n";
		$result .= '  <td align="center" colspan="2"><input type="submit" value="' . $this->options['submit'] . '" class="btnmain">';
		if(!empty($this->options['reset'])) {
			$result .= '<input type="reset" value="' . $this->options['reset'] . '" class="btnlite">';
		}
		$result .= "</td>\n";
		$result .= "</tr>\n";
		$result .= "</table>\n";
		$result .= "</form>\n";
		if($return){
			return $result;
		} else {
			echo $result;
			return true;
		}
	}

	function text_box($name, $value, $size = 0, $maxlength = 0, $password = false, $extra = ''){
		return '<input type="' . (($password) ? 'password' : 'text') . '" class="text" name="' . $name . '"' . (($size > 0) ?  ' size="' . $size . '"' : '') . (($maxlength > 0) ?  ' maxlength="' . $maxlength . '"' : '') . ' value="' . htmlspecialchars($value) . '" ' . $extra . '>';
	}

	function textarea($name, $value, $cols = 30, $rows = 5, $extra = ''){
		return '<textarea id="' . $name . '" name="' . $name . '" cols="' . $cols . '" rows="' . $rows . '" ' . $extra . '>' . htmlspecialchars($value) . '</textarea>';
	}

	function select_tag($name, $values, $selected = '', $extra = ''){
		$data = '<select name="' . $name . '" ' . $extra . '>' . "\n";
		foreach($values as $value => $text){
			$data .= '<option value="' . $value . '" ' . (($selected == $value) ? 'selected' : '') . '>' . __($text) . '</option>' . "\n";
		}
		$data .= '</select> ' . "\n";
		return $data;
	}

	function radio_button($name, $values, $selected = '', $separator = ' ', $extra = ''){
		$data = '';
		foreach($values as $value => $text){
			$id = rcms_random_string(5);
			$data .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="' . $id . '" ' . (($selected == $value) ? 'checked' : '') . ' ' . $extra . '><label for="' . $id . '">' . $text . '</label>' . $separator;
		}
		return $data;
	}

	function radio_button_single($name, $value, $selected = '', $caption = ' ', $extra = ''){
		$id = rcms_random_string(5);
		return '<input type="radio" name="' . $name . '" value="' . $value . '" id="' . $id . '" ' . (($selected) ? 'checked' : '') . ' ' . $extra . '><label for="' . $id . '">' . $caption . '</label>';
	}

	function checkbox($name, $value, $caption, $checked = 0, $extra = ''){
		$id = rcms_random_string(5);
		return '<input type="checkbox" name="' . $name . '" value="' . $value . '" id="' . $id . '" ' . ((!empty($checked)) ? 'checked' : '') . ' ' . $extra . ' /><label for="' . $id . '">' . $caption . '</label>';
	}

	function file($name) {
		return '<input type="file" name="' . $name . '" value="" />';
	}
	function fieldset_start($name,$after='') {
		$this->_rows[] = array('fieldset_start' => '</table><br /><fieldset><legend>' . $name . '</legend>' . $after . '<table>');
		end($this->_rows);
		return key($this->_rows);
	}
	
	function fieldset_end() {
		$this->_rows[] = array('fieldset_end' => '</table></fieldset><table>');
		end($this->_rows);
		return key($this->_rows);
	}
}
?>