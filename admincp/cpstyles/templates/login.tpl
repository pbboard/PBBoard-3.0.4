<div class="login">
<form action="index.php?page=login&amp;login=1" method="post">
    <div class="login-box">
        <div class="login-logo">
           <img src="{$admincpdir_cssprefs}/Power_Bulletin_Board.png">
        </div>

        <div class="login-title">
            <h1>{$lang['Login']}</h1>
        </div>

        <div class="login-form">
            <div class="control-group">
            <input type="text" name="username" class="login-field" value="" placeholder="{$lang['username']}" id="login-name" />
            </div>

            <div class="control-group">
            <input type="password" name="password" class="login-field" value="" placeholder="{$lang['password']}" id="login-pass" />

            </div>

            <input class="btn btn-primary btn-large btn-block" type="submit" value="{$lang['Login']}" name="submit" />
            <p class="login-link"><!--copyright--></p>
        </div>
    </div>
</form>
</div>