<table style='border: solid 1px black;' cellpadding='0' cellspacing='0'>
<tr><th colspan='5' class='dateheader'>{$blueprint->item}</th></tr>
<tr><th class='headers'>&nbsp;</th><th class='headers'>Material</th><th class='headers'>Quantity</th>
    <th class='headers'>Unit Cost</th><th class='headers'>Total Cost</th></tr>
{foreach from=$materials item=row}
    {cycle assign=class values="talt1,talt2"}
    <tr><td class='{$class}' width='32'>{$row[1]->img32()}</td>
        <td class='{$class} vc eptl'>{$row[1]->item} ({$row[1]->item_id})</td>
        <td class='{$class} vc eptl'><strong>{$row[0]}</strong></td>
        <td class='{$class} vc eptl'>{$row[2]|isk}</td>
        <td class='{$class} vc eptl'>{$row[3]|isk}</td>
        </tr>
{/foreach}
<tr>
    <th class='headersnb'>&nbsp;</th>
    <th class='headersnb c' colspan='3'>Grand Total Cost:</th>
    <th class='headersnb'>{$total|isk}</th>
</tr>
</table>
