<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mustang - Registro</title>
  <link rel="icon" type="image/png" href="../Media/Retro.png">
  
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      background: url('../Media/FB_IMG.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      margin: 5;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-container {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 30px 40px;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .register-container h2 {
      color: #3c3c52;
      font-weight: bold;
    }

    .form-label {
      font-weight: bold;
    }

    .password-toggle {
      background: none;
      border: none;
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      color: #333;
    }

    .btn-primary {
      background-color: #3c3c52;
      border: none;
    }

    .btn-primary:hover {
      background-color: #2d2d42;
    }

    .back-login {
      margin-top: 15px;
      text-align: center;
       color: white; /* ← AÑADIDO */
    }

    .back-login a {
      color: #3c3c52;
      text-decoration: none;
      font-weight: bold;
       color: white; /* ← AÑADIDO */
    }

    .back-login a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="register-container">
    <form action="../PHP/registrarUsuario.php" method="post">
      <h2 class="text-center mb-4">Registro</h2>

      <div class="mb-3">
        <label for="Usuario_Nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="Usuario_Nombre" name="Usuario_Nombre" placeholder="Ingresa tu nombre" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Apellidos" class="form-label">Apellidos:</label>
        <input type="text" class="form-control" id="Usuario_Apellidos" name="Usuario_Apellidos" placeholder="Ingresa tus apellidos" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Telefono" class="form-label">Número Telefónico:</label>
        <input type="text" class="form-control" id="Usuario_Telefono" name="Usuario_Telefono" placeholder="Ingresa tu número" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Email" class="form-label">Correo Electrónico:</label>
        <input type="email" class="form-control" id="Usuario_Email" name="Usuario_Email" placeholder="Ingresa tu correo electrónico" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Contraseña" class="form-label">Contraseña:</label>
        <div class="position-relative">
          <input type="password" class="form-control" id="Usuario_Contraseña" name="Usuario_Contraseña" placeholder="Ingresa tu contraseña" required autocomplete="new-password">
          <button type="button" id="showPasswordBtn" class="password-toggle" onclick="mostrarContrasena()" aria-label="Mostrar u ocultar contraseña">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Registrarse</button>
      </div>

      <div class="back-login">
        <p>¿Ya tienes una cuenta? <a href="../index.php"><i class="fas fa-sign-in-alt"></i> Inicia sesión aquí</a></p>
      </div>
    </form>
  </div>

  <script>
    function mostrarContrasena() {
      const input = document.getElementById('Usuario_Contraseña');
      const icon = document.getElementById('eyeIcon');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
