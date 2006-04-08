{if $summary}
<div style='text-align: center; font-size: 1.5em; margin-bottom: 8px;'>
    [ <span style='font-weight: bold;'>Weekly Summary</span> ]
</div>
{include file="summary.tpl"}
<br />
{/if}

{if $message}
<div style='text-align: center; font-size: 1.5em; margin-bottom: 8px;'>
    [ <span style='font-weight: bold;'>{$message}</span> ]
</div>
{/if}

{include file="show_kills.tpl"}
