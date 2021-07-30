<h1>Forgot</h1>
<?php $this->load->view('partials/v_message'); ?>
<form action="" method="post">
    <input type="text" name="email" placeholder="ackermannaomy@gmail.com">
    <?= (form_error('email')) ?>
    <button type="submit">Send</button>
</form>