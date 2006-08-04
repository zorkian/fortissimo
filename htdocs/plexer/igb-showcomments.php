<?php

	$comments = get_comments($schedid);
	foreach ($comments as $comment) {
		$pilot = get_pilot_name($comment->pilotid);
		$content = preg_replace('/\r?\n/', '<br />', $comment->content);
		$content = preg_replace('/</', '&lt;', $content);
		$out .= "<font color='yellow'>Comment by <font color='red'>$pilot</font> at <font color='red'>";
		$out .= $comment->leftdate . "</font>...</font><br />$content<br /><br />";
	}
	
	# add a comment plz
	$out .= "<form method='post' action='igb-postcomment.php'>";
	$out .= "<input type='hidden' name='id' value='$id' />";
	$out .= "<input type='hidden' name='schedid' value='$schedid' />";
	$out .= "Add Comment:<br /><textarea name='comment' rows='3' cols='50'></textarea>";
	$out .= "<br /><input type='submit' value='Add Comment' /></form>";

?>