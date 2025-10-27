<?php
// Recibe los datos del formulario de registro, genera hash y lo guarda en users.json
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
header('Location: register.php');
exit;
}


$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';


if ($username === '' || $password === '') {
echo "Faltan datos. <a href=\"register.php\">Volver</a>";
exit;
}


$usersFile = __DIR__ . '/users.json';
// Leer usuarios existentes
$users = [];
if (file_exists($usersFile)) {
$json = file_get_contents($usersFile);
$users = json_decode($json, true) ?: [];
}


// Verificar si ya existe el usuario
foreach ($users as $u) {
if (strcasecmp($u['username'], $username) === 0) {
echo "El usuario ya existe. <a href=\"register.php\">Volver</a>";
exit;
}
}


// Crear hash y guardar
$hash = password_hash($password, PASSWORD_DEFAULT);
$users[] = ['username' => $username, 'hash' => $hash, 'created_at' => date('c')];
// Guardar atomically con bloqueo
file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);


// Mostrar al usuario el hash generado (útil para tu propósito)
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Registro exitoso</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<h2>Registro exitoso</h2>
<p class="small">Usuario: <strong><?php echo htmlspecialchars($username); ?></strong></p>
<p class="small">Hash guardado:</p>
<pre style="word-break:break-all;background:#f6f8fa;padding:12px;border-radius:8px;border:1px solid #eee;"><?php echo htmlspecialchars($hash); ?></pre>





<a class="link" href="index.php">Ir a iniciar sesión</a>
</div>
</body>
</html>