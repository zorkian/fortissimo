{if $message}
    <span class='message'>{$message}</span>
{/if}

{if $error}
    <span class='error'>{$error}</span>
{/if}

<p>Here are the users that have set a password.  You can reset, scramble, or do other things
to their passwords.  Additionally you can set or unset the administrator flag on accounts.</p>

<p>To be clear, if you type a password in a box, it will <strong>overwrite</strong> that user's
existing password with your new chosen password.</p>

<p><strong>Admin</strong> - full control over the board and access rights.  Can perform all actions.</p>

<p><strong>Manager (Mgr)</strong> - some control over the board.  Can set CEO/Director status, set war mode,
and perform actions like that.</p>

<p><strong>CEO</strong> - can control a corporation.  Includes ability to set directors for a corp, reset
password for corp members, set war mode for that corp, etc etc.  Basically everything a Manager can do but
only for the corporation that this person is a member of.</p>

<p><strong>Director (Dir)</strong> - just like a CEO, but can't set roles within the corporation.  Can
process it just like the CEO, however.</p>

<table style='border: solid 1px black; width: 98%;' cellspacing='0' cellpadding='0'>

<tr><th colspan='7' class='dateheader'>

<form name='faid' id='faid' method='get' action='{$SITEROOT}/admin_users.php'>
<select name='faid' onChange='submitForm("faid");'>
<option value='0' {if $faid == 0}selected{/if}>All Known Pilots</option>
<option value='-2'>-------------------------</option>
{foreach from=$alliances item=a}
<option value='{$a->corpid}' {if $faid == $a->corpid}selected{/if}>{$a->name}</option>
{/foreach}
</select>
</form>
<form method='post' action='{$SITEROOT}/admin_users_do.php'>

</th></tr>
<tr><th colspan='7' class='headers c' style='border-bottom: none; margin: 10px 0px 10px 0px;'>
{if $letters}
{foreach from=$letters item=letter}
    {if $filter == strtolower($letter)}
        [{$letter}]
    {else}
        <a href='{$SITEROOT}/admin_users.php?filter={$letter}&faid={$faid}'>{$letter}</a>
    {/if}
{/foreach}
{else}
[All]
{/if}
</th></tr>

<tr><th class='headers'>Pilot</th>
    <th class='headers'>Corporation</th>
    <th class='headers'>Set Password</th>
{if $remote->admin()}
    <th class='headers'>Admin?</th>
{/if}
{if $remote->admin()}
    <th class='headers'>Mgr?</th>
{/if}
{if $remote->manager()}
    <th class='headers'>CEO?</th>
{/if}
{if $remote->ceo()}
    <th class='headers'>Dir?</th>
{/if}
    </tr>

{foreach from=$users item=user}
{if ! strlen($filter) || (strtolower(substr($user->name, 0, 1)) == $filter)}
{if $remote->admin() || $remote->manager() || $remote->ceo($user->corpid) || $remote->director($user->corpid)}
    {cycle values="talt1,talt2" assign="rowborder"}
    <tr>
        <td class="cell {$rowborder}">{$user->name}
            <input type='hidden' name='ok_{$user->pilotid}' value='1' />
            </td>
        <td class="cell {$rowborder}">{$user->corp}</td>
        <td class="cell {$rowborder}">{if $user->password}<input type='text' name='pw_{$user->pilotid}' />{else}never logged in{/if}</td>
{if $remote->admin()}
        <td class="cell {$rowborder}"><select name='admin_{$user->pilotid}'>
                                        <option value="1" {if $user->roles & 1}selected{/if}>YES</option>
                                        <option value="0" {if ! ($user->roles & 1)}selected{/if}>-</option>
                                      </select></td>
{/if}
{if $remote->admin()}
        <td class="cell {$rowborder}"><select name='proc_{$user->pilotid}'>
                                        <option value="1" {if $user->roles & 8}selected{/if}>YES</option>
                                        <option value="0" {if ! ($user->roles & 8)}selected{/if}>-</option>
                                      </select></td>
{/if}
{if $remote->manager()}
        <td class="cell {$rowborder}"><select name='ceo_{$user->pilotid}'>
                                        <option value="1" {if $user->roles & 2}selected{/if}>YES</option>
                                        <option value="0" {if ! ($user->roles & 2)}selected{/if}>-</option>
                                      </select></td>
{/if}
{if $remote->ceo()}
        <td class="cell {$rowborder}"><select name='dir_{$user->pilotid}'>
                                        <option value="1" {if $user->roles & 4}selected{/if}>YES</option>
                                        <option value="0" {if ! ($user->roles & 4)}selected{/if}>-</option>
                                      </select></td>
{/if}
    </tr>
{/if}
{/if}
{/foreach}

</table>

<input type='submit' value='Save Changes' />
</form>
