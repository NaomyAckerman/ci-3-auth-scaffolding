<h1 class="text-4xl font-thin text-center">Sign-up</h1><br>
<?php $this->load->view('partials/v_message'); ?>
<form method="post" action="" class="space-y-3 mb-8 mt-5">
	<!-- <input type="hidden" name="role_id" value="1"> -->
	<input type="text" name="name" class="w-full rounded focus:ring-2 focus:ring-blue-500" value="<?= set_value('name'); ?>" placeholder="Username">
	<?= form_error('name'); ?>
	<input type="text" name="email" class="w-full rounded focus:ring-2 focus:ring-blue-500" value="<?= set_value('email'); ?>" placeholder="Email">
	<?= form_error('email'); ?>
	<input type="password" name="password" class="w-full rounded focus:ring-2 focus:ring-blue-500" placeholder="Password">
	<?= form_error('password'); ?>
	<input type="password" name="password_conf" class="w-full rounded focus:ring-2 focus:ring-blue-500" placeholder="Confirm Password">
	<?= form_error('password_conf'); ?>
	<button type="submit" class="w-full py-2 px-4 font-bold rounded text-white bg-blue-600 focus:outline-none hover:bg-blue-700 focus:ring-blue-500 focus:ring-2 focus:ring-offset-1">Register</button>
</form>
<div class="text-center mt-3 font-bold text-sm">
	<a href="<?= base_url('login') ?>" class="text-blue-500 hover:text-blue-800 focus:outline-none">Login</a> • <a href="#" class="text-blue-500 hover:text-blue-800 focus:outline-none">Forgot Password</a>
</div>