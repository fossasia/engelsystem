<?php

function messages_title() {
  return _("Messages");
}

function user_unread_messages() {
  global $user;

  if (isset($user)) {
    $new_messages = message_unread($user['UID']);
    if ($new_messages > 0)
      return ' <span class="badge danger">' . $new_messages . '</span>';
  }
  return '';
}

function user_messages() {
  global $user;

  if (! isset($_REQUEST['action'])) {
    $users = user_by_nick($user['UID']);
    $groups = select_group();
    $angeltype = select_angeltypes();
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

    $messages = select_messages($user['UID']);

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

        $message = messages_by_id($id);
        if (count($message) > 0 && $message[0]['RUID'] == $user['UID']) {
          messages_read_by_id($id);
          redirect(page_link_to("user_messages"));
        } else
          return error(_("No Message found."), true);
        break;

      case "delete":
        if (isset($_REQUEST['id']) && preg_match("/^[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error(_("Incomplete call, missing Message ID."), true);

        $message = messages_by_id($id);
        if (count($message) > 0 && $message[0]['SUID'] == $user['UID']) {
          messages_delete($id);
          redirect(page_link_to("user_messages"));
        } else
          return error(_("No Message found."), true);
        break;

      case "send":
        $no_users = user_count();
        $temp = 0;
        if ($_REQUEST['to'] < 0) {
          $group_users = select_usergroups($_REQUEST['to']);

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
          $users_source = select_userangeltypes($id);

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
