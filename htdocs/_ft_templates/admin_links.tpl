{if $message}
    <span class='message'>{$message}</span>
{/if}

{if $error}
    <span class='error'>{$error}</span>
{/if}

<p>Here are the currently defined top bar links.</p>

<p><strong>Note:</strong> The sort order is the order in which the links will appear.  Lower numbers
will appear first in the list.  All links appear horizontally in the top bar.</p>

<p>To delete a link, just empty out the three boxes in that row and click Save Changes.</p>

<form method='post' action='{$SITEROOT}/admin_links_do.php'>

<table style='border: solid 1px black;' cellspacing='0' cellpadding='0'>

<tr><th colspan='4' class='dateheader'>Link List</th></tr>

<tr><th class='headers'>Sort</th>
    <th class='headers'>Name</th>
    <th class='headers'>URL</th></tr>

{foreach from=$links item=link}
    {cycle values="talt1,talt2" assign="rowborder"}
    <tr>
        <td class="cell {$rowborder}"><input type='text' name='sort_{$link->linkid}' value='{$link->sort}' size='5' /></td>
        <td class="cell {$rowborder}"><input type='text' name='name_{$link->linkid}' value='{$link->name}' size='20' /></td>
        <td class="cell {$rowborder}"><input type='text' name='url_{$link->linkid}' value='{$link->url}' size='30' /></td>
    </tr>
{/foreach}
    <tr><td class='headers' colspan='3'>Create a new link here:</td></tr>
    <tr>
        <td class="cell {$rowborder}"><input type='text' name='sort_new' value='' size='5' /></td>
        <td class="cell {$rowborder}"><input type='text' name='name_new' value='' size='20' /></td>
        <td class="cell {$rowborder}"><input type='text' name='url_new' value='' size='30' /></td>
    </tr>

</table>

<input type='submit' value='Save Changes' />
</form>

