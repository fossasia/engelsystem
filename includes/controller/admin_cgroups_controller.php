<?php

function admin_cgroups_title() {
  return _("Create Groups");
}

function admin_create_groups() {

  global $user;

  $name = "";
  $uid = "";
  // Load Privileges
  $selected_privileges = array();
  $privilege_source = sql_select("SELECT * FROM `Privileges`");
  $selected_privilege_source = sql_select("SELECT * FROM `GroupPrivileges` WHERE `group_id` = -2");
  $privilege_types = array();
  foreach ($privilege_source as $privilege_type) {
    $privilege_types[$privilege_type['id']] = $privilege_type['desc'] . ' (' . $privilege_type['name'] .') ';
  }
  // select default Privileges
  foreach ($selected_privilege_source as $selected_privilege) {
    $selected_privileges[] = $selected_privilege['privilege_id'];
  }

  if (isset($_REQUEST['submit'])) {
    $ok = true;

    if (isset($_REQUEST['Name']) && strlen(strip_request_item('Name')) > 0){
      $name = strip_request_item('Name');
    }
    else {
      $ok = false;
      $msg .= error(_("Please enter your Group Name."), true);
    }

   if (isset($_REQUEST['UID']))
      $uid = strip_request_item('UID');
    else {
      $ok = false;
      $msg .= error(_("Please enter your Group UID."), true);
    }

    // set privileges
    foreach ($privilege_source as $selected_privileges_id)
       if (isset($_REQUEST['privilege_types_' . $selected_privilege_id['id'] ]))
        $selected_privileges[] = $selected_privileges_id['id'];

  }

  if ($ok) {
    create_new_group($name, $uid);
    foreach ($selected_privileges as $priv)
      insert_into_group_privileges($uid, $priv);
    success(_("New Group Created."));
    redirect(page_link_to('admin_cgroups'));
  }

  return page_with_title(admin_cgroups_title(), array(
      msg(),
      form(array(
          form_text('Name', _("Group Name"), $name),
          form_text('UID', _("User ID"), $uid),
          form_checkboxes('privilege_types', _("What Privileges do you want to provide?") , $privilege_types, $selected_privileges),
          form_submit('submit', _("Save"))
      ))
  ));

}

?>
