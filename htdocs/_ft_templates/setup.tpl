<p>Welcome!</p>

<p>This page is designed to walk you through the setup process for the Fortissimo
killboard.  Hopefully this is a very understandable and easy to follow process, but
if you have any trouble, please contact me on AIM at my account <strong><u>xb95 At Work</u></strong>.</p>

<p><em>Instructions:</em> Please fill out the following fields.  When you are satisfied,
click the submit button to save your changes.</p>

<form method='post' action='setup_do.php'>

<h2>Admin Setup</h2>

<p>Please fill out the account name to be used for the admin account.  It is highly
recommended that you specify your character name exactly as it is shown in the game.  This
will help line things up better!</p>

{if $error1}
    <p><span class='error'>{$error1}</span></p>
{/if}

<table>
    <tr><td>Account Name:</td><td><input type='text' name='admin_name' value='{$admin_name}' /></td></tr>
    <tr><td>Password:</td><td><input type='password' name='admin_pw1' /></td></tr>
    <tr><td>Password:</td><td><input type='password' name='admin_pw2' /></td></tr>
</table>

<h2>Wow, That Was Simple</h2>

<p>That's all you had to do.  As soon as you hit submit, we will go ahead and configure
the rest.  Amazing, huh.</p>

<p><input type='submit' value='Setup Fortissimo Killboard' /></p>

</form>
