<style>
{literal}
.kill {
    border: solid 1px black;
    padding: 5px;
    margin: 10px;
}
.date {
    text-align: center;
    font-size: 1.5em;

}
.info1 {
    padding: 0px 4px 0px 2px;
    width: 1px;
    border-bottom: solid 1px black;
}
.mw {
    width: 1px;
    padding: 0px 6px 0px 2px;
}
.info {
    padding-left: 2px;
    border-bottom: solid 1px black;
    border-right: solid 1px black;
}
.gray {
    /*color: #999999;*/
    font-weight: bold;
}
.item {
    padding-left: 3px;
}
{/literal}
</style>

{if hide_location($kill)}
    {assign var=hide value=1}
{else}
    {assign var=hide value=0}
{/if}

<table width='100%'>
    <tr><td class='kill date' colspan='2'>Registered {$kill->killtime|ymd} @ {$kill->killtime|minsecs} 
        (<a href='{$SITEROOT}/killmail.php?killid={$kill->raw_id}&raw=1'>See Raw</a>)
{if $remote && $remote->manager()}
    <br />
        <span style='font-size: 0.75em;'>
            <em>Posted by {$submitpilot->pilot_link()} ({$submitip} @ {$submittime}).</em>
        </span>
    <br />
    <form method='get' action='{$SITEROOT}/deletemail.php'>
    <input type='hidden' name='killid' value='{$kill->raw_id}' />
    <input type='hidden' name='raw' value='1' />
    <input type='submit' value='Delete Kill' />
    </form>
{/if}
        </td></tr>
    <tr>
        <td class='kill'>
            <!-- VICTIM INFORMATION -->
            <table width='100%' cellspacing='0'>
                <tr><td colspan="3" class='dateheader'>Victim</td></tr>
                <tr><td rowspan="6" width='128'>{$kill->victim->img128()}</td>
                    <td class='info1'><span class='gray'>Pilot:</span></td>
                    <td class='info'><strong>{$kill->victim->pilot_link()}</strong></td></tr>
                <tr><td class='info1'><span class='gray'>Corp:</span></td>
                    <td class='info'>{$kill->victim->corp_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>Alliance:</span></td>
                    <td class='info'>{if $kill->victim->alliance}{$kill->victim->alliance_link()}{else}none{/if}</td></tr>
                <tr><td class='info1'><span class='gray'>Ship:</span></td>
                    <td class='info'>{$kill->victim->ship_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>Class:</span></td>
                    <td class='info'>{$kill->victim->group_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>System:</span></td>
                    <td class='info'>
                    {if ! $hide}
                        {$kill->system_link()}
                        <span style='color: {$kill->security|security_color};'>({$kill->security|security})</span>
                    {else}
                        <em>Secured</em>
                    {/if}
                    </td></tr> 
            </table>
        </td>
        <td class='kill'>
            <!-- KILLER INFORMATION -->
            <table width='100%' cellspacing='0'>
                <tr><td colspan="3" class='dateheader'>Final Blow</td></tr>
                <tr><td rowspan="6" width='128'>{$kill->killer->img128()}</td>
                    <td class='info1'><span class='gray'>Pilot:</span></td>
                    <td class='info'><strong>{$kill->killer->pilot_link()}</strong></td></tr>
                <tr><td class='info1'><span class='gray'>Corp:</span></td>
                    <td class='info'>{$kill->killer->corp_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>Alliance:</span></td>
                    <td class='info'>{if $kill->killer->alliance}{$kill->killer->alliance_link()}{else}none{/if}</td></tr>
                <tr><td class='info1'><span class='gray'>Ship:</span></td>
                    <td class='info'>{$kill->killer->ship_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>Class:</span></td>
                    <td class='info'>{$kill->killer->group_link()}</td></tr>
                <tr><td class='info1'><span class='gray'>Weapon:</span></td>
                    <td class='info'>{if $kill->killer->weapon}{$kill->killer->weapon_link()}{else}unknown{/if}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class='kill' valign='top'>
            <table width='100%' cellspacing='0' cellpadding='0'>
                <tr><td colspan="3" class='dateheader ptb bt bl br'>Equipped</td></tr>
{foreach from=$kill->destroyed item=item}
    {if $item->loc == 'fitted'}
        {cycle assign=class values="talt1,talt2"}
                <tr><td class='{$class}' width='32'>{$item->img32()}</td>
                    <td class='item bt {$class}' valign='center'>{$item->item_link()}</td>
                    <td class='item bt br {$class}' valign='center'>{if $item->quantity > 1}{$item->quantity}{else}&nbsp;{/if}</td></tr>
    {/if}
{/foreach}
                <tr><td colspan="3" class='bt'><span style='font-size: 0.5em;'>&nbsp;</span></td></tr>
                <tr><td colspan="3" class='dateheader bb ptb bt br bl'>Cargo</td></tr>
{foreach from=$kill->destroyed item=item}
    {if $item->loc != 'fitted'}
        {cycle assign=class values="talt1,talt2"}
                <tr><td class='{$class}' width='32'>{$item->img32()}</td>
                    <td class='item bb {$class}' valign='center'>{$item->item_link()}</td>
                    <td class='item bb br {$class}' valign='center'>{if $item->quantity > 1}{$item->quantity}{else}&nbsp;{/if}</td></tr>
    {/if}
{/foreach}
            </table>
        </td>
        <td class='kill' valign='top'>
            <table width='100%' cellspacing='0' cellpadding='0'>
                <tr><td colspan="3" class='dateheader ptb bt bl br'>Involved Parties</td></tr>
{foreach from=$kill->attackers item=killer}
    {if ! $killer->finalblow}
                <tr><td colspan='3'><span style='font-size: 0.5em;'>&nbsp;</span></td></tr>
                <tr><td class='bt bb bl' rowspan="4" width='64'>{$killer->img64()}</td>
                    <td class='bt mw'><span class='gray'>Pilot:</span></td>
                    <td class='bt br'><strong>{$killer->pilot_link()}</strong></td></tr>
                <tr><td class='mw'><span class='gray'>Corp:</span></td>
                    <td class='br'>{$killer->corp_link()}</td></tr>
                <tr><td class='mw'><span class='gray'>Ship:</span></td>
                    <td class='br'>{$killer->ship_link()}</td></tr>
                <tr><td class='bb mw'><span class='gray'>Weapon:</span></td>
                    <td class='br bb'>{if $killer->weapon}{$killer->weapon_link()}{else}unknown{/if}</td></tr>
    {/if}
{/foreach}
            </table>
        </td>
    </tr>
</table>

{if !$hide}
<table width='100%' cellspacing='0' cellpadding='0' style='border: solid 1px black;'>
<tr>
<th class='blkheader c br'>System: {$kill->system}</th>
<th class='blkheader c br'>Constellation: {$kill->constellation}</th>
<th class='blkheader c'>Region: {$kill->region}</th>
</tr>
<tr>
<td>{$kill->system_img256()}</td>
<td>{$kill->constellation_img256()}</td>
<td>{$kill->region_img256()}</td>
</tr>
</table>
{/if}
