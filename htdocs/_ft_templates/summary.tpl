<table width='100%' cellpadding='0' cellspacing='0' style='border: solid 1px black;'>
<tr><th colspan='15' class='dateheader'>Kill Summary</th></tr>
<tr>
    <th class='headers'>Class</th>
    <th class='headers br bl' align='center' width='20'>K</th>
    <th class='headers' align='center' width='20'>L</th>
    <td class='br bl' style='background-color: #20203c; border-bottom: none;'>&nbsp;</td>
    <th class='headers'>Class</th>
    <th class='headers br bl' align='center' width='20'>K</th>
    <th class='headers' align='center' width='20'>L</th>
    <td class='br bl' style='background-color: #20203c; border-bottom: none;'>&nbsp;</td>
    <th class='headers'>Class</th>
    <th class='headers br bl' align='center' width='20'>K</th>
    <th class='headers' align='center' width='20'>L</th>
    <td class='br bl' style='background-color: #20203c; border-bottom: none;'>&nbsp;</td>
    <th class='headers'>Class</th>
    <th class='headers br bl' align='center' width='20'>K</th>
    <th class='headers' align='center' width='20'>L</th>
</tr>

{counter name=n print=false start=0 assign=ct}
{foreach from=$classes item=class}
    {if $ct % 4 == 0}
        {cycle values="talt1,talt2" assign="rowborder"}
        {if $ct >= 4}
            </tr>
        {/if}
        <tr>
    {else}
        <td class="bl br" style='background-color: #20203c; width: 7px; border-bottom: none; border-top: none;'>&nbsp;</td>
    {/if}
    <td class="pta {$rowborder}"><a href="{$SITEROOT}/show.php?groupid={$summary[$class][2]}">{$class}</a></td>
    <td class="br bl pta {$rowborder}" align='center'>
    {if $summary[$class][0] > 0}
        <span style='color: #00cc00; font-weight: bold;'>{$summary[$class][0]+0}</span>
    {else}
        -
    {/if}
    </td>
    <td class="pta {$rowborder}" align='center'>
    {if $summary[$class][1] > 0}
        <span style='color: #ff0000; font-weight: bold;'>{$summary[$class][1]+0}</span>
    {else}
        -
    {/if}
    </td>
    {counter name=n}
{/foreach}

</table>
