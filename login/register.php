<?php
// Página de registro: form que envía a process_register.php
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Registro</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Registro</h2>
  <p class="small">Crea un usuario. La contraseña se guardará como hash y se mostrará después.</p>

  <form id="registerForm" action="process_register.php" method="post" autocomplete="off">
    <label class="label" for="username">Usuario</label>
    <input class="input" id="username" name="username" type="text"
           placeholder="Ingresa tu usuario" required autocomplete="username">

    <label class="label" for="password">Contraseña</label>
    <input class="input" id="password" name="password" type="password"
           placeholder="Ingresa contraseña" required autocomplete="new-password">
<label class="label" for="captcha">Verificación</label>
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
  <img src="captcha.php" alt="Captcha" id="captchaImg" style="border:1px solid #ccc;border-radius:6px;">
  <button type="button" onclick="refreshCaptcha()" class="button" style="width:auto;padding:6px 10px;">🔄</button>
</div>
<input class="input" id="captcha" name="captcha" type="text" placeholder="Ingresa el texto de la imagen" required>

    <button class="button button-primary" type="submit">Registrar</button>
  </form>

  <a class="link" href="index.php">Volver a Iniciar sesión</a>
</div>

<!-- 🔒 Script para hashear contraseña antes de enviar -->
<script>
// ======= Función de hash (usa Web Crypto) =======
async function hashSHA256(message) {
  const msgBuffer = new TextEncoder().encode(message);
  const hashBuffer = await crypto.subtle.digest("SHA-256", msgBuffer);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  return hashArray.map(b => b.toString(16).padStart(2, "0")).join("");
}

// ======= Manejador de envío =======
document.getElementById("registerForm").addEventListener("submit", async function(e) {
  e.preventDefault(); // evita el envío normal

  const form = this;
  const pwdInput = document.getElementById("password");
  const plainPwd = pwdInput.value;

  if (!plainPwd) {
    alert("Por favor, ingresa una contraseña.");
    return;
  }

  try {
    // 1️⃣ Calcular el hash SHA-256
    const hashed = await hashSHA256(plainPwd);

    // 2️⃣ Crear input oculto con el hash
    const hidden = document.createElement("input");
    hidden.type = "hidden";
    hidden.name = "password"; // el nombre que espera PHP
    hidden.value = hashed;
    form.appendChild(hidden);

    // 3️⃣ Eliminar el atributo name del input visible (para no enviar texto plano)
    pwdInput.removeAttribute("name");

    // 4️⃣ Vaciar el campo visible
    pwdInput.value = "";

    // 5️⃣ Enviar formulario
    form.submit();
  } catch (err) {
    console.error("Error al hashear:", err);
    alert("Error al procesar la contraseña.");
  }
});
function refreshCaptcha() {
  document.getElementById("captchaImg").src = "captcha.php?" + Date.now();
}

</script>
</body>
</html>
