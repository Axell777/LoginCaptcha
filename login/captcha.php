<?php
session_start();

// Texto aleatorio de 5 caracteres (sin confusos como O o 0)
$captcha_text = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION['captcha'] = $captcha_text;

// Crear imagen (ancho x alto)
$width = 150;
$height = 50;
$image = imagecreatetruecolor($width, $height);

// Colores
$bg_color = imagecolorallocate($image, 240, 240, 240);
$text_color = imagecolorallocate($image, 50, 50, 50);
$noise_color = imagecolorallocate($image, 100, 100, 100);

// Fondo
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Añadir líneas de ruido
for ($i = 0; $i < 6; $i++) {
    imageline($image, 0, rand() % $height, $width, rand() % $height, $noise_color);
}

// Añadir puntos de ruido
for ($i = 0; $i < 100; $i++) {
    imagesetpixel($image, rand() % $width, rand() % $height, $noise_color);
}

// Añadir texto
$font_size = 20;
$angle = rand(-10, 10);
$font_file = __DIR__ . '/arial.ttf'; // puedes poner cualquier fuente TTF en tu carpeta
if (!file_exists($font_file)) {
    // Si no hay fuente TTF, usar la fuente por defecto
    imagestring($image, 5, 40, 15, $captcha_text, $text_color);
} else {
    $textbox = imagettfbbox($font_size, $angle, $font_file, $captcha_text);
    $x = ($width - ($textbox[2] - $textbox[0])) / 2;
    $y = ($height - ($textbox[5] - $textbox[1])) / 2;
    $y += $font_size;
    imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font_file, $captcha_text);
}

// Encabezado y salida
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
