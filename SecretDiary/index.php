<?php
	
	session_start();
		
	if(array_key_exists("logout", $_GET)){
		session_unset();
		setcookie ("email","", time() - 60*60);
		setcookie ("password","", time() - 60*60);
		$_COOKIE["email"] = "";
		$_COOKIE["password"] = "";
	}
	
	if(array_key_exists("error", $_GET)){
		$error .= "There was an unexpected error. Try again later.";
	}
	
	if(isset($_COOKIE["email"]) && isset($_COOKIE["password"])){
		$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");	
		if(mysqli_connect_error()) {
			die("there was an error");
		}
		$query = "SELECT `id` FROM `siteusers` WHERE email = '".mysqli_real_escape_string($link, $_COOKIE["email"])."' AND password = '".mysqli_real_escape_string($link, $_COOKIE["password"])."'";
		if(mysqli_query($link, $query)){
			header("location: session.php");
		}
	}elseif(isset($_SESSION["email"]) && isset($_SESSION["password"])){
		$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");	
		if(mysqli_connect_error()) {
			die("there was an error");
		}
		$query = "SELECT `id` FROM `siteusers` WHERE email = '".mysqli_real_escape_string($link, $_SESSION["email"])."' AND password = '".mysqli_real_escape_string($link, $_SESSION["password"])."'";
		if(mysqli_query($link, $query)){
			header("location: session.php");
		}
	}
	
	$error = "";
	if($_POST){
		if(isset($_POST["signUpEmail"])){
			$email = $_POST["signUpEmail"];
			$password =$_POST["signUpPassword"];
			if(!$email){
			$error .= "Please fill out your sign up email! <br>";
			}else{
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$nothing = "";
				} else {
					$error .= "The sign up email address is not valid";
				}
			}
			if(!$password){
				$error .= "Please fill out your sign up password!";
			}else{
				$password = password_hash($_POST["signUpPassword"], PASSWORD_DEFAULT);
			}

			if($error == ""){
				$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");
				
				if(mysqli_connect_error()) {
					die("there was an error");
				} 
				
				$query = "SELECT `id` FROM `secret` WHERE email = '".mysqli_real_escape_string($link, $email)."'";
				$result = mysqli_query($link, $query);
				
				if(mysqli_num_rows($result) > 0){
					$error .= "<div class='alert alert-danger' role='alert'>An account with that email already exists</div>";
				}else{
					$query = "INSERT INTO `secret` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $email)."', '".mysqli_real_escape_string($link, $password)."')";
					if(mysqli_query($link, $query)){
						if(!empty($_POST["remember"])) {
							setcookie ("email",$_POST["signUpEmail"],time()+ (10 * 365 * 24 * 60 * 60));
							setcookie ("password",$_POST["signUpPassword"],time()+ (10 * 365 * 24 * 60 * 60));
						} else {
							if(isset($_COOKIE["email"])) {
								setcookie ("email","");
							}
							if(isset($_COOKIE["password"])) {
								setcookie ("password","");
							}
						}
						$_SESSION["email"] = $_POST["signUpEmail"];
						$_SESSION["password"] = $_POST["signUpPassword"];
						header("location: session.php");
					}else {
						echo "<div class='alert alert-danger' role='alert'>Try again later</div>";
					}
				}
			}else{
				$error = "<div class='alert alert-danger' role='alert'>" .$error. "</div>";
			}
		}
			
		if(isset($_POST["loginEmail"])){
			$email = $_POST["loginEmail"];
			$password = $_POST["loginPassword"];
			if(!$email){
			$error .= "Please fill out your email! <br>";
			}else{
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				} else {
					$error .= "The email address is not valid";
				}
			}
			if(!$password){
				$error .= "Please fill out your password!";
			}
			if($error == ""){
				$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");
				if(mysqli_connect_error()) {
					die("there was an error");
				} 
				if($result = mysqli_query($link, "SELECT * FROM `secret` WHERE email = '".mysqli_real_escape_string($link, $_POST["loginEmail"])."'")){
					$row = mysqli_fetch_array($result);
					if(mysqli_num_rows($result) > 0){
						if(password_verify($_POST["loginPassword"], $row["password"])){
							if(!empty($_POST["remember"])) {
								setcookie ("email",$_POST["loginEmail"],time()+ (10 * 365 * 24 * 60 * 60));
								setcookie ("password",$_POST["loginPassword"],time()+ (10 * 365 * 24 * 60 * 60));
							} else {
								if(isset($_COOKIE["email"])) {
									setcookie ("email","");
								}
								if(isset($_COOKIE["password"])) {
									setcookie ("password","");
								}
							}
							$_SESSION["email"] = $_POST["loginEmail"];
							$_SESSION["password"] = $_POST["loginPassword"];
							header("location: session.php");
						}else{
							$error .= "<div class='alert alert-danger' role='alert'>The password is incorrect.</div>";
							}
					}else{
						$error .= "<div class='alert alert-danger' role='alert'>That email is not registered. Please sign up.</div>";
					}
				}else{
					$error .= "<div class='alert alert-danger' role='alert'>Login Failed. Try again later.</div>";
				}
			}else{
				$error = "<div class='alert alert-danger' role='alert'>" .$error. "</div>";
			}
		}
	}
		
?>

<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="pragma" content="no-cache" />
		<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		
		<title>AWOL - Home</title>
		
	<style type="text/css">
	
		body{
			background-image: url(secretbg.jpg);
			background-position: 0% 25%;
			background-size: cover;
			background-repeat: no-repeat;
		}
		
		.jumbotron{
			margin-top:300px;
			background-color: transparent;
		}
		
		.textCenter{
			text-align:center;
		}
		
		.center{
			margin: auto
		}
		
		.container{
			width:500px;
		}
	
	</style>
	
	</head>
	
	<body>
	 
		<form method="POST">
		
			<div class="container mt-3">
				<div class="jumbotron">
					<h1 class="display-4 text-white textCenter">Secret Diary</h1>
					<p class="lead text-white textCenter">Store your thoughts permanently and securely.</p>
					<div><? echo $error; ?></div>
					<div class="form-group">
						<input class="form-control" id="emailLogArea" type="email" name="loginEmail">
					</div>	
					<div class="form-group">
						<input class="form-control" id="emailPassArea" type="password" name="loginPassword">
					</div>
					<div class="form-group textCenter form-check">
						<input class="form-check-input" type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["email"])) { ?> checked <?php } ?> />
						<label for="remember" class="text-white">Remeber Me</label>
					</div>
					<div class="textCenter form-group">
						<input id="logbtn" class="btn btn-success" type="submit" value="Log In">
					</div>
					<div class="textCenter form-group">	
						<a class="text-primary" id="switchbtn"><strong>Signup</strong></a>
					</div>
				</div>
			</div>
		
		</form>
		
		<script type="text/javascript">
		
			$("#switchbtn").click(function() {
				if($("#emailLogArea").attr("name") == "loginEmail"){
					$("#emailLogArea").attr("name", "signUpEmail")
					$("#emailPassArea").attr("name", "signUpPassword")
					$("#switchbtn").html("<strong>Login</strong>")
					$("#logbtn").attr("value", "Sign Up")
				}else{
					$("#emailLogArea").attr("name", "loginEmail")
					$("#emailPassArea").attr("name", "loginPassword")
					$("#switchbtn").html("<strong>Signup</strong>")
					$("#logbtn").attr("value", "Log In")
				}
			})

		</script>
		
	</body>
	
</html>