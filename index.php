<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Guess the colour</title>
		<style media="screen">
			* {
				font-family: Helvetica;
			}
		</style>
	</head>
	<body>
		<form action="guess.php" method="get">
			<section id="input_type_select">
				<strong>Which input type would you like to use?</strong><br>
				<input type="radio" name="input_type" value="hex" checked>Hex<br>
				<input type="radio" name="input_type" value="rgb">RGB<br>
				<input type="radio" name="input_type" value="hsv" disabled>HSV<br>
				<input type="radio" name="input_type" value="hsl" disabled>HSL<br>
				<input type="radio" name="input_type" value="cymk" disabled>CYMK<br>
			</section>
			Enable selection preview?: <input type="checkbox" name="preview"><br>
			<button type="submit">Play the game</button>
		</form>
	</body>
</html>
