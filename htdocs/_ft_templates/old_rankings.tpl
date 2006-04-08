<form method='get' action='{$SITEROOT}/rankings.php'>
<table>
<tr>
    <td>
        Show
        <select name='by'>
            <option value='killgive' {if $byorig == 'killgive'}selected{/if}>Enemies Killed</option>
            <option value='lossrecv' {if $byorig == 'lossrecv'}selected{/if}>Losses to Enemies</option>
            <option value='murdergive' {if $byorig == 'murdergive'}selected{/if}>Friendlies Murdered</option>
            <option value='murderrecv' {if $byorig == 'murderrecv'}selected{/if}>Losses to Friendlies</option>
            <option value='finalblows' {if $byorig == 'finalblows'}selected{/if}>Final Blows</option>
            <option value='bountypoints' {if $byorig == 'bountypoints'}selected{/if}>Bounty Accumulated</option>
            <option value='iskdestroyed' {if $byorig == 'iskdestroyed'}selected{/if}>ISK Destroyed</option>
            <option value='isklost' {if $byorig == 'isklost'}selected{/if}>ISK Lost</option>
        </select>
    </td>
    <td>
        <select name='when'>
            <option value='ever' {if $whenorig == 'ever'}selected{/if}>Over All Time</option>
            <option value='week' {if $whenorig == 'week'}selected{/if}>This Week</option>
            <option value='month' {if $whenorig == 'month'}selected{/if}>This Month</option>
            <option value='year' {if $whenorig == 'year'}selected{/if}>This Year</option>
        </select>
    </td>
    <td>
        <select name='which'>
            <option value='pilot' {if $whichorig == 'pilot'}selected{/if}>By Pilot</option>
            <option value='corp' {if $whichorig == 'corp'}selected{/if}>By Corporation</option>
            <option value='system' {if $whichorig == 'system'}selected{/if}>By System</option>
            <option value='region' {if $whichorig == 'region'}selected{/if}>By Region</option>
            <option value='weapon' {if $whichorig == 'weapon'}selected{/if}>By Weapon</option>
            <option value='ship' {if $whichorig == 'ship'}selected{/if}>By Ship</option>
            <option value='group' {if $whichorig == 'group'}selected{/if}>By Ship Class</option>
        </select>
    </td>
    <td>
        <input type='submit' value='Go!' />
    </td>
</tr>
</table>
</form>

{if $custom}

<p>To view rankings, please select what you want to view from the list above and click the button.</p>

{else}

{capture assign=rankings}
{foreach from=$data item=row}
    {cycle assign=class values="talt1,talt2"}
    <tr><td class='cell {$class}'>{$row[0]}</td>
        <td class='cell {$class}'>{$row[1]|commify}</td></tr>
{/foreach}
{/capture}

{if $rankings != ""}
    <table style='border: solid 1px black;' cellspacing='0' cellpadding='0'>
        <tr><td colspan='2' class='dateheader'>{$by} {$which} {$when}</td></tr>
        <tr><th class='headers bb'>Value</th><th class='headers bb'>Number</th></tr>
        {$rankings}
    </table>
{else}
    <p>There is no data for the view you have selected.  Sorry!</p>
{/if}

{/if}
