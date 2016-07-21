<?php
function admin_groups_title() {
  return _("Grouprights");
}

function admin_groups() {
  global $user;

  $html = "";
  $groups = Groups_by_name();
  if (! isset($_REQUEST["action"])) {
    $groups_table = array();
    foreach ($groups as $group) {
      $privileges = select_GroupPrivileges($group);
      $privileges_html = array();

      foreach ($privileges as $priv)
        $privileges_html[] = $priv['name'];

      $groups_table[] = array(
          'name' => $group['Name'],
          'privileges' => join(', ', $privileges_html),
          'actions' => button(page_link_to('admin_groups_controller') . '&action=edit&id=' . $group['UID'], _("edit"), 'btn-xs')
      );
    }

    return page_with_title(admin_groups_title(), array(
        table(array(
            'name' => _("Name"),
            'privileges' => _("Privileges"),
            'actions' => ''
        ), $groups_table)
    ));
  } else {
    switch ($_REQUEST["action"]) {
      case 'edit':
        if (isset($_REQUEST['id']) && preg_match("/^-[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error("Incomplete call, missing Groups ID.", true);

        $room = Groups_by_id($id);
        if (count($room) > 0) {
          list($room) = $room;
          $privileges = Privileges_Group_by_id($id);
          $privileges_html = "";
          $privileges_form = array();
          foreach ($privileges as $priv) {
            $privileges_form[] = form_checkbox('privileges[]', $priv['desc'] . ' (' . $priv['name'] . ')', $priv['group_id'] != "", $priv['id']);
            $privileges_html .= sprintf('<tr><td><input type="checkbox" ' . 'name="privileges[]" value="%s" %s />' . '</td> <td>%s</td> <td>%s</td></tr>', $priv['id'], ($priv['group_id'] != "" ? 'checked="checked"' : ''), $priv['name'], $priv['desc']);
          }

          $privileges_form[] = form_submit('submit', _("Save"));
          $html .= page_with_title(_("Edit group"), array(
              form($privileges_form, page_link_to('admin_groups_controller') . '&action=save&id=' . $id)
          ));
        } else
          return error("No Group found.", true);
        break;

      case 'save':
        if (isset($_REQUEST['id']) && preg_match("/^-[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error("Incomplete call, missing Groups ID.", true);

        $room = Groups_by_id($id);
        if (! is_array($_REQUEST['privileges']))
          $_REQUEST['privileges'] = array();
        if (count($room) > 0) {
          list($room) = $room;
          delete_GroupPrivileges($id);
          $privilege_names = array();
          foreach ($_REQUEST['privileges'] as $priv) {
            if (preg_match("/^[0-9]{1,}$/", $priv)) {
              $group_privileges_source = Privileges_by_id($priv);
              if (count($group_privileges_source) > 0) {
                insert_GroupPrivilege($id, $priv);
                $privilege_names[] = $group_privileges_source[0]['name'];
              }
            }
          }
          engelsystem_log("Group privileges of group " . $room['Name'] . " edited: " . join(", ", $privilege_names));
          redirect(page_link_to("admin_groups_controller"));
        } else
          return error("No Group found.", true);
        break;
    }
  }
  return $html;
}
?>
