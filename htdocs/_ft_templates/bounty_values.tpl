{capture assign=shiplist}
{foreach from=$ships item=ship}
    {if is_null($ship->bountypoints)}
        <tr><td class='hilite'><strong>{$ship->name}</strong></td>
            <td class='hilite'>{$ship->groupname}</td>
            <td class='hilite'><input type='text' name='ship_{$ship->typeid}' value='{$ship->bountypoints}' size='10' /></td>
            <td class='hilite' align='right'>Please specify.</td></tr>
    {else}
        <tr><td><strong>{$ship->name}</strong></td>
            <td>{$ship->groupname}</td>
            <td><input type='text' name='ship_{$ship->typeid}' value='{$ship->bountypoints}' size='10' /></td>
            <td align='right'>{$ship->bountypoints|commify} ISK</td></tr>
    {/if}
{/foreach}
{/capture}

{if $shiplist != ''}

    <p>These are the bounties paid out to pilots for achieving the killing blow on a target
    of this type.</p>

    {if $message}
        <p><span class='message'>{$message}</span></p>
    {/if}

    <form method='post' action='bounty_values_do.php'>
        <table>
            {$shiplist}
            <tr><td></td>
                <td></td>
                <td><input type='submit' value='Save All' /></td>
                <td></td></tr>
        </table>
    </form>

    <p>Changes are permanent and cannot be undone.</p>

{else}

    <p>Sorry, there are no ships currently in the database.  Upload some killmails first, please.</p>
    <p>Yes, this is a bit of a pain.  Hopefully your killboard will get enough traffic so you can quickly
    get a good sample of data in here.  <tt>:-)</tt></p>

{/if}
