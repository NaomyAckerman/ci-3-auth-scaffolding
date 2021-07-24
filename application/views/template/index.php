<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title><?= $title; ?></title>
	<link rel="shortcut icon" href="<?= base_url(); ?>public/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?= base_url(); ?>/public/styles/tailwind.css">
</head>

<body>
	<div id="app" class="m-10">
		<div id="card" class="rounded-md overflow-hidden shadow-xl">
			<div id="card-header" class="px-5 py-4 border-b grid grid-flow-col grid-cols-auto justify-between items-center">
				<h1 class="text-gray-700 text-2xl"><?= $header; ?></h1>
				<div id="card-subheader" class="w-full flex space-x-4">
					<?php if ($this->auth->logged_in()) : ?>
						<a href="<?= base_url('logout'); ?>">Logout</a>
					<?php else : ?>
						<a href="<?= base_url('login'); ?>" class="btn bg-blue-600 text-white focus:ring-blue-600 hover:bg-blue-700">Login</a>
						<a href="<?= base_url('register'); ?>" class="btn bg-blue-600 text-white focus:ring-blue-600 hover:bg-blue-700">Register</a>
					<?php endif; ?>
				</div>
			</div>
			<div id="card-body" class="px-5 py-4 space-y-2">
				<?= $contents; ?>
			</div>
			<div id="card-footer" class="px-5 py-4 text-sm border-t text-right">
				Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
			</div>
		</div>
	</div>

</body>

</html>