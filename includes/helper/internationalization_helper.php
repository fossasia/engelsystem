<?php
$locales = array(
    'de_DE.UTF-8' => "Deutsch",
    'hi_IN.UTF-8' => "Hindi-IN",
    'sp_EU.UTF-8' => "Spanish",
    'en_US.UTF-8' => "English-US",
    'bg_BG.UTF-8' => "Bulgarian",
    'en_GB.UTF-8' => "English-UK",
    'pa_IN.UTF-8' => "Punjabi-IN",
    'fr_FR.UTF-8' => "French",
    'ta_IN.UTF-8' => "Tamil-IN",
    'zn_CH.UTF-8' => "Chinese",
    'hu_HU.UTF-8' => "Hungarian",
    'fi_FI.UTF-8' => "Finnish",
    'ne_NP.UTF-8' => "Nepali"
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
