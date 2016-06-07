<?php
$locales = array(
    'de_DE.UTF-8' => "Deutsch",
    'hi_IN.UTF-8' => "Hindi", 
    'sp_EU.UTF-8' => "Spanish",
    'en_US.UTF-8' => "English",
    'bn_IN.UTF-8' => "Bengali"
); 

$default_locale = 'en_US.UTF-8';

/**
 * Return currently active locale
 */
function locale() {
  return $_SESSION['locale'];
}

/**
 * Returns two letter language code from currently active locale
 */
function locale_short() {
  return substr(locale(), 0, 2);
}

/**
 * Initializes gettext for internationalization and updates the sessions locale to use for translation.
 */
function gettext_init() {
  global $locales, $default_locale;
  
  if (isset($_REQUEST['set_locale']) && in_array($_REQUEST['set_locale'], array_keys($locales)))
    $_SESSION['locale'] = $_REQUEST['set_locale'];
  elseif (! isset($_SESSION['locale']))
    $_SESSION['locale'] = $default_locale;
  
  gettext_locale();
  bindtextdomain('default', '../locale');
  bind_textdomain_codeset('default', 'UTF-8');
  textdomain('default');
}

/**
 * Swich gettext locale.
 *
 * @param string $locale          
 */
function gettext_locale($locale = null) {
  if ($locale == null)
    $locale = $_SESSION['locale'];
  
  putenv('LC_ALL=' . $locale);
  setlocale(LC_ALL, $locale);
}

/**
 * Renders language selection.
 *
 * @return string
 */
function make_langselect() {
  global $locales;
  $URL = $_SERVER["REQUEST_URI"] . (strpos($_SERVER["REQUEST_URI"], "?") > 0 ? '&' : '?') . "set_locale=";
  
  $items = array();
  foreach ($locales as $locale => $name)
    $items[] = toolbar_item_link(htmlspecialchars($URL) . $locale, '', '<img src="pic/flag/' . $locale . '.png" alt="' . $name . '" title="' . $name . '"> ' . $name);
  return $items;
}

?>
