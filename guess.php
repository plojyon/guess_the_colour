<!DOCTYPE html>
<?php
	if (!isset($_GET["input_type"])) {
		$input_type = "hex";
	}
	else {
		$input_type = $_GET["input_type"];
		if (!in_array($input_type, ["hex", "rgb", "hsl", "hsv", "cymk"])) {
			$input_type = "hex";
		}
	}
	if (isset($_GET['preview'])) $preview = true;
	else $preview = false;
?>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Guess the colour</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="colours.js" charset="utf-8"></script>
		<style>
			#colour_show, #preview {
				height: 5em;
				width: 10em;
				margin-top: 2.5em;
				margin-bottom: 2em;
				border: 5px outset gray;
				display: inline-block;
			}
			input.short {
				width: 2em;
			}
			input.wide {
				width: 4em;
			}
			section {
				margin: 0.5em;
			}
			#input_type_select {
				text-align: left;
				display: inline-block;
			}
			button {
				display: block;
				margin: 1em;
				padding: 0.5em 1em;
			}
			.result p {
				display: inline;
				line-height: 2.5em;
			}
			.result strong {
				margin: 0 0.5em;
			}
			.result .colour_preview {
				height: 2em;
				width: 2.5em;
				display: inline-block;
				position: relative;
				top: 0.5em;
			}
		</style>
	</head>
	<body>
		<a href="index.php">&larr;&nbsp;Back</a>
		<center>
			<form id="myForm" onsubmit="return false;">
				<h1>What colour is this?</h1>
				<div id="colour_show"></div>
				<?php if ($preview) echo '<div id="preview"></div>'; ?>

				</section>
					<?php
						echo '<section class="input_type">';
						if ($input_type == "hex") {
							echo '<label for="hex">#</label>'
								.'<input class="wide" onkeyup="updatePreview()" type="text" name="hex" value="" id="'.$input_type.'">';
						}
						else {
							foreach (str_split($input_type,1) as $c) {
								echo '<label for="'.$c.'">'.strtoupper($c).'</label>'
									.'<input class="short" onkeyup="updatePreview()" type="text" name="'.$c.'" value="" id="'.$c.'"> ';
							}
						}
					?>
				</section>
				<!--<input type="hidden" name="input_type" value="<?php echo $input_type ?>">-->
				<button type="submit" onclick="check();">OK</button>
			</form>
			<div id="results"></div>
			<script>
				function addResult(de, target, yours) {
					target_label = document.createElement("p");
					<?php
						if ($input_type == "hex")
							echo 'target_label.innerText = "(#"+target+") Target :";';
						elseif ($input_type == "rgb") {
							echo 'rgb = hex_to_rgb(target);';
							echo 'target_label.innerText = "("+rgb[0]+","+rgb[1]+","+rgb[2]+") Target :";';
						}
						else
							echo 'target_label.innerText = "Target :";';
					?>

					target_preview = document.createElement("div");
					target_preview.classList.add("colour_preview");
					target_preview.style.backgroundColor = "#"+target;

					delta = document.createElement("strong");
					delta.innerText = Math.round(de);

					yours_preview = document.createElement("div");
					yours_preview.classList.add("colour_preview");
					yours_preview.style.backgroundColor = "#"+yours;

					yours_label = document.createElement("p");
					<?php
						if ($input_type == "hex")
							echo 'yours_label.innerText = ": Yours (#"+yours+")";';
						elseif ($input_type == "rgb") {
							echo 'rgb = hex_to_rgb(yours);';
							echo 'yours_label.innerText = ": Yours ("+rgb[0]+","+rgb[1]+","+rgb[2]+")";';
						}
						else
							echo 'yours_label.innerText = ": Yours";';
					?>


					result = document.createElement("div");
					result.classList.add("result");
					result.appendChild(target_label);
					result.appendChild(target_preview);
					result.appendChild(delta);
					result.appendChild(yours_preview);
					result.appendChild(yours_label);

					document.getElementById("results").appendChild(result);
				}

				window.onload = function() {
					newColour();
				}

				function newColour() {
					colour = Math.random().toString(16).substr(2,6);
					document.getElementById('colour_show').style.backgroundColor = '#'+colour;
				}

				function updatePreview() {
					<?php
						if (!$preview) echo "/* TODO: add preview feature.. */";
						else {
							echo 'currentColour = rgb_to_hex(getCurrentColour());
							';
							echo 'document.getElementById("preview").style.backgroundColor = "#"+currentColour;';
						}
					?>
				}

				function getCurrentColour() {
					<?php
						if ($input_type == "hex") {
							echo 'hex = document.getElementById("hex").value;';
							echo 'rgb = hex_to_rgb(hex);';
						}
						elseif ($input_type == "rgb") {
							echo 'r = document.getElementById("r").value;';
							echo 'g = document.getElementById("g").value;';
							echo 'b = document.getElementById("b").value;';
							echo 'rgb = [r,g,b];';
						}
						elseif ($input_type == "hsl") {
							echo 'h = document.getElementById("h").value;';
							echo 's = document.getElementById("s").value;';
							echo 'l = document.getElementById("l").value;';
							echo 'rgb = hsl_to_rgb([h,s,l]);';
						}
						elseif ($input_type == "hsv") {
							echo 'h = document.getElementById("h").value;';
							echo 's = document.getElementById("s").value;';
							echo 'v = document.getElementById("v").value;';
							echo 'rgb = hsl_to_rgb(hsv_to_hsl([h,s,v]));';
						}
						elseif ($input_type == "cymk") {
							echo 'c = document.getElementById("c").value;';
							echo 'y = document.getElementById("y").value;';
							echo 'm = document.getElementById("m").value;';
							echo 'k = document.getElementById("k").value;';
							echo 'rgb = cymk_to_rgb([c,y,m,k]);';
						}
					?>
					return rgb;
				}

				function check() {
					/*
					$.ajax({
				        url: '/verify.php',
				        type: 'post',
				        data: $('#myForm').serialize(),
				        success: function(data) {
				            alert(data);
				        }
				    });*/
					selected = rgb_to_lab(getCurrentColour());
					target = rgb_to_lab(hex_to_rgb(colour));
					de = deltaE(target, selected);
					addResult(de, colour, rgb_to_hex(rgb));
					newColour();
					return false;
				}

				function setInput(type) {
					inputs = document.getElementsByClassName('input_type')
					for (i of inputs) {
						i.style.display = "none";
					}
					document.getElementById(type).style.display = "block";
				}

			</script>
		</center>
	</body>
</html>
