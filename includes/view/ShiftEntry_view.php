<?php

/**
 * Display form for adding/editing a shift entry.
 * @param string $angel
 * @param string $date
 * @param string $location
 * @param string $title
 * @param string $type
 * @param string $comment
 * 
 * @return string
 */
function ShiftEntry_edit_view($angel, $date, $location, $title, $type, $comment, $freeloaded, $freeload_comment, $user_admin_shifts = false) {
  if ($user_admin_shifts) {
    $freeload_form = array(
        form_checkbox('freeloaded', _("Freeloaded"), $freeloaded),
        form_textarea('freeload_comment', _("Freeload comment (Only for shift coordination):"), $freeload_comment) 
    );
  } else {
    $freeload_form = array();
  }
  /**
   * Google reCaptcha Server-Side Handling
   */
  if (isset($_REQUEST['g-recaptcha-response']) && !empty($_REQUEST['g-recaptcha-response'])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => 'hppts://www.google.com/recaptcha/api/siteverify',
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => [
        'secret' => '6LeGiyITAAAAAMd--Qw4C3iBPrEM-qZDhQQ4LWMt',
        'response' => $_REQUEST['g-recaptcha-response'],
      ]
    ]);
    $response = json_decode(curl_exec($curl));
  }
  else {
    $ok = false;
    $msg .= error(_("You are a Robot."), true);
  }
  return page_with_title(_("Edit shift entry"), array(
      msg(),
      form(array(
          form_info(_("Angel:"), $angel),
          form_info(_("Date, Duration:"), $date),
          form_info(_("Location:"), $location),
          form_info(_("Title:"), $title),
          form_info(_("Type:"), $type),
          form_textarea('comment', _("Comment (for your eyes only):"), $comment),
          join("", $freeload_form),
          div('row', array(
                      div('col-sm-8', array(
                          reCaptcha()
                      ))
          )),
          form_submit('submit', _("Save")) 
      )) 
  ));
}
?>
