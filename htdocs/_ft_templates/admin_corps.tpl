{if $message}
    <span class='message'>{$message}</span>
{/if}

{if $error}
    <span class='error'>{$error}</span>
{/if}

<p>Corporations that are currently known to the system.  Here you can set standings and whether
a corporation is allowed to use this board or not.  You can also set notes to put next to the
corporation on the standings viewer page.</p>

<p><strong>Allow</strong> - this corporation is allowed to use this killboard to post kills.</p>

<p><strong>War Mode</strong> - if enabled, hides the location of kills and losses made in the
past N hours, where N is configurable by the war mode.</p>


<table style='border: solid 1px black; width: 98%;' cellspacing='0' cellpadding='0'>

<tr><th colspan='7' class='dateheader'>

<form name='faid' id='faid' method='get' action='{$SITEROOT}/admin_corps.php'>
<select name='faid' onChange='return submitForm("faid");'>
<option value='0' {if $faid == 0}selected{/if}>All Known Corporations</option>
<option value='-1' {if $faid == -1}selected{/if}>Corps with No Alliance</option>
<option value='-2'>-------------------------</option>
{foreach from=$alliances item=a}
{if $a->allianceid == $faid}
    {assign var=anote value=$a->note}
{/if}
<option value='{$a->allianceid}' {if $faid == $a->allianceid}selected{/if}>{$a->name}</option>
{/foreach}
</select>
</form>
<form method='post' action='{$SITEROOT}/admin_corps_do.php'>
<input type='hidden' name='faid' value='{$faid}' />


{if $remote->manager()}

<br />
<strong>Set standings for alliance:</strong>
                <select name='all_standings'>
                {foreach from=$standings item=value key=key}
                    <option value="{$value}">{$key}</option>
                {/foreach}
                </select>

{if $faid > 0}
<br />
<strong>Set note for alliance:</strong>
<input type='text' name='alliance_note_{$faid}' value='{$anote}' size='40' />
{/if}

{/if}
</th></tr>


<tr><th colspan='7' class='headers c' style='border-bottom: none; margin: 10px 0px 10px 0px;'>
{if $letters}
{foreach from=$letters item=letter}
    {if $filter == strtolower($letter)}
        [{$letter}]
    {else}
        <a href='/admin_corps.php?filter={$letter}&faid={$faid}'>{$letter}</a>
    {/if}
{/foreach}
{else}
[All]
{/if}
</th></tr>

<tr><th class='headers'>Corporation</th>
    <th class='headers'>Alliance</th>
{if $remote->manager()}
    <th class='headers'>Ticker</th>
    <th class='headers'>Allowed?</th>
    <th class='headers'>Standings</th>
{/if}
{if $remote->director()}
    <th class='headers'>War Mode?</th>
{/if}
    </tr>

{foreach from=$users item=user}
{if ! strlen($filter) || (strtolower(substr($user->name, 0, 1)) == $filter)}
{if $remote->admin() || $remote->manager() || $remote->ceo($user->corpid) || $remote->director($user->corpid)}
    {cycle values="talt1,talt2" assign="rowborder"}
    <tr>
        <td class="cell {$rowborder}">{$user->name}
            <input type='hidden' name='ok_{$user->corpid}' value='1' />
            </td>
        <td class="cell {$rowborder}">{$user->alliance}</td>
{if $remote->manager()}
        <td class="cell {$rowborder}"><input type='text' name='ticker_{$user->corpid}' value='{$user->ticker}' size='5' /></td>
        <td class="cell {$rowborder}"><select name='allow_{$user->corpid}'>
                                        <option value="1" {if $user->allowed}selected{/if}>YES</option>
                                        <option value="0" {if !$user->allowed}selected{/if}>-</option>
                                      </select></td>
        <td class="cell {$rowborder}"><select name='standings_{$user->corpid}'>
                {foreach from=$standings item=value key=key}
                    {if is_null($user->standings)}
                        {assign var="selected" value=""}
                    {else}
                        {if $user->standings == $value}
                            {assign var="selected" value="selected='selected'"}
                        {else}
                            {assign var="selected" value=""}
                        {/if}
                    {/if}
                    <option value="{$value}" {$selected}>{$key}</option>
                {/foreach}
                                      </select></td>
{/if}
{if $remote->director($user->corpid)}
{if $user->allowed}
        <td class="cell {$rowborder}"><select name='war_{$user->corpid}'>
                                        <option value="0" {if $user->warmode == 0}selected{/if}>-</option>
                                        <option value="6" {if $user->warmode == 6}selected{/if}>6 hours</option>
                                        <option value="12" {if $user->warmode == 12}selected{/if}>12 hours</option>
                                        <option value="24" {if $user->warmode == 24}selected{/if}>24 hours</option>
                                        <option value="36" {if $user->warmode == 36}selected{/if}>36 hours</option>
                                        <option value="48" {if $user->warmode == 48}selected{/if}>48 hours</option>
                                      </select></td>
{else}
        <td class="cell {$rowborder}">not allowed</td>
{/if}
{/if}
    </tr>

{* stuff *}
{if $remote->manager()}
<tr>
    <td colspan='2' class="cell {$rowborder}" style='padding-bottom: 15px;'>
        &nbsp;&nbsp;Note:&nbsp;<input type='text' name='note_{$user->corpid}' size='50' value='{$user->note}' />
    </td>
    <td colspan='4' class="cell {$rowborder}">&nbsp;</td>
</tr>
{/if}

{/if}
{/if}
{/foreach}

</table>

<input type='submit' value='Save Changes' />
</form>
