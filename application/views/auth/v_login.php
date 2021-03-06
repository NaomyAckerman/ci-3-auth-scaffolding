<h1 class="text-4xl font-thin text-center">Log-in</h1><br>
<?php $this->load->view('partials/v_message'); ?>
<form method="post" action="" class="space-y-3 mb-8 mt-5">
	<input type="text" name="<?= $this->config->item('default_login'); ?>" class="w-full rounded focus:ring-2 focus:ring-blue-500" value="<?= set_value($this->config->item('default_login')); ?>" placeholder="<?= ucfirst($this->config->item('default_login')); ?>">
	<?= (form_error($this->config->item('default_login'))) ?>
	<input type="password" name="password" class="w-full rounded focus:ring-2 focus:ring-blue-500" placeholder="Password">
	<?= (form_error('password')) ?>
	<button type="submit" class="w-full py-2 px-4 font-bold rounded text-white bg-blue-600 focus:outline-none hover:bg-blue-700 focus:ring-blue-500 focus:ring-2 focus:ring-offset-1">Login</button>
</form>
<div class="text-center mt-3 font-bold text-sm">
	<a href="<?= base_url('register'); ?>" class="text-blue-500 hover:text-blue-800 focus:outline-none">Register</a> • <a href="#" class="text-blue-500 hover:text-blue-800 focus:outline-none">Forgot Password</a>
</div>