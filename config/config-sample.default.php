<?php

// Enable maintenance mode (showin a static page)
$maintenance_mode = false;

// URL to the angel faq and job description
$faq_url = "https://events.ccc.de/congress/2013/wiki/Static:Volunteers";

// contact email address, linked on every page
$contact_email = "mailto:erzengel@lists.ccc.de";

// Default Theme on the home page 1 = style1.css etc.
$default_theme = 0;

// Number of news that can be spend on one side ...
$DISPLAY_NEWS = 6;

// Number hours to discharge its own layers
$LETZTES_AUSTRAGEN = 3;

// Sets to use crypt-Algorithm ( according to the documentation of crypt ( ) ) .
// If a user password is stored in a different format ,
// is it in the first use of the plaintext password in the new format
// converted.
// define('CRYPT_ALG', '$1'); // MD5
// define('CRYPT_ALG', '$2y$13'); // Blowfish
// define('CRYPT_ALG', '$5$rounds=5000'); // SHA-256
define('CRYPT_ALG', '$6$rounds=5000'); // SHA-512

define('MIN_PASSWORD_LENGTH', 8);

// When angels should specify a T - shirt size when registering or in their profile , set to true :
$enable_tshirt_size = true;

// Number of shifts to freeload until angel is locked for shift signup.
$max_freeloadable_shifts = 2;

// local timezone
date_default_timezone_set("Europe/Berlin");

// multiply "night shifts" and freeloaded shifts (start or end between 2 and 6 exclusive) by 2
$shift_sum_formula = "SUM(
  (1+(
    (HOUR(FROM_UNIXTIME(`Shifts`.`end`)) > 2 AND HOUR(FROM_UNIXTIME(`Shifts`.`end`)) < 6)
    OR (HOUR(FROM_UNIXTIME(`Shifts`.`start`)) > 2 AND HOUR(FROM_UNIXTIME(`Shifts`.`start`)) < 6)
    OR (HOUR(FROM_UNIXTIME(`Shifts`.`start`)) <= 2 AND HOUR(FROM_UNIXTIME(`Shifts`.`end`)) >= 6)
  ))*(`Shifts`.`end` - `Shifts`.`start`)*(1 - 3 * `ShiftEntry`.`freeloaded`)
)";

// voucher calculation
$voucher_settings = array(
  "initial_vouchers" => 2,
  "shifts_per_voucher" => 1
);

// weigh every shift the same
// $shift_sum_formula = "SUM(`end` - `start`)";

// For accessing stats
$api_key = "";

// MySQL-Connection Settings
$DB_HOST = "localhost";
$DB_USER = "username_here";
$DB_PASSWORD = "password_here";
$DB_NAME = "database_name_here";

/** reCaptcha Settings
 * Visit http://www.google.com/recaptcha/admin#whyrecaptcha for generating reCaptcha keys for your website.
*/
define('capflg', '');  // Set reCaptch enalble or disable. true = enable , false = disable.
define('CAPTCHA_KEY_PUBLIC', '');  // Public/Data-site key
define('CAPTCHA_KEY_PRIVATE', '');  // Private/Secret Key
?>
