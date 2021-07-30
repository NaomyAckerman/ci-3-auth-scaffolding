<h1>Reset Password</h1>
<form action="" method="post">
    <input type="password" name="password">
    <?= (form_error('password')) ?>
    <input type="password" name="password_conf">
    <?= (form_error('password_conf')) ?>
    <button type="submit">Reset</button>
</form>