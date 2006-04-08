<p>Submitting a killmail is easy.  Just paste it into the box below and click the button!</p>

{if $message}
    <p><span class='message'>{$message}</span></p>
{/if}

{if $error}
    <p><span class='error'>{$error}</span></p>
{/if}

<form method='post' action='submit_do.php'>
    <textarea name='mail' rows='15' cols='70'>{$mail}</textarea><br />
    <input type='submit' value='Submit Killmail' />
</form>
