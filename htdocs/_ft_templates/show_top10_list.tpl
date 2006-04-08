<table style='width: 100%; border: solid 1px black;' cellpadding='0' cellspacing='0'>
<tr><td class='dateheader'>{$t10which}</td></tr>

{counter name=rows start=0 print=false}

{foreach from=$t10rows item=row}
    {cycle print=false assign="class" values="talt1,talt2"}
    <tr><td class='{$class} bt pta'>
        {counter name=rows}. {$row[0]} ({$row[1]})
    </td></tr>
{/foreach}

</table>
