<div align='center'>
<form method='get' action='{$SITEROOT}/rankings2.php'>
<table>
<tr>
    <td class='c' colspan='9'>
        <select name='by'>
            <option value='killgive' {if $byorig == 'killgive'}selected{/if}>Kill Participations</option>
            <option value='lossrecv' {if $byorig == 'lossrecv'}selected{/if}>Losses to Enemies</option>
            <option value='murdergive' {if $byorig == 'murdergive'}selected{/if}>Friendlies Murdered</option>
            <option value='murderrecv' {if $byorig == 'murderrecv'}selected{/if}>Losses to Friendlies</option>
            <option value='finalblows' {if $byorig == 'finalblows'}selected{/if}>Final Blows</option>
            <option value='bountypoints' {if $byorig == 'bountypoints'}selected{/if}>Bounty Accumulated</option>
            <option value='iskdestroyed' {if $byorig == 'iskdestroyed'}selected{/if}>ISK Destroyed</option>
            <option value='isklost' {if $byorig == 'isklost'}selected{/if}>ISK Lost</option>
        </select>
        <select name='which'>
            <option value='pilot' {if $whichorig == 'pilot'}selected{/if}>By Pilot</option>
            <option value='corp' {if $whichorig == 'corp'}selected{/if}>By Corporation</option>
            <option value='system' {if $whichorig == 'system'}selected{/if}>By System</option>
            <option value='region' {if $whichorig == 'region'}selected{/if}>By Region</option>
            <option value='weapon' {if $whichorig == 'weapon'}selected{/if}>By Weapon</option>
            <option value='ship' {if $whichorig == 'ship'}selected{/if}>By Ship</option>
            <option value='group' {if $whichorig == 'group'}selected{/if}>By Ship Class</option>
        </select>
        <input type='submit' value='Go!' />
    </td>
</tr>

<tr><td>

{if $custom}

<p>To view rankings, please select what you want to view from the list above and click the button.</p>

{else}

{capture assign=rankings}
{foreach from=$count item=c}
    {cycle name=cx assign=class values="talt1,talt2"}
    <tr>
        <td class='cell {$class} c'>{$c+1}</td>
    {foreach from=$order item=o}
        {if $data[$o][$c][0]}
        <td class='cell {$class} bl l'>&nbsp;<strong>{$data[$o][$c][0]}</strong><br />
            &nbsp;&nbsp;&nbsp;&raquo;&nbsp;<span style='color: #9f9faf;'>{$data[$o][$c][1]|commify}</span>
            </td>
        {else}
        <td class='cell {$class} bl l'>&nbsp;</td>
        {/if}
    {/foreach}
    </tr>
{/foreach}
{/capture}

{if $rankings != ""}
    <table style='border: solid 1px black;' cellspacing='0' cellpadding='0' width='100%'>
        <tr><td colspan='9' class='dateheader c'>{$by} {$which}</td></tr>
        <tr><th class='headers'>#</th>
            <th class='headers bl' colspan='1'>This Week</th>
            <th class='headers bl' colspan='1'>This Month</th> 
            <th class='headers bl' colspan='1'>This Year</th> 
            <th class='headers bl' colspan='1'>All Time</th></tr>
        {$rankings}
    </table>
{else}
    <p>There is no data for the view you have selected.  Sorry!</p>
{/if}

{/if}

</td></tr>


</table>
</form>
</div>
