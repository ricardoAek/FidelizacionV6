<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mustang - Inicio de Sesión</title>
  <link rel="icon" type="image/png" href="./Media/Retro.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      margin: 5;
      padding: 0;
      height: 100vh;
      background: url('./Media/mustrojo.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
      position: relative;
    }

    /* Capa oscura sobre la imagen */
    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6); /* oscurece el fondo */
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 20px;
      padding: 30px 40px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(12px);
      color: white;
    }

    .form-label {
      font-weight: bold;
      color: white;
    }

    .password-toggle {
      background: none;
      border: none;
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      color: #fff;
      font-size: 1rem;
    }

    .btn-primary {
      background-color: #3c3c52;
      border: none;
    }

    .btn-primary:hover {
      background-color: #2d2d42;
    }

    .btn-outline-light {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      border: 1px solid #fff;
      transition: 0.3s;
    }

    .btn-outline-light:hover {
      background-color: #fff;
      color: #3c3c52;
    }

    .text-white {
      color: white !important;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <form action="./PHP/iniciarSesion.php" method="post">
      <h2 class="text-center mb-4 text-white">Iniciar Sesión</h2>

      <div class="mb-3">
        <label for="Usuario_Telefono" class="form-label">Número Telefónico:</label>
        <input type="text" class="form-control" id="Usuario_Telefono" name="Usuario_Telefono" placeholder="Ingresa tu número" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Contraseña" class="form-label">Contraseña:</label>
        <div class="position-relative">
          <input type="password" class="form-control" id="Usuario_Contraseña" name="Usuario_Contraseña" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
          <button type="button" class="password-toggle" onclick="mostrarContrasena()" aria-label="Mostrar u ocultar contraseña">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-grid mb-2">
        <button type="submit" name="btnLogin" class="btn btn-primary">Iniciar Sesión</button>
      </div>

      <div class="text-center mt-3">
        <span class="text-white">¿No tienes cuenta?</span><br>
        <a href="./Admin/index.php" class="btn btn-sm btn-outline-light mt-2">
          <i class="fas fa-user-plus"></i> Registrarse
        </a>
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
