<?php
// Valida credenciales comparando la contraseña con el hash guardado en users.json
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
header('Location: index.php');
exit;
}


$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';


$usersFile = __DIR__ . '/users.json';
$users = [];
if (file_exists($usersFile)) {
$json = file_get_contents($usersFile);
$users = json_decode($json, true) ?: [];
}


$found = null;
foreach ($users as $u) {
if (strcasecmp($u['username'], $username) === 0) {
$found = $u;
break;
}
}


if (!$found) {
echo "Usuario no encontrado. <a href=\"index.php\">Volver</a>";
exit;
}


if (password_verify($password, $found['hash'])) {
// Login exitoso — mostramos el hash almacenado (como pediste)
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bienvenido</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<h2>Acceso permitido</h2>
<p class="small">Bienvenido, <strong><?php echo htmlspecialchars($found['username']); ?></strong></p>
<p class="small">Hash almacenado:</p>
<pre style="word-break:break-all;background:#f6f8fa;padding:12px;border-radius:8px;border:1px solid #eee;"><?php echo htmlspecialchars($found['hash']); ?></pre>


<a class="link" href="index.php">Cerrar sesión (volver)</a>
</div>
</body>
</html>
<?php
exit;
} else {
echo "Contraseña incorrecta. <a href=\"index.php\">Volver</a>";
exit;
}


?>