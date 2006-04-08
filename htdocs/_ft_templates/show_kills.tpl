
{capture assign=killx}
{assign var="lastdate" value=""}
{foreach from=$killids item=i}
    {assign var="k" value=$kills[$i]}

    {if $lastdate != $k->date}
        {if $lastdate != ""}
            </table>
        {/if}
        {assign var="lastdate" value=$k->date}
        {assign var="rowborder" value=""}
        {if $class == "talt1"}
            {cycle print=false assign="class" values="talt1,talt2"}
        {/if}
        <table class='datebox' width='100%' cellspacing='0' cellpadding='0'>
            <tr><td colspan='8' class='dateheader'>Action on {$k->killtime|ymd}</td></tr>

    <tr>
        <th colspan='2' class='headers br {$class}' width='30%' valign='center'>
            &nbsp;Victim
        </th>
        <th colspan='2' class='headers br {$class}' width='30%'>
            &nbsp;Final Blow
        </th>
        <th class='headers br {$class}' width='12%' align='center'>
            System
        </th>
        <th class='headers br {$class}' width='6%' align='center'>
            Sec.
        </th>
        <th class='headers br {$class}' width='16%' align='center'>
            Links
        </th>
        <th class='headers {$class}' width='6%' align='center'>
            Time
        </th>
    </tr>

    {/if}

    {cycle print=false assign="class" values="talt1,talt2"}

{if hide_location($k)}
    {assign var=hide value=1}
{else}
    {assign var=hide value=0}
{/if}

{*    <tr onMouseOver="return doHoverKill({$k->kill_id});">*}
    <tr>
        <td id='t1k{$k->kill_id}' class='box {$class} {$rowborder}' width='64' valign='center'>
            {$k->victim->img32()}
        </td>
        <td id='t2k{$k->kill_id}' class='ptl box br {$class} {$rowborder}' width='26%' valign='center'>
            <strong>{$k->victim->pilot_link()}</strong> [{$k->victim->ticker_link()}]<br />
            {$k->victim->ship_link()}
        </td>
        <td id='t3k{$k->kill_id}' class='box {$class} {$rowborder}' width='64' valign='center'>
            {$k->killer->img32()}
        </td>
        <td id='t4k{$k->kill_id}' class='ptl box br {$class} {$rowborder}' width='26%'>
            <strong>{$k->killer->pilot_link()}</strong> [{$k->killer->ticker_link()}]<br />
            {$k->killer->ship_link()}
        </td>
        <td id='t5k{$k->kill_id}' class='box br {$class} {$rowborder}' width='12%' align='center'>
            {if ! $hide}
                <strong>{$k->system_link()}</strong><br />
                {$k->region_link()}
            {else}
                <em>Secured</em>
            {/if}
        </td>
        <td id='t6k{$k->kill_id}' class='box br {$class} {$rowborder}' width='6%' align='center'>
            {if ! $hide}
                <span style='color: {$k->security|security_color};'>
                    <strong>{$k->security|security}</strong>
                </span>
            {else}
                -
            {/if}
        </td>
        <td id='t7k{$k->kill_id}' class='br {$class} {$rowborder}' width='16%' align='center'>
            <a href="/killmail.php?killid={$k->kill_id}">Show Info</a><br />
            <a href="/killmail.php?killid={$k->raw_id}&raw=1" target="_blank">Raw Mail</a>
        </td>
        <td id='t8k{$k->kill_id}' class='{$class} {$rowborder}' width='6%' align='center'>
            {$k->killtime|minsecs}
        </td>
    </tr>
    {assign var="rowborder" value="bt"}
{/foreach}
{/capture}

{if $killx}
    {$killx}
    </table>
{else}
    <p>Looks like there are none!</p>
{/if}

