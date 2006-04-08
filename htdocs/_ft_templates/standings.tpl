<p>This page lists all corporations and alliances that we know about within the world of EVE.
If you don't see a group here, it's probably because we've never seen them on a killmail.  Why
don't you shoot someone and remedy that.  <tt>:-)</tt></p>

{capture assign=standings}
{foreach from=$sorted item=row}
    {if $row[0] == 'alliance'}
        <tr><td colspan='3' class='bt'>&nbsp;</td></tr>
        <tr><td colspan='3' class='dateheader bl bt br'>{$row[2]}</td></tr>
        {if ! is_null($row[4])}
            <tr><td colspan='3' class='headers bl br c' style='border-bottom: none;'><strong>{$row[4]}</strong></td></tr>
        {/if}
    {else}
        {if $row[3] != -999 && ! is_nulL($row[3])}
            {cycle values="talt1,talt2" assign="class"}
            <tr>
            <td class='{$class} bt bl pta c'>{$row[3]|standings}</td>
            <td class='{$class} bt pta'>{$row[2]}</td>
            <td class='{$class} br bt pta'><em>{$row[4]}</em>&nbsp;</td>
            </tr>
        {/if}
    {/if}
{/foreach}
{/capture}

{if $standings}
    <table cellspacing='0' cellpadding='0' width='98%'>
        <tr><td colspan='3' class='dateheader bl bt br'>No Alliance</td></tr>
        {$standings}
        <tr><td colspan='3' class='bt'>&nbsp;</td></tr>
    </table>
{else}
    <p>Sorry!  There are no standings in the system.  Please post a killmail!</p>
{/if}
