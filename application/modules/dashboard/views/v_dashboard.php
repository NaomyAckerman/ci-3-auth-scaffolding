<h1>selamat datang <?= $this->auth->user()->name; ?></h1>
<h2>role : <?= $this->auth->get_role()->role; ?></h2>
