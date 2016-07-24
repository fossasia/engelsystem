<?php

function admin_user_title() {
  return _("All Angels");
}

function admin_user() {
  global $user, $privileges, $tshirt_sizes, $privileges;

  $html = '';

  if (! isset($_REQUEST['id']))
    redirect(users_link());

  $id = $_REQUEST['id'];
  if (! isset($_REQUEST['action'])) {
    $user_source = User($id);
    if ($user_source === false)
      engelsystem_error('Unable to load user.');
    if ($user_source == null) {
      error(_('This user does not exist.'));
      redirect(users_link());
    }

    $html .= "Hello,<br />" . "Here you can change the entries. Under the point 'Down'
the angel is present if it is marked 'Yes'  ." . " If a 'Yes' " . " is there under active than the angel is active " . "thus has a claim on the T-shirt.". " If T-shirt contains a 'Yes' ".  "than it means angel has already received his T-shirt." . "<br /><br />\n";

    $html .= "<form action=\"" . page_link_to("admin_user_controller") . "&action=save&id=$id\" method=\"post\">\n";
    $html .= "<table border=\"0\">\n";
    $html .= "<input type=\"hidden\" name=\"Type\" value=\"Normal\">\n";
    $html .= "<tr><td>\n";
    $html .= "<table>\n";
    $html .= "  <tr><td>Nick Name</td><td>" . "<input type=\"text\" size=\"40\" name=\"eNick\" value=\"" . $user_source['Nick'] . "\"></td></tr>\n";
    $html .= "  <tr><td>lastLogIn</td><td>" . date("Y-m-d H:i", $user_source['lastLogIn']) . "</td></tr>\n";
    $html .= "  <tr><td>Name</td><td>" . "<input type=\"text\" size=\"40\" name=\"eName\" value=\"" . $user_source['Name'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Last Name</td><td>" . "<input type=\"text\" size=\"40\" name=\"eVorname\" value=\"" . $user_source['Vorname'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Age</td><td>" . "<input type=\"text\" size=\"5\" name=\"eAlter\" value=\"" . $user_source['Alter'] . "\"></td></tr>\n";
    $html .= "  <tr><td>TelePhone</td><td>" . "<input type=\"text\" size=\"40\" name=\"eTelefon\" value=\"" . $user_source['Telefon'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Mobile</td><td>" . "<input type=\"text\" size=\"40\" name=\"eHandy\" value=\"" . $user_source['Handy'] . "\"></td></tr>\n";
    $html .= "  <tr><td>DECT</td><td>" . "<input type=\"text\" size=\"4\" name=\"eDECT\" value=\"" . $user_source['DECT'] . "\"></td></tr>\n";
    $html .= "  <tr><td>email</td><td>" . "<input type=\"text\" size=\"40\" name=\"eemail\" value=\"" . $user_source['email'] . "\"></td></tr>\n";
    $html .= "<tr><td>" . form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $user_source['email_shiftinfo']) . "</td></tr>\n";
    $html .= "  <tr><td>jabber</td><td>" . "<input type=\"text\" size=\"40\" name=\"ejabber\" value=\"" . $user_source['jabber'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Size</td><td>" . html_select_key('size', 'eSize', $tshirt_sizes, $user_source['Size']) . "</td></tr>\n";

    $options = array(
        '1' => "Yes",
        '0' => "No"
    );

    // Gekommen?
    $html .= "  <tr><td>Down</td><td>\n";
    $html .= html_options('eGekommen', $options, $user_source['Gekommen']) . "</td></tr>\n";

    // Aktiv?
    $html .= "  <tr><td>Active</td><td>\n";
    $html .= html_options('eAktiv', $options, $user_source['Aktiv']) . "</td></tr>\n";

    // Aktiv erzwingen
    if (in_array('admin_active', $privileges)) {
      $html .= "  <tr><td>" . _("Force active") . "</td><td>\n";
      $html .= html_options('force_active', $options, $user_source['force_active']) . "</td></tr>\n";
    }

    // T-Shirt bekommen?
    $html .= "  <tr><td>T-Shirt</td><td>\n";
    $html .= html_options('eTshirt', $options, $user_source['Tshirt']) . "</td></tr>\n";

    $html .= "  <tr><td>Hometown</td><td>" . "<input type=\"text\" size=\"40\" name=\"Hometown\" value=\"" . $user_source['Hometown'] . "\"></td></tr>\n";

    $html .= "</table>\n</td><td valign=\"top\"></td></tr>";

    $html .= "</td></tr>\n";
    $html .= "</table>\n<br />\n";
    $html .= "<input type=\"submit\" value=\"Submit\">\n";
    $html .= "</form>";

    $html .= "<hr />";

    $html .= form_info('', _('Please visit the angeltypes page or the users profile to manage users angeltypes.'));

    $html .= "Here you can reset the password of this angel.<form action=\"" . page_link_to("admin_user_controller") . "&action=change_pw&id=$id\" method=\"post\">\n";
    $html .= "<table>\n";
    $html .= "  <tr><td>Password</td><td>" . "<input type=\"password\" size=\"40\" name=\"new_pw\" value=\"\"></td></tr>\n";
    $html .= "  <tr><td>Repeat Password</td><td>" . "<input type=\"password\" size=\"40\" name=\"new_pw2\" value=\"\"></td></tr>\n";

    $html .= "</table>";
    $html .= "<input type=\"submit\" value=\"Submit\">\n";
    $html .= "</form>";

    $html .= "<hr />";

    $my_highest_group = UserGroups_by_id($user['UID']);
    if (count($my_highest_group) > 0)
      $my_highest_group = $my_highest_group[0]['group_id'];

    $his_highest_group = UserGroups_by_id($id);
    if (count($his_highest_group) > 0)
      $his_highest_group = $his_highest_group[0]['group_id'];

    if ($id != $user['UID'] && $my_highest_group <= $his_highest_group) {
      $html .= "Hier kannst Du die Benutzergruppen des Engels festlegen:<form action=\"" . page_link_to("admin_user_controller") . "&action=save_groups&id=" . $id . "\" method=\"post\">\n";
      $html .= '<table>';

      $groups = Groups_by_id_groups($id, $my_highest_group);
      foreach ($groups as $group)
        $html .= '<tr><td><input type="checkbox" name="groups[]" value="' . $group['UID'] . '"' . ($group['group_id'] != "" ? ' checked="checked"' : '') . ' /></td><td>' . $group['Name'] . '</td></tr>';

      $html .= '</table>';

      $html .= "<input type=\"submit\" value=\"Speichern\">\n";
      $html .= "</form>";

      $html .= "<hr />";
    }

    $html .= buttons([
        button(user_delete_link($user_source), glyph('lock') . _("delete"), 'btn-danger')
    ]);

    $html .= "<hr />";
  } else {
    switch ($_REQUEST['action']) {
      case 'save_groups':
        if ($id != $user['UID']) {
          $my_highest_group = UserGroups_by_id($user['UID']);
          $his_highest_group = UserGroups_by_id($id);

          if (count($my_highest_group) > 0 && (count($his_highest_group) == 0 || ($my_highest_group[0]['group_id'] <= $his_highest_group[0]['group_id']))) {
            $groups_source = Groups_by_id_groups($id, $my_highest_group[0]['group_id']);
            $groups = array();
            $grouplist = array();
            foreach ($groups_source as $group) {
              $groups[$group['UID']] = $group;
              $grouplist[] = $group['UID'];
            }

            if (! is_array($_REQUEST['groups']))
              $_REQUEST['groups'] = array();

            delete_UserGroups_id($id);
            $user_groups_info = array();
            foreach ($_REQUEST['groups'] as $group) {
              if (in_array($group, $grouplist)) {
                insert_UserGroups_id($id, $group);
                $user_groups_info[] = $groups[$group]['Name'];
              }
            }
            $user_source = User($id);
            engelsystem_log("Set groups of " . User_Nick_render($user_source) . " to: " . join(", ", $user_groups_info));
            $html .= success("User groups stored .", true);
          } else {
            $html .= error("You can not edit angel with more rights .", true);
          }
        } else {
          $html .= error("You can not edit your own rights .", true);
        }
        break;

      case 'save':
        $force_active = $user['force_active'];
        if (in_array('admin_active', $privileges))
          $force_active = $_REQUEST['force_active'];
        $SQL = update_user($_POST["eNick"], $_POST["eName"], $_POST["eVorname"], $_POST["eTelefon"], $_POST["eHandy"], $_POST["eAlter"], $_POST["eDECT"], $_POST["eemail"], $_REQUEST['email_shiftinfo'], $_POST["ejabber"], $_POST["eSize"], $_POST["eGekommen"], $_POST["eAktiv"], $force_active, $_POST["eTshirt"], $_POST["Hometown"], $id);
        sql_query($SQL);
        engelsystem_log("Updated user: " . $_POST["eNick"] . ", " . $_POST["eSize"] . ", arrived: " . $_POST["eGekommen"] . ", active: " . $_POST["eAktiv"] . ", tshirt: " . $_POST["eTshirt"]);
        $html .= success("Change has been saved...\n", true);
        break;

      case 'change_pw':
        if ($_REQUEST['new_pw'] != "" && $_REQUEST['new_pw'] == $_REQUEST['new_pw2']) {
          set_password($id, $_REQUEST['new_pw']);
          $user_source = User($id);
          engelsystem_log("Set new password for " . User_Nick_render($user_source));
          $html .= success("set new password.", true);
        } else {
          $html .= error("The entries must match and must not be empty !", true);
        }
        break;
    }
  }

  return page_with_title(_('Edit user'), array(
      $html
  ));
}
?>
