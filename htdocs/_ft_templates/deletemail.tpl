<p>You are requested to completely <strong>remove</strong> the referenced kill.</p>

<p>This action is irreversible.  We will keep the kill on file in the database,
flagged as deleted, to ensure that nobody can come along after and recreate the
kill by reposting it.</p>

<p>If you are really sure you want to delete this mail, use the button.</p>

<form method='post' action='{$SITEROOT}/deletemail_do.php'>
<input type='hidden' name='raw' value='{$raw}' />
<input type='hidden' name='killid' value='{$killid}' />
<input type='submit' value='Permanently Erase Kill' />
</form>
