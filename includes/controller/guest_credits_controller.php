<?php
function credits_title() {
  return _("Credits");
}

function guest_credits() {
  return template_render('../templates/guest_credits.html', array());
}
?>