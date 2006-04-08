<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="en">

<head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" type="text/css" href="{$SITEROOT}/css/basic.css" />
    <script type="text/javascript" src="{$SITEROOT}/js/fortissimo.js"></script>
    {$extrahead}
</head>

<body>
<a name="top"></a><center>

<!-- logo -->
<table width="945" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#53617C">
    <tr><td align="center"><a href="{$SITEROOT}/"><img src="{$SITEROOT}/img/xtreem/header/chHead.jpg" width="945" height="145" border="0"></a></td></tr>
</table>

<!-- open content container -->
<div align="center">
    <div class="page" style="width:945px; text-align:left">
        <div style="padding:0px 0px 0px 0px">
 
<!-- begin navigation -->
<table width="945" border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td><img src="{$SITEROOT}/img/xtreem/navbar/topleft1.gif" width="28" height="18"></td>
    <td background="{$SITEROOT}/img/xtreem/navbar/topbackground2.gif" width="100%"></td>
    <td><img src="{$SITEROOT}/img/xtreem/navbar/topright2.gif" width="24" height="18"></td>
  </tr>
</table>

{include file="menu.tpl"}

<table width="945" border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td><img src="{$SITEROOT}/img/xtreem/navbar/bottomleft.gif" width="28" height="18"></td>
    <td background="{$SITEROOT}/img/xtreem/navbar/bottombackground.gif" width="100%"></td>
    <td><img src="{$SITEROOT}/img/xtreem/navbar/bottomright.gif" width="24" height="18"></td>
  </tr>
</table>
<!-- end navigation -->

<table align="center" class="page" cellspacing="0" cellpadding="0" width="100%">
<tr valign="top">

<!-- left bar -->
<td width="175">

<!-- user information -->
{left_box_start title="User Information" name="userinfo"}
{if $remote}
    {left_box_link text="<strong>Submit Kill</strong>" url="/submit.php"}
    {if $remote->director()}
        {left_box_link text="Admin Users" url="/admin_users.php"}
        {left_box_link text="Admin Corps" url="/admin_corps.php"}
    {/if}
    {if $remote->manager()}
        {left_box_link text="Admin Links" url="/admin_links.php"}
        {left_box_link text="Ship Bounty Values" url="/bounty_values.php"}
        {left_box_link text="Ship ISK Values" url="/kill_values.php"}
    {/if}
    {if $remote->admin()}
        {left_box_link text="Reprocess Mails" url="/reprocess.php"}
    {/if}
    {left_box_link text="View Standings" url="/standings.php"}
    {left_box_link text="Log out" url="/logout.php"}
{else}
    {left_box_link text="View Standings" url="/standings.php"}
    {left_box_link text="Log in" url="/login.php"}
{/if}
{left_box_end}

<!-- stats information -->
{left_box_start title="Recent Activity" name="recentinfo"}
{left_box_link text="Kill Search" url="/search.php"}
{left_box_link text="Last 50 Kills" url="/?show=kill"}
{left_box_link text="Last 50 Losses" url="/?show=loss"}
{if $remote}
    {left_box_link text="Last 50 Murders" url="/?show=murder"}
{/if}
{left_box_end}

<!-- stats information -->
{left_box_start title="Top Pilots" name="statsinfo"}
{left_box_link text="By Destroyed Value" url="/rankings.php?by=iskdestroyed&which=pilot"}
{left_box_link text="By Bounty Taken" url="/rankings.php?by=bountypoints&which=pilot"}
{left_box_link text="By Final Blows" url="/rankings.php?by=finalblows&which=pilot"}
{left_box_link text="By Solo Kills" url="/rankings.php?by=solokills&which=pilot"}
{left_box_link text="By Participations" url="/rankings.php?by=killgive&which=pilot"}
{left_box_end}

<!-- more interesting stats information -->
{left_box_start title="Top Scores" name="targetsinfo"}
{left_box_link text="Kills by Corp" url="/rankings.php?by=finalblows&which=corp"}
{left_box_link text="Kills by Pilot" url="/rankings.php?by=finalblows&which=pilot"}
{left_box_link text="Losses by Corp" url="/rankings.php?by=lossrecv&which=corp"}
{left_box_link text="Losses by Pilot" url="/rankings.php?by=lossrecv&which=pilot"}
{left_box_end}

<!-- other stats -->
{left_box_start title="Other Stats" name="otherinfo"}
{left_box_link text="Best Systems" url="/rankings.php?by=finalblows&which=system"}
{left_box_link text="Worst Systems" url="/rankings.php?by=lossrecv&which=system"}
{left_box_link text="Winning Ships" url="/rankings.php?by=finalblows&which=ship"}
{left_box_link text="Losing Ships" url="/rankings.php?by=lossrecv&which=ship"}
{left_box_link text="Winning Weapons" url="/rankings.php?by=finalblows&which=weapon"}
{left_box_link text="Custom Rankings" url="/rankings.php?custom=1"}
{left_box_end}
    
</td>

<!-- Spacer Cell -->
<td width="15"><img alt="" src="http://www.celestial-horizon.net/forums/clear.gif" width="15" /></td>
<!-- / Spacer Cell -->

<td valign="top">

<!-- content -->
<table align="center" border="0" cellpadding="6" cellspacing="1" class="tborder" width="100%">

<thead>
 <tr>
  <td class="tcatbl">
   <span class="smallfont"><strong>{$title}</strong></span>
  </td>
 </tr>
</thead>

<tbody id="collapseobj_cmps_pagesmenu" style=";text-align:left">

<tr><td class="alt1"><div class="smallfont">
