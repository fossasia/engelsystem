<?php
function questions_title() {
  return _("Ask an archangel");
}
function user_questions() {
  global $user;
  if (! isset($_REQUEST['action'])) {
    $open_questions = select_open_questions($user['UID']);
    $answered_questions = select_ansd_questions($user['UID']);
    foreach ($answered_questions as &$question) {
      $answer_user_source = User($question['AID']);
      if ($answer_user_source === false)
        engelsystem_error(_("Unable to load user."));
      $question['answer_user'] = User_Nick_render($answer_user_source);
    }
    return Questions_view($open_questions, $answered_questions, page_link_to("user_questions") . '&action=ask');
  } else {
    switch ($_REQUEST['action']) {
      case 'ask':
        $question = strip_request_item_nl('question');
        if ($question != "") {
          $result = insert_new_question($user['UID'], $question);
          if ($result === false)
            engelsystem_error(_("Unable to save question."));
          success(_("You question was saved."));
          redirect(page_link_to("user_questions"));
        } else
          return page_with_title(questions_title(), array(
              error(_("Please enter a question!"), true)
          ));
        break;
      case 'delete':
        if (isset($_REQUEST['id']) && preg_match("/^[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error(_("Incomplete call, missing Question ID."), true);
        $question = select_ques_by_id($id);
        if (count($question) > 0 && $question[0]['UID'] == $user['UID']) {
          delete_ques_by_id($id);
          redirect(page_link_to("user_questions"));
        } else
          return page_with_title(questions_title(), array(
              error(_("No question found."), true)
          ));
        break;
    }
  }
}
?>