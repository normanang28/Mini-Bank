<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title><?= session()->get('nama_website') ?></title>
    <base href="<?php echo base_url('assets') ?>/">

  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/assets/images/settings_web/'.session()->get('logo_sekolah'))?>">
	<!-- <link rel="stylesheet" href="style.css"> -->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<head>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
	<div class="wrapper">
		<form method="post" action="<?= base_url('home/aksi_login') ?>">
			<h1>Login</h1>
			<div class="input-box">
      	<input type="text" name="username" class="form-control text-capitalize" placeholder="Please enter your Username" required>
      	<i class='bx bxs-user'></i>
			</div>
			<script>
		    function capitalizeFirstLetter(inputElement) {
		        const words = inputElement.value.split(' ');

		        const capitalizedWords = words.map(word => {
		            if (word.length > 0) {
		                return word.charAt(0).toUpperCase() + word.slice(1);
		            } else {
		                return '';
		            }
		        });

		        inputElement.value = capitalizedWords.join(' ');
		    }

		    const usernameInput = document.querySelector('input[name="username"]');
		    usernameInput.addEventListener('input', function () {
		        capitalizeFirstLetter(this);
		    });
		</script>
			<div class="input-box">
				<input type="password" name="password" class="form-control" placeholder="Please enter your Password" required>
				<i class='bx bxs-lock-alt'></i>
			</div>

			    <center><div class="g-recaptcha" data-sitekey="6Le4D6snAAAAAHKAJFOOLbLc17nMTBTF6Ze12hWG"></div></center>
			    <div class="input-box">
               	<button type="submit" class="btn">Login</button>
		</form>
		
	</div>

</body>
</html>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: "Poppins", sans-serif;
}

body {
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	background: url(<?= base_url('/assets/images/settings_web/'.session()->get('login_sekolah'))?>) no-repeat;
	background-size: cover;
	background-position: center;
}

.wrapper {
	width: 420px;
	background: transparent;
	border: 2px solid rgba(225, 225, 225, .2);
	backdrop-filter: blur(30px);
	box-shadow: 0 0 10px rgba(0, 0, 0, .2);
	color: #fff;
	border-radius: 10px;
	padding: 30px 40px;
}

.wrapper h1 {
	font-size: 36px;
	text-align: center;
}

.wrapper .input-box {
	position: relative;
	width: 100%;
	height: 50px;
	margin: 30px 0;
}

.input-box input {
	width: 100%;
	height: 100%;
	background: transparent;
	border: none;
	outline: none;
	border: 2px solid rgba(255, 255, 255, .2);
	border-radius: 40px;
	font-size: 16px;
	color: #fff;
	padding: 20px 45px 20px 20px;
}

.input-box input::placeholder {
	color: #fff;
}

.input-box i {
	position: absolute;
	right: 20px;
	top: 50%;
	transform: translateY(-50%);
	font-size: 20px;
}

.wrapper .remember-forgot {
	display: flex;
	justify-content: space-between;
	font-size: 14.5px;
	margin: -15px 0 15px;
}

.remember-forgot label input {
	accent-color: #fff;
	margin-left: 3px;
}

.remember-forgot a {
	color: #fff;
	text-decoration: none;
}

.remember-forgot a:hover {
	text-decoration: underline;
}

.wrapper .btn {
	width: 100%;
	height: 45px;
	background: #fff;
	border: none;
	outline: none;
	border-radius: 40px;
	box-shadow: 0 0 10px rgba(0, 0, 0, .1);
	cursor: pointer;
	font-size: 16px;
	color: #333;
	font-weight: 600;
}
</style>