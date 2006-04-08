{if $remote}

    <p>Sorry, you are already logged in.</p>

{else}

    <p>Please fill out the following information to log in.</p>

    <form method='post' action='{$SITEROOT}/login_do.php'>
        <table>
            <tr><td>Character Name:</td>
                <td><input type='text' name='name' value='' /></td></tr>
            <tr><td>Your Password:</td>
                <td><input type='password' name='password' value='' /></td></tr>
            <tr><td></td>
                <td><input type='submit' value='Login' /></td></tr>
        </table>
    </form>

    {if $error}
        <p><span class='error'>{$error}</span></p>
    {/if}
    
    <p><strong>NOTE:</strong>
    If you do not have a password for this site, you will need to visit this page
    in your EVE client's in-game browser in order to setup a password.</p>

{/if}
