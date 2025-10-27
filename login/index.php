<!-- Página de login: form que envía a process_login.php -->
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Iniciar sesión</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Iniciar sesión</h2>
  <p class="small">Ingresa tu usuario y contraseña</p>

  <form id="loginForm" action="process_login.php" method="post">
    <label class="label" for="username">Usuario</label>
    <input class="input" id="username" name="username" type="text"
           placeholder="Ingresa tu usuario" required>

    <label class="label" for="password">Contraseña</label>
    <input class="input" id="password" name="password" type="password"
           placeholder="Ingresa contraseña" required>
<label class="label" for="captcha">Verificación</label>
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
  <img src="captcha.php" alt="Captcha" id="captchaImg" style="border:1px solid #ccc;border-radius:6px;">
  <button type="button" onclick="refreshCaptcha()" class="button" style="width:auto;padding:6px 10px;">🔄</button>
</div>
<input class="input" id="captcha" name="captcha" type="text" placeholder="Ingresa el texto de la imagen" required>

    <button class="button button-primary" type="submit">Iniciar sesión</button>
  </form>

  <a class="link" href="register.php">¿No tienes cuenta? Registro</a>
</div>

<!-- hashear la contraseña en el cliente antes de enviar -->
<script>
async function hashSHA256(message) {
  const msgBuffer = new TextEncoder().encode(message);
  const hashBuffer = await crypto.subtle.digest("SHA-256", msgBuffer);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  const hashHex = hashArray.map(b => b.toString(16).padStart(2, "0")).join("");
  return hashHex;
}

document.getElementById("loginForm").addEventListener("submit", async function(e) {
  e.preventDefault(); // Evita envío inmediato
  const pwdInput = document.getElementById("password");
  const plainPwd = pwdInput.value;

  if (!plainPwd) {
    this.submit();
    return;
  }

  // Calcular hash SHA-256
  const hashed = await hashSHA256(plainPwd);

  // Reemplazar la contraseña por su hash antes de enviarla
  pwdInput.value = hashed;

  // Enviar el formulario con el hash
  this.submit();
});
function refreshCaptcha() {
  document.getElementById("captchaImg").src = "captcha.php?" + Date.now();
}

</script>
</body>
</html>
