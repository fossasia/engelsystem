<?php

/**
 * Liste der verfügbaren Themes
 */
$themes = array(
    '3' => "Engelsystem 32c3",
    "2" => "Engelsystem cccamp15",
    "0" => "Engelsystem light",
    "1" => "Engelsystem dark",
    "4" => "Engelsystem color scheme-1"
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
 * Rendert ein Zahlenfeld mit Buttons zum verstellen
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
 * Rendert eine Liste von Checkboxen für ein Formular
 *
 * @param
 *          name Die Namen der Checkboxen werden aus name_key gebildet
 * @param
 *          label Die Beschriftung der Liste
 * @param
 *          items Array mit den einzelnen Checkboxen
 * @param
 *          selected Array mit den Keys, die ausgewählt sind
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
 * Rendert eine Tabelle von Checkboxen für ein Formular
 *
 * @param
 *          names Assoziatives Array mit Namen der Checkboxen als Keys und Überschriften als Values
 * @param
 *          label Die Beschriftung der gesamten Tabelle
 * @param
 *          items Array mit den Beschriftungen der Zeilen
 * @param
 *          selected Mehrdimensionales Array, wobei $selected[foo] ein Array der in der Datenreihe foo markierten Checkboxen ist
 * @param
 *          disabled Wie selected, nur dass die entsprechenden Checkboxen deaktiviert statt markiert sind
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
 * Rendert eine Checkbox
 */
function form_checkbox($name, $label, $selected, $value = 'checked') {
  return '<div class="checkbox"><label><input type="checkbox" id="' . $name . '" name="' . $name . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' /> ' . $label . '</label></div>';
}

/**
 * Rendert einen Radio
 */
function form_radio($name, $label, $selected, $value) {
  return '<div class="radio"><label><input type="radio" id="' . $name . '" name="' . $name . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' /> ' . $label . '</label></div>';
}

/**
 * Rendert einen Infotext in das Formular
 */
function form_info($label, $text = "") {
  if ($label == "")
    return '<span class="help-block">' . glyph('info-sign') . $text . '</span>';
  if ($text == "")
    return '<h4>' . $label . '</h4>';
  return form_element($label, '<p class="form-control-static">' . $text . '</p>', '');
}

/**
 * Rendert den Absenden-Button eines Formulars
 */
function form_submit($name, $label) {
  return form_element('<input class="btn btn-primary" type="submit" name="' . $name . '" value="' . $label . '" />', "");
}

/**
 * Rendert ein Formular-Textfeld
 */
function form_text($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="text" name="' . $name . '" value="' . htmlspecialchars($value) . '" ' . $disabled . '/>', 'form_' . $name);
}

/**
 * Rendert ein Formular-Emailfeld
 */
function form_email($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="email" name="' . $name . '" value="' . htmlspecialchars($value) . '" ' . $disabled . '/>', 'form_' . $name);
}

/**
 * Rendert ein Formular-Dateifeld
 */
function form_file($name, $label) {
  return form_element($label, '<input id="form_' . $name . '" type="file" name="' . $name . '" />', 'form_' . $name);
}

/**
 * Rendert ein Formular-Passwortfeld
 */
function form_password($name, $label, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<input class="form-control" id="form_' . $name . '" type="password" name="' . $name . '" value="" ' . $disabled . '/>', 'form_' . $name);
}

/**
 * Rendert ein Formular-Textfeld
 */
function form_textarea($name, $label, $value, $disabled = false) {
  $disabled = $disabled ? ' disabled="disabled"' : '';
  return form_element($label, '<textarea rows="5" class="form-control" id="form_' . $name . '" type="text" name="' . $name . '" ' . $disabled . '>' . $value . '</textarea>', 'form_' . $name);
}

/**
 * Rendert ein Formular-Auswahlfeld
 */
function form_select($name, $label, $values, $selected) {
  return form_element($label, html_select_key('form_' . $name, $name, $values, $selected), 'form_' . $name);
}

/**
 * Rendert ein Formular-Element
 */
function form_element($label, $input, $for = "") {
  if ($label == '') {
    return '<div class="form-group">' . $input . '</div>';
  } else {
    return '<div class="form-group">' . '<label for="' . $for . '">' . $label . '</label>' . $input . '</div>';
  }
}

/**
 * Rendert ein Formular
 */
function form($elements, $action = "") {
  return '<form role="form" action="' . $action . '" enctype="multipart/form-data" method="post">' . join($elements) . '</form>';
}

/**
 * Generiert HTML Code für eine "Seite".
 * Fügt dazu die übergebenen Elemente zusammen.
 */
function page($elements) {
  return join($elements);
}

/**
 * Generiert HTML Code für eine "Seite" mit zentraler Überschrift
 * Fügt dazu die übergebenen Elemente zusammen.
 */
function page_with_title($title, $elements) {
  return '<div class="col-md-12"><h1>' . $title . '</h1>' . join($elements) . '</div>';
}

/**
 * Rendert eine Datentabelle
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
 * Rendert einen Knopf
 */
function button($href, $label, $class = "") {
  return '<a href="' . $href . '" class="btn btn-default ' . $class . '">' . $label . '</a>';
}

/**
 * Rendert einen Knopf mit Glyph
 */
function button_glyph($href, $glyph, $class = "") {
  return button($href, glyph($glyph), $class);
}

/**
 * Rendert eine Toolbar mit Knöpfen
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
?>
