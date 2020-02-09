<?php
	if (!isset($_POST['input_type']))
		die("Error: missing input type");
	$input_type = $_POST['input_type'];

	if (!in_array($input_type, ["hex", "rgb", "hsl", "hsv", "cymk"]))
		die("Error: invalid input type");

	switch ($input_type) {
		case "hex":
			if (!isset($_POST['hex']))
				die("Error: missing hex data");
			$hex = $_POST['hex'];
			// TODO
			echo "0";
			
			break;

		default:
			// code...
			break;
	}
?>
