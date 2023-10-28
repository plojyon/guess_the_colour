function hex_to_rgb(hex) {
	// hex string should not contain #
	r = parseInt(hex.substring(0, 2), 16);
	g = parseInt(hex.substring(2, 4), 16);
	b = parseInt(hex.substring(4), 16);
	return [r, g, b];
}

function rgb_to_hex(rgb) {
	r = (rgb[0] % 256).toString(16);
	g = (rgb[1] % 256).toString(16);
	b = (rgb[2] % 256).toString(16);
	if (r.length == 1) r = "0" + r;
	if (g.length == 1) g = "0" + g;
	if (b.length == 1) b = "0" + b;
	return (r + g + b).toUpperCase();
}

function cymk_to_rgb(cymk) {
	c = cymk[0];
	y = cymk[1];
	m = cymk[2];
	k = cymk[3];
	r = 255 * (1 - c / 100) * (1 - k / 100);
	g = 255 * (1 - m / 100) * (1 - k / 100);
	b = 255 * (1 - y / 100) * (1 - k / 100);
	return [r, g, b];
}

function hsv_to_hsl(hsv) {
	h = hsv[0] / 360;
	s = hsv[1] / 100;
	v = hsv[2] / 100;
	// both hsv and hsl values are in [0, 1]
	var l = (2 - s) * v / 2;

	if (l != 0) {
		if (l == 1) {
			s = 0;
		} else if (l < 0.5) {
			s = s * v / (l * 2);
		} else {
			s = s * v / (2 - l * 2);
		}
	}

	return [h * 360, s * 100, l * 100];
}

function hex_to_hsv(hex) {
	rgb = hex_to_rgb(hex);
	return rgb_to_hsv(rgb);
}

function hex_to_hsl(hex) {
	rgb = hex_to_rgb(hex);
	return rgb_to_hsl(rgb);
}

function hex_to_cymk(hex) {
	rgb = hex_to_rgb(hex);
	return rgb_to_cymk(rgb);
}

function rgb_to_hsv(rgb) {
	// thank u copilot
	r = rgb[0] / 255;
	g = rgb[1] / 255;
	b = rgb[2] / 255;

	var max = Math.max(r, g, b);
	var min = Math.min(r, g, b);

	var h, s, v = max;

	var d = max - min;
	s = max == 0 ? 0 : d / max;

	if (max == min) {
		h = 0; // achromatic
	} else {
		switch (max) {
			case r:
				h = (g - b) / d + (g < b ? 6 : 0);
				break;
			case g:
				h = (b - r) / d + 2;
				break;
			case b:
				h = (r - g) / d + 4;
				break;
		}
		h /= 6;
	}

	return [h * 360, s * 100, v * 100];
}

function rgb_to_hsl(rgb) {
	// thank u copilot
	r = rgb[0] / 255;
	g = rgb[1] / 255;
	b = rgb[2] / 255;
	var max = Math.max(r, g, b);
	var min = Math.min(r, g, b);
	var h, s, l = (max + min) / 2;

	if (max == min) {
		h = s = 0; // achromatic
	} else {
		var d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
		switch (max) {
			case r:
				h = (g - b) / d + (g < b ? 6 : 0);
				break;
			case g:
				h = (b - r) / d + 2;
				break;
			case b:
				h = (r - g) / d + 4;
				break;
		}
		h /= 6;
	}

	return [h * 360, s * 100, l * 100];
}

function rgb_to_cymk(rgb) {
	// thank u copilot
	r = rgb[0] / 255;
	g = rgb[1] / 255;
	b = rgb[2] / 255;
	var k = 1 - Math.max(r, g, b);
	var c = (1 - r - k) / (1 - k);
	var m = (1 - g - k) / (1 - k);
	var y = (1 - b - k) / (1 - k);
	return [c * 100, y * 100, m * 100, k * 100];
}

function hsl_to_rgb(hsl) {
	h = hsl[0] / 360;
	s = hsl[1] / 100;
	l = hsl[2] / 100;

	var r, g, b;

	if (s == 0) {
		r = g = b = l; // achromatic
	} else {
		function hue2rgb(p, q, t) {
			if (t < 0) t += 1;
			if (t > 1) t -= 1;
			if (t < 1 / 6) return p + (q - p) * 6 * t;
			if (t < 1 / 2) return q;
			if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
			return p;
		}

		var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		var p = 2 * l - q;

		r = hue2rgb(p, q, h + 1 / 3);
		g = hue2rgb(p, q, h);
		b = hue2rgb(p, q, h - 1 / 3);
	}

	return [r * 255, g * 255, b * 255];
}

// https://github.com/antimatter15/rgb-lab/blob/master/color.js
function rgb_to_lab(rgb) {
	r = rgb[0] / 255;
	g = rgb[1] / 255;
	b = rgb[2] / 255;
	var x, y, z;

	r = (r > 0.04045) ? Math.pow((r + 0.055) / 1.055, 2.4) : r / 12.92;
	g = (g > 0.04045) ? Math.pow((g + 0.055) / 1.055, 2.4) : g / 12.92;
	b = (b > 0.04045) ? Math.pow((b + 0.055) / 1.055, 2.4) : b / 12.92;

	x = (r * 0.4124 + g * 0.3576 + b * 0.1805) / 0.95047;
	y = (r * 0.2126 + g * 0.7152 + b * 0.0722) / 1.00000;
	z = (r * 0.0193 + g * 0.1192 + b * 0.9505) / 1.08883;

	x = (x > 0.008856) ? Math.pow(x, 1 / 3) : (7.787 * x) + 16 / 116;
	y = (y > 0.008856) ? Math.pow(y, 1 / 3) : (7.787 * y) + 16 / 116;
	z = (z > 0.008856) ? Math.pow(z, 1 / 3) : (7.787 * z) + 16 / 116;

	return [(116 * y) - 16, 500 * (x - y), 200 * (y - z)]
}

function deltaE(labA, labB) {
	var deltaL = labA[0] - labB[0];
	var deltaA = labA[1] - labB[1];
	var deltaB = labA[2] - labB[2];
	var c1 = Math.sqrt(labA[1] * labA[1] + labA[2] * labA[2]);
	var c2 = Math.sqrt(labB[1] * labB[1] + labB[2] * labB[2]);
	var deltaC = c1 - c2;
	var deltaH = deltaA * deltaA + deltaB * deltaB - deltaC * deltaC;
	deltaH = deltaH < 0 ? 0 : Math.sqrt(deltaH);
	var sc = 1.0 + 0.045 * c1;
	var sh = 1.0 + 0.015 * c1;
	var deltaLKlsl = deltaL / (1.0);
	var deltaCkcsc = deltaC / (sc);
	var deltaHkhsh = deltaH / (sh);
	var i = deltaLKlsl * deltaLKlsl + deltaCkcsc * deltaCkcsc + deltaHkhsh * deltaHkhsh;
	return i < 0 ? 0 : Math.sqrt(i);
}

function roundAll(colour) {
	for (var i = 0; i < colour.length; i++) {
		colour[i] = Math.round(colour[i]);
	}
	return colour;
}
