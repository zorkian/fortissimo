{if $error}
    <span class='error'>{$error}</span>
{/if}

{if $list}
<ul>
{foreach from=$list item=i}
<li>{$i}</li>
{/foreach}
</ul>
{/if}

{if $go}

{if $killids}
    <p>Here are the requested results for {$search_term}:</p>

    {include file="show_kills_dateless.tpl"}

{elseif $brows}

    <table cellspacing='0' cellpadding='0' style='border: solid 1px black;'>
        <tr><th class='dateheader' colspan='6'>Bounty Summary</th></tr>
        <tr><th class='headers br'>Pilot/Corp</td>
            <th class='headers br'>Participations</td>
            <th class='headers br'>Final Blows</td>
            <th class='headers br'>Start Date</td>
            <th class='headers br'>End Date</td>
            <th class='headers'>Total Bounty</td></tr>
    {foreach from=$brows item=brow}
        {cycle print=false assign="class" values="talt1,talt2"}
        <tr>
            <td class='pta br {$class}'><strong>{$brow[0]->pilot_link()}</strong></td>
            <td class='pta br c {$class}'>{$brow[5]}</td>
            <td class='pta br c {$class}'>{$brow[4]}</td>
            <td class='pta br {$class}'>{$brow[2]|ymd}</td>
            <td class='pta br {$class}'>{$brow[3]|ymd}</td>
            <td class='pta r {$class}'>{$brow[1]|commify} ISK</td>
        </tr>
    {/foreach}
    </table>

{else}

{if !$error}
    <p><span class='message'>Sorry, your search for {$search_term} didn't return any data.  Please try broadening the scope.</span></p>
{/if}

{/if}

<p>Perform a new search by entering your new criteria below.</p>

{else}

<p>Please enter some search criteria below and click the button.</p>

{/if}

<form method='get' action='{$SITEROOT}/search.php'>
<table>
<tr>
    <td>Search for</td>
    <td><select name='whatis'>
            <option value='pilot' {if $whatis == 'pilot'}selected{/if}>Pilot</option>
            <option value='corp' {if $whatis == 'corp'}selected{/if}>Corp</option>
        </select> <input type='text' name='what' size='20' value='{$what}' /></td>
    <td><em>partial names are okay</em></td>
</tr>
<tr>
    <td>Where they</td>
    <td><select name='which'>
            <option value='killgive' {if $which == 'killgive'}selected{/if}>Participated</option>
            <option value='finalblows' {if $which == 'finalblows'}selected{/if}>Got the Final Blow</option>
        </select>
        </td>
    <td></td>
</tr>
<tr>
    <td>On a</td>
    <td><select name='type'>
            <option value='kill' {if $type == 'kill'}selected{/if}>Kill</option>
            <option value='loss' {if $type == 'loss'}selected{/if}>Loss</option>
            <option value='murder' {if $type == 'murder'}selected{/if}>Murder</option>
        </select>
        </td>
    <td></td>
</tr>
<tr>
    <td>On or after date</td>
    <td><select name='start_month'>
            <option value='00' {if ! $start_month}selected{/if}>(no start)</option>
            <option value='01' {if $start_month == '01'}selected{/if}>January</option>
            <option value='02' {if $start_month == '02'}selected{/if}>February</option>
            <option value='03' {if $start_month == '03'}selected{/if}>March</option>
            <option value='04' {if $start_month == '04'}selected{/if}>April</option>
            <option value='05' {if $start_month == '05'}selected{/if}>May</option>
            <option value='06' {if $start_month == '06'}selected{/if}>June</option>
            <option value='07' {if $start_month == '07'}selected{/if}>July</option>
            <option value='08' {if $start_month == '08'}selected{/if}>August</option>
            <option value='09' {if $start_month == '09'}selected{/if}>September</option>
            <option value='10' {if $start_month == '10'}selected{/if}>October</option>
            <option value='11' {if $start_month == '11'}selected{/if}>November</option>
            <option value='12' {if $start_month == '12'}selected{/if}>December</option>
        </select>
        <input type='text' name='start_day' size='2' value='{$start_day}' />, 
        <input type='text' name='start_year' size='6' value='{$start_year}' />
    </td>
    <td><em>dates are entirely optional</em></td>
</tr>
<tr>
    <td>On or before date</td>
    <td><select name='end_month'>
            <option value='00' {if ! $end_month}selected{/if}>(no end)</option>
            <option value='01' {if $end_month == '01'}selected{/if}>January</option>
            <option value='02' {if $end_month == '02'}selected{/if}>February</option>
            <option value='03' {if $end_month == '03'}selected{/if}>March</option>
            <option value='04' {if $end_month == '04'}selected{/if}>April</option>
            <option value='05' {if $end_month == '05'}selected{/if}>May</option>
            <option value='06' {if $end_month == '06'}selected{/if}>June</option>
            <option value='07' {if $end_month == '07'}selected{/if}>July</option>
            <option value='08' {if $end_month == '08'}selected{/if}>August</option>
            <option value='09' {if $end_month == '09'}selected{/if}>September</option>
            <option value='10' {if $end_month == '10'}selected{/if}>October</option>
            <option value='11' {if $end_month == '11'}selected{/if}>November</option>
            <option value='12' {if $end_month == '12'}selected{/if}>December</option>
        </select>
        <input type='text' name='end_day' size='2' value='{$end_day}' />, 
        <input type='text' name='end_year' size='6' value='{$end_year}' />
    </td>
    <td></td>
</tr>
<tr>
    <td>Show me</td>
    <td><select name='output'>
            <option value='kills' {if $output == 'kills'}selected{/if}>Individual Kills</option>
            <option value='bounty' {if $output == 'bounty'}selected{/if}>Bounty Summary (Screen Format)</option>
            <option value='bountyp' {if $output == 'bountyp'}selected{/if}>Bounty Summary (Print Format)</option>
        </select></td>
    <td></td>
</tr>
<tr>
    <td><input type='hidden' name='go' value='1' /></td>
    <td><input type='submit' value='Perform Search' /></td>
    <td></td>
</tr>
</table>
</form>
