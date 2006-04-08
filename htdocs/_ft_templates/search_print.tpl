<html>

<head>
<title>Bounty Report</title>
<style type='text/css'>
{literal}
.pta { padding: 2px 4px 2px 4px; }
.bl { border-left: solid 1px black; }
.bb { border-bottom: solid 1px black; }
.bt { border-top: solid 1px black; }
.br { border-right: solid 1px black; }
.r { text-align: right; }
.c { text-align: center; }
{/literal}
</style>
</head>

<body>

<p>This bounty report has been formatted plainly to work better with your printer.</p>

{if $error}
    <span class='error'>{$error}</span>
{/if}

{if $brows}

    <table cellspacing='0' cellpadding='0' style='border: solid 1px black;'>
        <tr><th class='bb' colspan='6'>Bounty Summary</th></tr>
        <tr><th class='pta br'>Pilot/Corp</td>
            <th class='pta br'>Participations</td>
            <th class='pta br'>Final Blows</td>
            <th class='pta br'>Start Date</td>
            <th class='pta br'>End Date</td>
            <th class='pta'>Total Bounty</td></tr>
    {foreach from=$brows item=brow}
        <tr>
            <td class='pta br bt'>{$brow[0]->pilot}</td>
            <td class='pta br bt c'>{$brow[5]}</td>
            <td class='pta br bt c'>{$brow[4]}</td>
            <td class='pta br bt'>{$brow[2]|ymd}</td>
            <td class='pta br bt'>{$brow[3]|ymd}</td>
            <td class='pta bt r'>{$brow[1]|commify} ISK</td>
        </tr>
    {/foreach}
    </table>

{else}

{if !$error}
    <p><span class='message'>Sorry, your search for {$search_term} didn't return any data.  Please try broadening the scope.</span></p>
{/if}

{/if}

</body>

</html>
