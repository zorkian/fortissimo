{capture assign=kills}
{assign var="lastdate" value=""}
{foreach from=$killids item=i}
    {assign var="k" value=$kills[$i]}

    {if $lastdate != $k->date}
        {if $lastdate != ""}
            </div></div>
        {/if}
        {assign var="lastdate" value=$k->date}
        {assign var="rowborder" value=""}
        {if $class == "talt1"}
            {cycle print=false assign="class" values="talt1,talt2"}
        {/if}
        <div class='datebox'>
            <div class='dateheader'>Action on {$k->killtime|ymd}</div>
            <div class='datedata'>

    <table width='100%' {$rowborder} style='border-bottom: solid 1px black;'>
    <tr>
        <th class='headers {$class}' width='30%' valign='center'>
            &nbsp;Victim
        </th>
        <th class='headers {$class}' width='30%'>
            &nbsp;Final Blow
        </th>
        <th class='headers {$class}' width='12%' align='center'>
            System
        </th>
        <th class='headers {$class}' width='6%' align='center'>
            &nbsp;
        </th>
        <th class='headers {$class}' width='16%' align='center'>
            &nbsp;
        </th>
        <th class='headers {$class}' width='100%' align='center'>
            &nbsp;
        </th>
    </tr>
    </table>

    {/if}

    {cycle print=false assign="class" values="talt1,talt2"}

    <table width='100%' {$rowborder}>
    <tr>
        <td class='box {$class}' width='30%' valign='center'>
            {$k->victim->img32()}
            <strong>{$k->victim->pilot_link()}</strong><br />
            {$k->victim->corp_link()}
        </td>
        <td class='box {$class}' width='30%'>
            {$k->killer->img32()}
            <strong>{$k->killer->pilot_link()}</strong><br />
            {$k->killer->corp_link()}
        </td>
        <td class='box {$class}' width='12%' align='center'>
            <strong>{$k->system_link()}</strong><br />
            {$k->region_link()}
        </td>
        <td class='box {$class}' width='6%' align='center'>
            <span style='color: {$k->security|security_color};'>
                <strong>{$k->security|security}</strong>
            </span>
        </td>
        <td class='{$class}' width='16%' align='center'>
            <a href="{$SITEROOT}/killmail.php?killid={$k->kill_id}">Show Info</a><br />
            <a href="{$SITEROOT}/killmail.php?killid={$k->raw_id}&raw=1" target="_blank">Raw Mail</a>
        </td>
        <td class='{$class}' width='100%' align='center'>
            {$k->killtime|minsecs}
        </td>
    </tr>
    </table>
    {assign var="rowborder" value="style='border-top: solid 1px black;'"}
{/foreach}
{/capture}

{if $kills}
    {$kills}
    </div></div>
{else}
    <p>Looks like there are none!</p>
{/if}

