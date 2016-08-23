<?php
function admin_export_title() {
  return _("Import and Export User data ");
}

function admin_export() {
  // Export User data
  if(isset($_REQUEST['download'])){
    $filename = tempnam('/tmp', '.csv'); //  Temporary File Name
    create_temporary_table(); // Create temporary table
	  alter_table("Passwort"); // Drop column with passwords
    alter_table("password_recovery_token");
	  $headings = select_column();
	  $head = "";
    // Return heading of the columns on Table `Users`
	  foreach($headings as $heading) {
	    if ((strcmp($heading["COLUMN_NAME"],'Passwort') && strcmp($heading["COLUMN_NAME"],'password_recovery_token')) !=0 )
	      $head .= $heading["COLUMN_NAME"] . " ";
	  }
  	$final = explode(" ", $head);
  	$results = select_temp_tb();
	  $filep = fopen("$filename", "w+");
	  fputcsv($filep, $final, "\t");
	  foreach($results as $result) {
		  fputcsv($filep, $result, "\t");
	  }
	  $filep = @fopen($filename, 'rb+');
    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
      header('Content-Type: application/csv');
      header('Content-Disposition: attachment; filename=export_users_data.csv');
      header('Expires: 0');
		  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header("Content-Transfer-Encoding: binary");
      header('Pragma: public');
		  header("Content-Length: ".filesize($filename));
	  }
	  else {
      header('Content-Type: application/csv');
		  header('Content-Disposition: attachment; filename=export_users_data.csv');
		  header("Content-Transfer-Encoding: binary");
		  header('Expires: 0');
		  header('Pragma: no-cache');
		  header("Content-Length: ".filesize($filename));
	  }
	  fpassthru($filep);
	  fclose($filep);
 }

 // Import User data
  if (isset($_REQUEST['upload'])) {
    $ok = true;
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");
    if ($file == NULL) {
      error(_('Please select a file to import'));
      redirect(page_link_to('admin_export'));
    }
    else{
      while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
        {
          $nick = $filesop[0];
          $prename = $filesop[1];
          $lastname = $filesop[2];
          $mail = $filesop[3];
          $current_city = $filesop[4];
          $password = $filesop[5];
          $planned_arrival_date = $filesop[6];
          $timezone = $filesop[7];
          $mobile = $filesop[8];
          $tel = $filesop[9];
          $age = $filesop[10];
          $hometown = $filesop[11];
          $organization = $filesop[12];
          $organization_web = $filesop[13];
          $facebook = $filesop[14];
          $github = $filesop[15];
          $twitter = $filesop[16];
          $jabber = $filesop[17];
          $dect = $filesop[18];
          $tshirt_size = $filesop[19];
          $email_shiftinfo = false;
          $selected_angel_types = array();
          $password_hash = "";

        if (strlen(User_validate_Nick($nick)) > 1) {
          $nick = User_validate_Nick($nick);
          if (User_select_nick($nick) > 0) {
            $ok = false;
            $msg .= error(sprintf(_("Your nick &quot;%s&quot; already exists."), $nick), true);
          }
        } else {
          $ok = false;
          $msg .= error(sprintf(_("Your nick &quot;%s&quot; is too short (min. 2 characters)."),User_validate_Nick($_REQUEST['nick'])), true);
        }

        if ( strlen($mail) && preg_match("/^[a-z0-9._+-]{1,64}@(?:[a-z0-9-]{1,63}\.){1,125}[a-z]{2,63}$/", $mail) > 0) {
          if (! check_email($mail)) {
            $ok = false;
            $msg .= error(_("E-mail address is not correct."), true);
          }
        }

        if (User_select_mail($mail) > 0) {
          $ok = false;
          $msg .= error(sprintf(_("Your E-mail &quot;%s&quot; already exists.<a href=%s>Forgot password?</a>"), $mail,page_link_to_absolute('user_password_recovery')), true);
        } else {
          $ok = false;
          $msg .= error(_("Please enter your correct e-mail (in lowercase)."), true);
        }

        if (strlen($password) >= MIN_PASSWORD_LENGTH) {
            $ok = true;
          } else {
            $ok = false;
            $msg .= error(sprintf(_("Your password is too short (please use at least %s characters)."), MIN_PASSWORD_LENGTH), true);
        }
        if ($ok) {
          $sql = User_insert($nick, $prename, $lastname, $age, $tel, $dect, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $password_hash, $comment, $hometown, $twitter, $facebook, $github, $organization, $organization_web, $timezone, $planned_arrival_date);
          $user_id = sql_id();
          Set_user_group($user_id);
          set_password($user_id, $_REQUEST['password']);
          engelsystem_log("User " . User_Nick_render(User($user_id)) . " signed up as: " . join(", ", $user_angel_types_info));
        }
      }

      if ($sql) {
        success(_("You database has imported successfully!"));
        redirect(page_link_to('admin_export'));
      } else {
        error(_('Sorry! There is some problem in the import file.'));
        redirect(page_link_to('admin_export'));
        }
    }
  }

 return page_with_title(admin_export_title(), array(
   msg(),
   div('well well-sm text-center', [
     _('Export User Database')
   ]).div('row', array(
          div('col-md-12', array(
              form(array(
                form_info('', _("This will export user data.Press export button to download the user data.")),
                form_submit('download', _("Export"))
              ))
          ))
      )).div('well well-sm text-center', [
            _('Import User Database')
        ]).div('row', array(
          div('col-md-12', array(
              form(array(
                form_info('', _("This will import user data.Press Import button to upload the user data.")),
                form_file('csv_file', _("Import user data from a csv file")),
                form_submit('upload', _("Import"))
              ))
          ))
      ))
  ));
}
?>
