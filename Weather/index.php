<?php
	
	$weather = "";
	$city = "";
	
	if($_POST) {
		
		$city = $_POST["city"];
		
		$citychange = preg_replace('/\s+/', '-', $city);
		
		$url = "https://www.weather-forecast.com/locations/".$citychange."/forecasts/latest";
		
		$file_headers = @get_headers("https://www.weather-forecast.com/locations/".$citychange."/forecasts/latest");
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			if($city){
				$weather = "<div class='alert alert-danger' role='alert'><strong>".$city." is not a valid city name.</strong> Please enter a valid city.</div>";
			}else{
				$weather = "<div class='alert alert-danger' role='alert'><strong> That is not a valid city name.</strong> Please enter a valid city.</div>";
			}
		}else {
			$html = file_get_contents($url);
			libxml_use_internal_errors( true);
			$doc = new DOMDocument;
			$doc->loadHTML( $html);
			$xpath = new DOMXpath( $doc);

			// A name attribute on a <div>???
			$node = $xpath->query( '//p[@class="b-forecast__table-description-content"]')->item( 0);
				
			$weather = "<div class='alert alert-success' role='alert'>".$node->textContent."</div>";	
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
		
		textarea{
			resize:none;
		}
		
		.center{
			margin: auto
		}
		
		.textCenter{
			text-align:center;
		}
		
		body{
			background-image: url(bgimg.jpg);
			background-position: 0% 25%;
			background-size: cover;
			background-repeat: no-repeat;
		}
		
		.jumbotron{
			background-color: transparent;
			padding-top:300px;
		}
		
		</style>
	
	</head>
	
	<body>
		<div class="container">
			<div id="home1" class="jumbotron">
				<h1 class="textCenter text-black"><strong>Whats the Weather?</strong></h1>
				<p class="lead textCenter"><strong>Enter the name of a city.</strong></p>
				
				<form method="POST">
					<input name="city" type="text" class="form-control" placeholder="E.g. London, Tokyo" value="<? echo $city; ?>" aria-label="City" aria-describedby="basic-addon1">
					<div class="mt-3 textCenter">
						<div id="alert" class="container"><? echo $weather; ?></div>
						<button type="submit" class="btn btn-primary" href="">Submit</button>
					</div>
				</form>
			</div>
		</div>
		
		<script type="text/javascript">
						
			
		
		</script>
	
	</body>
	
	</html>
		
		