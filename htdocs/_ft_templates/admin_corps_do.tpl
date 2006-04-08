{capture assign=out}
{foreach from=$output item=row}

<strong>{if is_null($row[1])}General{else}{$row[1]->name}{/if}:</strong>

{if $row[0] == 'ok'}
    <span class='message'>
{else}
    <span class='error'>
{/if}

{$row[2]}

</span><br />

{/foreach}
{/capture}

{if $out}
{$out}
{else}

<p>You have not requested any changes.  As such, we have changed nothing.</p>

{/if}

<p><a href='{$SITEROOT}/admin_corps.php'>Click here to go back to editing corporations.</a></p>
