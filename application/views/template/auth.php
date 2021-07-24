<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="shortcut icon" href="<?= base_url(); ?>public/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?= base_url(); ?>public/styles/tailwind.css">
</head>

<body>
	<div id="app" class="flex justify-center items-center h-screen bg-gray-100">
		<div id="card" class="bg-white p-8 w-4/12 overflow-hidden rounded-md shadow-lg">
			<?= $contents; ?>
		</div>
	</div>
</body>

</html>