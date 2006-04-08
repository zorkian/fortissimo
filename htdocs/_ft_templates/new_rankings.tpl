<p>Use this page to put together a custom rankings list.</p>

<form method='get' action='{$SITEROOT}/rankings.php'>
<table>
<tr><td>Show me</td>
    <td>
        <select name='by'>
            <option value='killgive' selected>Enemies Killed</option>
            <option value='lossrecv'>Losses Taken</option>
            <option value='finalblows'>Final Blows</option>
            <option value='bountypoints'>Bounty Accumulated</option>
            <option value='iskdestroyed'>ISK Destroyed</option>
            <option value='isklost'>ISK Lost</option>
        </select>
    </td></tr>
<tr><td></td>
    <td>
        <select name='when'>
            <option value='ever' selected>Over All Time</option>
            <option value='week'>This Week</option>
            <option value='month'>This Month</option>
            <option value='year'>This Year</option>
        </select>
    </td></tr>
<tr><td></td>
    <td>
        <select name='which'>
            <option value='pilot'>By Pilot</option>
            <option value='corp'>By Corp</option>
            <option value='alliance'>By Alliance</option>
            <option value='system'>In System</option>
            <option value='region'>In Region</option>
            <xxoption value='weapon'></xxoption>
            <option value='ship'>By Ship</option>
            <option value='group'>By Ship Class</option>
        </select>
    </td></tr>
<tr><td></td>
    <td>
        <input type='submit' value='Rank' />
    </td></tr>
</table>
</form>
