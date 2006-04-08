{if $message}
    <p>{$message}</p>
{/if}

<table width='100%' cellspacing='0' cellpadding='0' style='border: solid 1px black;'>

<tr><td colspan='6' class='dateheader'>Corporation Statistics - {$corp}</td></tr>

<tr><td rowspan='12' width='256'>&nbsp;</td>
    <td class='headers bl c'>&nbsp;</td>
    <td class='headers bl c'>This Week</td>
    <td class='headers bl c'>This Month</td>
    <td class='headers bl c'>This Year</td>
    <td class='headers bl c'>All Time</td>
</tr>

{foreach from=$headers item=name key=stat}

{if $name}

<tr>
    <td class='headers bl' style='border-bottom: none; border-top: none;'>{$name}</td>
{foreach from=$iter item=i}
    <td class='bl c'>{$stats[$i][$stat]+0|commify}</td>
{/foreach}
</tr>

{else}

<tr>
<td colspan='5' class='headers bl'>&nbsp;</td>
</tr>

{/if}

{/foreach}

</table>

<br />

<table style='width: 100%;' cellspacing='0' cellpadding='0'>
<tr><td style='width: 25%; vertical-align: top;'>

{assign var=t10which value='Ships Flown'}
{assign var=t10rows value=$t10shipsflown}
{include file="show_top10_list.tpl"}

</td><td style='width: 5%;'>&nbsp;</td>
<td style='width: 40%; vertical-align: top;'>

{assign var=t10which value='Favorite Targets'}
{assign var=t10rows value=$t10targets}
{include file="show_top10_list.tpl"}

</td><td style='width: 5%;'>&nbsp;</td>
<td style='width: 25%; vertical-align: top;'>

{assign var=t10which value='Favorite Systems'}
{assign var=t10rows value=$t10systems}
{include file="show_top10_list.tpl"}

</td></tr>
</table>

<br />

<table style='width: 100%;' cellspacing='0' cellpadding='0'>
<tr><td style='width: 25%; vertical-align: top;'>

{assign var=t10which value='Ships Destroyed'}
{assign var=t10rows value=$t10shipsdestroyed}
{include file="show_top10_list.tpl"}

</td><td style='width: 5%;'>&nbsp;</td>
<td style='width: 40%; vertical-align: top;'>

{assign var=t10which value='Favorite Weapons'}
{assign var=t10rows value=$t10weapons}
{include file="show_top10_list.tpl"}

</td><td style='width: 5%;'>&nbsp;</td>
<td style='width: 25%; vertical-align: top;'>

{assign var=t10which value='Ships Lost'}
{assign var=t10rows value=$t10shipslost}
{include file="show_top10_list.tpl"}

</td></tr>
</table>

<br />

{assign var=title value="Last 10 Kills (<a href='/all_kills.php?type=kill&corpid=$corpid&which=k'>Show All</a>)"}
{assign var=killids value=$killids2}
{assign var=kills value=$kills2}
{include file="show_kills_dateless.tpl"}

{assign var=title value="Last 10 Losses (<a href='/all_kills.php?type=loss&corpid=$corpid&which=v'>Show All</a>)"}
{assign var=killids value=$lossids}
{assign var=kills value=$losses}
{include file="show_kills_dateless.tpl"}

{assign var=title value="Last 10 Kills (<a href='/all_kills.php?type=loss&corpid=$corpid&which=k'>Show All</a>)"}
{assign var=killids value=$xkillids}
{assign var=kills value=$xkills}
{include file="show_kills_dateless.tpl"}

{assign var=title value="Last 10 Losses (<a href='/all_kills.php?type=kill&corpid=$corpid&which=v'>Show All</a>)"}
{assign var=killids value=$xlossids}
{assign var=kills value=$xlosses}
{include file="show_kills_dateless.tpl"}


