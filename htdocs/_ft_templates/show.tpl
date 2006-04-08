{if $message}
    <p>{$message}</p>
{/if}

{assign var=title value="Last 25 Kills"}
{include file="show_kills_dateless.tpl"}

{assign var=title value="Last 25 Losses"}
{assign var=killids value=$lkillids}
{assign var=kills value=$lkills}
{include file="show_kills_dateless.tpl"}

{assign var=title value="Last 25 Murders"}
{assign var=killids value=$mkillids}
{assign var=kills value=$mkills}
{include file="show_kills_dateless.tpl"}


