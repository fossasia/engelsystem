<?php
/**
 * List of available themes
 */
$themes = array(
    '3' => "Engelsystem 32c3",
    "2" => "Engelsystem cccamp15",
    "0" => "Engelsystem light",
    "1" => "Engelsystem dark",
    "4" => "Engelsystem color scheme-1",
    "5" => "Engelsystem color scheme-2"
);
/**
 * Display muted (grey) text.
 *
 * @param string $text
 */
function mute($text) {
  return '<span class="text-muted">' . $text . '</span>';
}
function progress_bar($valuemin, $valuemax, $valuenow, $class = '', $content = '') {
  return '<div class="progress"><div class="progress-bar ' . $class . '" role="progressbar" aria-valuenow="' . $valuenow . '" aria-valuemin="' . $valuemin . '" aria-valuemax="' . $valuemax . '" style="width: ' . (($valuenow - $valuemin) * 100 / ($valuemax - $valuemin)) . '%">' . $content . '</div></div>';
}
/**
 * Render glyphicon
 *
 * @param string $glyph_name
 */
function glyph($glyph_name) {
  return ' <span class="glyphicon glyphicon-' . $glyph_name . '"></span> ';
}
/**
 * Renders a tick or a cross by given boolean
 *
 * @param boolean $boolean
 */
function glyph_bool($boolean) {
  return '<span class="text-' . ($boolean ? 'success' : 'danger') . '">' . glyph($boolean ? 'ok' : 'remove') . '</span>';
}
function div($class, $content = array(), $id = "") {
  $id = $id != '' ? ' id="' . $id . '"' : '';
  return '<div' . $id . ' class="' . $class . '">' . join("\n", $content) . '</div>';
}
function heading($content, $number = 1) {
  return "<h" . $number . ">" . $content . "</h" . $number . ">";
}
/**
 * Render a toolbar.
 *
 * @param array $items
 * @return string
 */
function toolbar($items = array(), $right = false) {
  return '<ul class="nav navbar-nav' . ($right ? ' navbar-right' : '') . '">' . join("\n", $items) . '</ul>';
}
/**
 * Render a link for a toolbar.
 *
 * @param string $href
 * @param string $glyphicon
 * @param string $label
 * @param bool $selected
 * @return string
 */
function toolbar_item_link($href, $glyphicon, $label, $selected = false) {
  return '<li class="' . ($selected ? 'active' : '') . '"><a href="' . $href . '">' . ($glyphicon != '' ? '<span class="glyphicon glyphicon-' . $glyphicon . '"></span> ' : '') . $label . '</a></li>';
}
function toolbar_item_divider() {
  return '<li class="divider"></li>';
}
function toolbar_dropdown($glyphicon, $label, $submenu, $class = '') {
  return '<li class="dropdown ' . $class . '">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . ($glyphicon != '' ? '<span class="glyphicon glyphicon-' . $glyphicon . '"></span> ' : '') . $label . ' <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">' . join("\n", $submenu) . '</ul></li>';
}
function toolbar_popover($glyphicon, $label, $content, $class = '') {
  $id = md5(microtime() . $glyphicon . $label);
  return '<li class="dropdown messages ' . $class . '">
          <a id="' . $id . '" href="#" tabindex="0">' . ($glyphicon != '' ? '<span class="glyphicon glyphicon-' . $glyphicon . '"></span> ' : '')
          . $label . ' <span class="caret"></span></a>
          <script type="text/javascript">
          $(function(){
              $("#' . $id . '").popover({
                  trigger: "focus",
                  html: true,
                  content: "' . addslashes(join('', $content)) . '",
                  placement: "bottom",
                  container: "#navbar-collapse-1"
              })
          });
          </script></li>';
}
function form_hidden($name, $value) {
  return '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
}
/**
 * Renders a number pad with buttons to adjust
 */
function form_spinner($name, $label, $value) {
  return form_element($label, '
      <div class="input-group">
        <input id="spinner-' . $name . '" class="form-control" type="text" name="' . $name . '" value="' . $value . '" />
        <div class="input-group-btn">
          <button id="spinner-' . $name . '-down" class="btn btn-default" type="button">
            <span class="glyphicon glyphicon-minus"></span>
          </button>
          <button id="spinner-' . $name . '-up" class="btn btn-default" type="button">
            <span class="glyphicon glyphicon-plus"></span>
          </button>
        </div>
      </div>
      <script type="text/javascript">
        $("#spinner-' . $name . '-down").click(function(e) {
          $("#spinner-' . $name . '").val(parseInt($("#spinner-' . $name . '").val()) - 1);
        });
        $("#spinner-' . $name . '-up").click(function(e) {
          $("#spinner-' . $name . '").val(parseInt($("#spinner-' . $name . '").val()) + 1);
        });
      </script>
      ');
}
/**
 * Render a bootstrap datepicker
 *
 * @param string $name
 *          Name of the parameter
 * @param string $label
 *          Label
 * @param int $value
 *          Unix Timestamp
 * @param int $min_date
 *          Earliest possible date
 * @return HTML
 */
function form_date($name, $label, $value, $start_date = '') {
  $id = $name . '-date';
  $value = is_numeric($value) ? date('Y-m-d', $value) : '';
  $start_date = is_numeric($start_date) ? date('Y-m-d', $start_date) : '';
  return form_element($label, '
    <div class="input-group date" id="' . $id . '">
      <input type="text" name="' . $name . '" class="form-control" value="' . $value . '"><span class="input-group-addon">' . glyph('th') . '</span>
    </div>
    <script type="text/javascript">
			$(function(){
        $("#' . $id . '").datepicker({
        	language: "' . locale_short() . '",
          todayBtn: "linked",
          format: "yyyy-mm-dd",
          startDate: "' . $start_date . '"
			  });
      });
    </script>
    ', $id);
}
/**
 * Renders a list of check boxes on a form
 *
 * @param
 *          name The name of the check boxes are formed from name_key
 * @param
 *          label The label of the list
 * @param
 *          items array of the individual checkboxes
 * @param
 *          selected array containing the keys that are selected
 */
function form_checkboxes($name, $label, $items, $selected) {
  $html = form_element($label, '');
  foreach ($items as $key => $item)
    $html .= form_checkbox($name . '_' . $key, $item, array_search($key, $selected) !== false);
  return $html;
  $html = "<ul>";
  foreach ($items as $key => $item) {
    $id = $name . '_' . $key;
    $sel = array_search($key, $selected) !== false ? ' checked="checked"' : "";
    $html .= '<li><input type="checkbox" id="' . $id . '" name="' . $id . '" value="checked"' . $sel . ' /><label for="' . $id . '">' . $item . '</label></li>';
  }
  $html .= "</ul>";
  return form_element($label, $html);
}
/**
 * Renders a table of check boxes on a form
 *
 * @param
 *         names associative array with the name of the checkboxes as Keys and headings as Values
 * @param
 *          label The label of the entire table
 * @param
 *          items Array with the labels of rows
 * @param
 *          selected Multidimensional array, $selected[ foo ] is an array of foo marked in the data series checkboxes
 * @param
 *         disabled How selected , only that the corresponding checkboxes disabled are instead marked
 */
function form_multi_checkboxes($names, $label, $items, $selected, $disabled = array()) {
  $html = "<table><thead><tr>";
  foreach ($names as $title)
    $html .= "<th>$title</th>";
  $html .= "</tr></thead><tbody>";
  foreach ($items as $key => $item) {
    $html .= "<tr>";
    foreach ($names as $name => $title) {
      $id = $name . '_' . $key;
      $sel = array_search($key, $selected[$name]) !== false ? ' checked="checked"' : "";
      if (! empty($disabled) && ! empty($disabled[$name]) && array_search($key, $disabled[$name]) !== false)
        $sel .= ' disabled="disabled"';
      $html .= '<td style="text-align: center;"><input type="checkbox" id="' . $id . '" name="' . $name . '[]" value="' . $key . '"' . $sel . ' /></td>';
    }
    $html .= '<td><label for="' . $id . '">' . $item . '</label></td></tr>';
  }
  $html .= "</tbody></table>";
  return form_element($label, $html);
}
/**
 * Renders a checkbox
 */
 /*
 * Updated : Added an option to exclude using a div block
 */
function form_checkbox($name, $label, $selected, $value = 'checked', $div = true) {
  if ($div === true) {
  	return '<div class="checkbox"><label><input type="checkbox" id="' . $name . '" name="' . $name . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' /> ' . $label . '</label></div>';
  }
  else {
   	return '<label><input type="checkbox" id="' . $name . '" name="' . $name . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' /> ' . $label . '</label>';
  }
}
/**
 * Renders a radio
 */
function form_radio($name, $label, $selected, $value) {
  return '<div class="radio"><label><input type="radio" id="' . $name . '" name="' . $name . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' /> ' . $label . '</label></div>';
}
/**
 * Renders a text information in the form
 */
function form_info($label, $text = "") {
  if ($label == "")
    return '<span class="help-block">' . glyph('info-sign') . $text . '</span>';
  if ($text == "")
    return '<h4>' . $label . '</h4>';
  return form_element($label, '<p class="form-control-static">' . $text . '</p>', '');
}
/**
 * Renders the submit button of a form
 */
function form_submit($name, $label) {
  return form_element('<input class="btn btn-primary" type="submit" name="' . $name . '" value="' . $label . '" />', "");
}
/**
 * Renders a form text box
 */
function form_text($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="text" name="' . $name . '" value="' . htmlspecialchars($value) . '" ' . $disabled . '/>', 'form_' . $name);
}
/**
 * Renders a form email field
 */
function form_email($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="email" name="' . $name . '" value="' . htmlspecialchars($value) . '" ' . $disabled . '/>', 'form_' . $name);
}
/**
 * Renders a form file box
 */
function form_file($name, $label) {
  return form_element($label, '<input id="form_' . $name . '" type="file" name="' . $name . '" />', 'form_' . $name);
}
/**
 * Renders a form - password field
 */
function form_password($name, $label, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="password" name="' . $name . '" value="" ' . $disabled . '/>', 'form_' . $name);
}
/**
 * Renders a form text box
 */
function form_textarea($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<textarea rows="5" class="form-control" id="form_' . $name . '" type="text" name="' . $name . '" ' . $disabled . '>' . $value . '</textarea>', 'form_' . $name);
}
/**
 * Renders a form selection box
 */
function form_select($name, $label, $values, $selected='') {
  return form_element($label, html_select_key('form_' . $name, $name, $values, $selected), 'form_' . $name);
}
/**
 * Renders a form element
 */
function form_element($label, $input, $for = "") {
  if ($label == '') {
    return '<div class="form-group">' . $input . '</div>';
  } else {
    return '<div class="form-group">' . '<label for="' . $for . '">' . $label . '</label>' . $input . '</div>';
  }
}
/**
 * Renders a form
 */
function form($elements, $action = "") {
  return '<form role="form" action="' . $action . '" enctype="multipart/form-data" method="post">' . join($elements) . '</form>';
}
/**
 * Generates HTML code for a "page".
 * Adds to the passed elements together.
 */
function page($elements) {
  return join($elements);
}
/**
 * Generates HTML code for a " page " with central heading
 * Adds to the passed elements together.
 */
function page_with_title($title, $elements) {
  return '<div class="col-md-12"><h1>' . $title . '</h1>' . join($elements) . '</div>';
}
/**
 * Renders a data table
 */
function table($columns, $rows_raw, $data = true) {
  // If only one column is given
  if (! is_array($columns)) {
    $columns = array(
        'col' => $columns
    );
    $rows = array();
    foreach ($rows_raw as $row)
      $rows[] = array(
          'col' => $row
      );
  } else
    $rows = $rows_raw;
  if (count($rows) == 0)
    return info(_("No data found."), true);
  $html = "";
  $html .= '<table class="table table-striped' . ($data ? ' data' : '') . '">';
  $html .= '<thead><tr>';
  foreach ($columns as $key => $column)
    $html .= '<th class="column_' . $key . '">' . $column . '</th>';
  $html .= '</tr></thead>';
  $html .= '<tbody>';
  foreach ($rows as $row) {
    $html .= '<tr>';
    foreach ($columns as $key => $column)
      if (isset($row[$key]))
        $html .= '<td class="column_' . $key . '">' . $row[$key] . '</td>';
      else
        $html .= '<td class="column_' . $key . '">&nbsp;</td>';
    $html .= '</tr>';
  }
  $html .= '</tbody>';
  $html .= '</table>';
  return $html;
}
/**
 * Renders a button
 */
function button($href, $label, $class = "") {
  return '<a href="' . $href . '" class="btn btn-default ' . $class . '">' . $label . '</a>';
}
/**
 * Renders a button with Glyph
 */
function button_glyph($href, $glyph, $class = "") {
  return button($href, glyph($glyph), $class);
}
/**
 * Renders a toolbar with buttons
 */
function buttons($buttons = array ()) {
  return '<div class="form-group">' . table_buttons($buttons) . '</div>';
}
function table_buttons($buttons = array()) {
  return '<div class="btn-group">' . join(' ', $buttons) . '</div>';
}
// Load and render template
function template_render($file, $data) {
  if (file_exists($file)) {
    $template = file_get_contents($file);
    if (is_array($data))
      foreach ($data as $name => $content) {
        $template = str_replace("%" . $name . "%", $content, $template);
      }
    return $template;
  } else {
    die('Cannot find template file &laquo;' . $file . '&raquo;.');
  }
}
function shorten($str) {
  if (strlen($str) < 50)
    return $str;
  return '<span title="' . htmlentities($str, ENT_COMPAT, 'UTF-8') . '">' . substr($str, 0, 47) . '...</span>';
}
function table_body($array) {
  $html = "";
  foreach ($array as $line) {
    $html .= "<tr>";
    if (is_array($line)) {
      foreach ($line as $td)
        $html .= "<td>" . $td . "</td>";
    } else {
      $html .= "<td>" . $line . "</td>";
    }
    $html .= "</tr>";
  }
  return $html;
}
function html_options($name, $options, $selected = "") {
  $html = "";
  foreach ($options as $value => $label)
    $html .= '<input type="radio"' . ($value == $selected ? ' checked="checked"' : '') . ' name="' . $name . '" value="' . $value . '"> ' . $label;
  return $html;
}
function html_select_key($id, $name, $rows, $selected) {
  $html = '<select class="form-control" id="' . $id . '" name="' . $name . '">';
  foreach ($rows as $key => $row) {
    if (($key == $selected) || ($row == $selected)) {
      $html .= '<option value="' . $key . '" selected="selected">' . $row . '</option>';
    } else {
      $html .= '<option value="' . $key . '">' . $row . '</option>';
    }
  }
  $html .= '</select>';
  return $html;
}
function ReplaceSmilies($neueckig) {
  $neueckig = str_replace(";o))", "<img src=\"pic/smiles/icon_redface.gif\">", $neueckig);
  $neueckig = str_replace(":-))", "<img src=\"pic/smiles/icon_redface.gif\">", $neueckig);
  $neueckig = str_replace(";o)", "<img src=\"pic/smiles/icon_wind.gif\">", $neueckig);
  $neueckig = str_replace(":)", "<img src=\"pic/smiles/icon_smile.gif\">", $neueckig);
  $neueckig = str_replace(":-)", "<img src=\"pic/smiles/icon_smile.gif\">", $neueckig);
  $neueckig = str_replace(":(", "<img src=\"pic/smiles/icon_sad.gif\">", $neueckig);
  $neueckig = str_replace(":-(", "<img src=\"pic/smiles/icon_sad.gif\">", $neueckig);
  $neueckig = str_replace(":o(", "<img src=\"pic/smiles/icon_sad.gif\">", $neueckig);
  $neueckig = str_replace(":o)", "<img src=\"pic/smiles/icon_lol.gif\">", $neueckig);
  $neueckig = str_replace(";o(", "<img src=\"pic/smiles/icon_cry.gif\">", $neueckig);
  $neueckig = str_replace(";(", "<img src=\"pic/smiles/icon_cry.gif\">", $neueckig);
  $neueckig = str_replace(";-(", "<img src=\"pic/smiles/icon_cry.gif\">", $neueckig);
  $neueckig = str_replace("8)", "<img src=\"pic/smiles/icon_rolleyes.gif\">", $neueckig);
  $neueckig = str_replace("8o)", "<img src=\"pic/smiles/icon_rolleyes.gif\">", $neueckig);
  $neueckig = str_replace(":P", "<img src=\"pic/smiles/icon_evil.gif\">", $neueckig);
  $neueckig = str_replace(":-P", "<img src=\"pic/smiles/icon_evil.gif\">", $neueckig);
  $neueckig = str_replace(":oP", "<img src=\"pic/smiles/icon_evil.gif\">", $neueckig);
  $neueckig = str_replace(";P", "<img src=\"pic/smiles/icon_mad.gif\">", $neueckig);
  $neueckig = str_replace(";oP", "<img src=\"pic/smiles/icon_mad.gif\">", $neueckig);
  $neueckig = str_replace("?)", "<img src=\"pic/smiles/icon_question.gif\">", $neueckig);
  return $neueckig;
}
/**
 * Renders Google reCaptcha
 * @param $flg Flag to enable/diable capthca
 *         set in config.php
 */
function reCaptcha($flg) {
  if($flg)
    return '<div class="g-recaptcha" data-sitekey="' . CAPTCHA_KEY_PUBLIC . '"></div>';
}
/**
 * Function to add Date-picker
 * @param $name  name of the Date picker
 */
function calender($name, $value = '', $id) {
  return '<div class="input-group date" id="' . $id .'">
      		<input name="' . $name . '" class="form-control" value="' . $value .'" type="text">
      		<span class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </span>
    	  </div>
		  <script type="text/javascript">
			$(function(){
			  $("#' . $id .'").datepicker({
				language: "de",
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				startDate: ""
			  });
			});
		  </script>';
}
/**
 * Renders a form multi-select box
 * @param $name names associative array with the name of the checkboxes as Keys and headings as Values
 * @param $label label The label of the entire table
 * @param $values items Array with the labels of rows
 */
function form_multiselect($name, $label, $values, $tag = "") {
  $list = '<div class="form-group"><label>' . $label . '</label>';
  $list .= '<select class="form-control selectpicker" multiple title="' . $tag . '" name="' . $name .'[]"> ';
  foreach($values as $key => $value) {
    $list .= '<option value="' . $key . '">' . $value . '</option>';
  }
  $list .= '</select></div>';
  return $list;
}
?>
