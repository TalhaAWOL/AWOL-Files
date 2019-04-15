<?php

	session_start();
	
	$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "siteusers-3637bdb6", "hcldbp17mw", "siteusers-3637bdb6");	
		if(mysqli_connect_error()) {
			die("there was an error");
		}
	
	if(isset($_SESSION["email"]) && isset($_SESSION["password"])){
		$query = "SELECT `id` FROM `secret` WHERE email = '".mysqli_real_escape_string($link, $_SESSION["email"])."' AND password = '".mysqli_real_escape_string($link, $_SESSION["password"])."'";
		if(!mysqli_query($link, $query)){
			$_SESSION["email"] = "";
			$_SESSION["password"] = "";
			header("location: index.php");
		}
	}else{
		header("location: index.php");
	}
	
	$diaryvalue = "";
	$check = "SELECT * FROM `secret` WHERE email = '".$_SESSION["email"]."'";
	if($result = mysqli_query($link, $check)){
		$row = mysqli_fetch_array($result);
		$diaryvalue = $row["diary"];
	}else{
		header("location: index.php?error=1");
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
		
		textarea{
			resize:none;
		}
		
		@media (min-width: 1200px) {
			.container{
				max-width: 1650px;
			}
		}
			
	</style>
	
	</head>
	
	<body>
	 
		<nav id="topbar" class="navbar navbar-expand-lg navbar-light bg-light">

			<a class="navbar-brand text-black" href="">Secret Diary</a>
			
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="btn btn-outline-success my-2 my-sm-0" type="submit" href="index.php?logout=1">Log Out</a>
				</li>
			</ul>
			
		</nav>
		
		<div class="mt-3"></div>
		
		<div class="container">
			
			<form method="POST">
				<textarea id="diary" name="diary" class="form-control"><? echo $diaryvalue; ?></textarea>
			</form>
		
		</div>
		
		<script type="text/javascript">
		
			$("textarea").height($(window).height() - $("#topbar").height() - 85)
			
			$("textarea").on("change paste keyup", function() {
				$.ajax({
					type: "POST",
					url: "updateData.php",
					dataType:"text",
					data: {content: $("#diary").val()}
				}).done(function(msg) {
					
				})
			})

		</script>
		
	</body>
	
</html>