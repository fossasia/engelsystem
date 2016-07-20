<?php

function messages_title() {
  return _("Messages");
}

function user_unread_messages() {
  global $user;

  if (isset($user)) {
    $new_messages = sql_num_query("SELECT * FROM `Messages` WHERE isRead='N' AND `RUID`='" . sql_escape($user['UID']) . "'");
    if ($new_messages > 0)
      return ' <span class="badge danger">' . $new_messages . '</span>';
  }
  return '';
}

function user_messages() {
  global $user;

  if (! isset($_REQUEST['action'])) {
    $users = sql_select("SELECT * FROM `User` WHERE NOT `UID`='" . sql_escape($user['UID']) . "' ORDER BY `Nick`");
    $groups = sql_select("SELECT * FROM `Groups` ORDER BY `Name`");
    $angeltype = sql_select("SELECT * FROM `AngelTypes` ORDER BY  `name`");
    // no of users and +1 for admin
    $no = count($users) + 1;

    $to_select_data = array(
        "" => _("Select recipient...")
    );

    foreach ($users as $u)
      $to_select_data[$u['UID']] = $u['Nick'];

    foreach ($groups as $grp)
      $to_select_data[$grp['UID']] = "Group" . "-" . $grp['Name'];

    foreach ($angeltype as $angel)
      $to_select_data[$angel['id'] + $no] = "AngelType" . " - " . $angel['name'];

    $to_select = html_select_key('to', 'to', $to_select_data, '');

    $messages = sql_select("SELECT * FROM `Messages` WHERE `SUID`='" . sql_escape($user['UID']) . "' OR `RUID`='" . sql_escape($user['UID']) . "' ORDER BY `isRead`,`Datum` DESC");

    $messages_table = [
        [
            'news' => '',
            'timestamp' => date("Y-m-d H:i"),
            'from' => User_Nick_render($user),
            'to' => $to_select,
            'text' => form_textarea('text', '', ''),
            'actions' => form_submit('submit', _("Save"))
        ]
    ];

    foreach ($messages as $message) {
      $sender_user_source = User($message['SUID']);
      if ($sender_user_source === false)
        engelsystem_error(_("Unable to load user."));
      $receiver_user_source = User($message['RUID']);
      if ($receiver_user_source === false)
        engelsystem_error(_("Unable to load user."));

      $messages_table_entry = array(
          'new' => $message['isRead'] == 'N' ? '<span class="glyphicon glyphicon-envelope"></span>' : '',
          'timestamp' => date("Y-m-d H:i", $message['Datum']),
          'from' => User_Nick_render($sender_user_source),
          'to' => User_Nick_render($receiver_user_source),
          'text' => str_replace("\n", '<br />', $message['Text'])
      );

      if ($message['RUID'] == $user['UID']) {
        if ($message['isRead'] == 'N')
          $messages_table_entry['actions'] = button(page_link_to("user_messages") . '&action=read&id=' . $message['id'], _("mark as read"), 'btn-xs');
      } else
        $messages_table_entry['actions'] = button(page_link_to("user_messages") . '&action=delete&id=' . $message['id'], _("delete message"), 'btn-xs');
      $messages_table[] = $messages_table_entry;
    }

    return page_with_title(messages_title(), array(
        msg(),
        sprintf(_("Hello %s, here you can leave messages for other angels or all the members of groups/angeltypes"), User_Nick_render($user)),
        form(array(
            table(array(
                'new' => _("New"),
                'timestamp' => _("Date"),
                'from' => _("Transmitted"),
                'to' => _("Recipient"),
                'text' => _("Message"),
                'actions' => ''
            ), $messages_table)
        ), page_link_to('user_messages') . '&action=send')
    ));
  } else {
    switch ($_REQUEST['action']) {
      case "read":
        if (isset($_REQUEST['id']) && preg_match("/^[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error(_("Incomplete call, missing Message ID."), true);

        $message = sql_select("SELECT * FROM `Messages` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
        if (count($message) > 0 && $message[0]['RUID'] == $user['UID']) {
          sql_query("UPDATE `Messages` SET `isRead`='Y' WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
          redirect(page_link_to("user_messages"));
        } else
          return error(_("No Message found."), true);
        break;

      case "delete":
        if (isset($_REQUEST['id']) && preg_match("/^[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error(_("Incomplete call, missing Message ID."), true);

        $message = sql_select("SELECT * FROM `Messages` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
        if (count($message) > 0 && $message[0]['SUID'] == $user['UID']) {
          sql_query("DELETE FROM `Messages` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
          redirect(page_link_to("user_messages"));
        } else
          return error(_("No Message found."), true);
        break;

      case "send":
        $no_users = sql_num_query("SELECT * FROM `User`");
        $temp = 0;
        if ($_REQUEST['to'] < 0) {
          $group_users = sql_select("SELECT * FROM `UserGroups` WHERE `group_id`='" . sql_escape($_REQUEST['to']) . "'");

          foreach ($group_users as $u_id) {
            Message_send($u_id[uid],  $_REQUEST['text']);
            $temp++;
          }

          if (count($group_users) == 0) {
            success(_("There are no members in the selected group"));
            redirect(page_link_to("user_messages"));
          } elseif (count($group_users) == $temp) {
            redirect(page_link_to("user_messages"));
          } else {
          return error(_("Transmitting was terminated with an Error."), true);
          }
        } elseif ($_REQUEST['to'] > $no_users) {
          $id = $_REQUEST['to'] - $no_users;
          $users_source = sql_select("SELECT * FROM `UserAngelTypes` WHERE `angeltype_id`='" . sql_escape($id) . "'");

          foreach ($users_source as $userid) {
            Message_send($userid['user_id'],  $_REQUEST['text']);
            $temp++;
          }

          if (count($users_source) == 0) {
            success(_("There are no members in the selected Angeltype"));
            redirect(page_link_to("user_messages"));
          } elseif (count($users_source) == $temp) {
            redirect(page_link_to("user_messages"));
          } else {
            return error(_("Transmitting was terminated with an Error."), true);
          }
        } elseif (Message_send($_REQUEST['to'], $_REQUEST['text']) === true) {
          redirect(page_link_to("user_messages"));
        } else {
          return error(_("Transmitting was terminated with an Error."), true);
        }
        break;

      default:
        return error(_("Wrong action."), true);
    }
  }
}
?>
