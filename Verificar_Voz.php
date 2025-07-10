<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificación de Voz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: url('./Media/FB_IMG.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', sans-serif;
    position: relative;
  }

  body::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6); /* oscurece el fondo */
    z-index: 0;
  }

  form {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 30px 40px;
    max-width: 400px;
    width: 100%;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    text-align: center;
    color: white;
  }

  #wave {
    width: 200px;
    height: 20px;
    background: linear-gradient(90deg, #28a745, #6f42c1, #17a2b8);
    background-size: 200% 100%;
    animation: waveAnim 1.2s linear infinite;
    border-radius: 10px;
    margin: 20px auto;
    display: none;
  }
  @keyframes waveAnim {
    0% { background-position: 0 0; }
    100% { background-position: 200% 0; }
  }
  #transcripcion {
    margin-top: 15px;
    font-size: 1.1rem;
    color: #17a2b8;
    min-height: 30px;
  }
  #mensaje {
    margin-top: 20px;
    font-weight: bold;
  }
  button {
    margin-top: 20px;
  }
</style>

</head>
<body>
  <form>
  <h2>Verificación de Voz</h2>
  <p>Por favor, di la frase secreta para continuar.</p>
  <div id="wave"></div>
  <button id="btnVoz" type="button" class="btn btn-light">Iniciar Verificación de Voz</button>
  <div id="transcripcion"></div>
  <div id="mensaje"></div>
</form>


  <script>
    const btn = document.getElementById('btnVoz');
    const wave = document.getElementById('wave');
    const transcripcion = document.getElementById('transcripcion');
    const mensaje = document.getElementById('mensaje');

    // Frase secreta (la defines tú)
    const fraseSecreta = "mustang";

    btn.addEventListener('click', () => {
      if (!('webkitSpeechRecognition' in window)) {
        mensaje.textContent = "Tu navegador no soporta reconocimiento de voz.";
        mensaje.style.color = "orange";
        return;
      }

      const recognition = new webkitSpeechRecognition();
      recognition.lang = "es-ES";
      recognition.continuous = false;
      recognition.interimResults = true;

      wave.style.display = "block";
      transcripcion.textContent = "";
      mensaje.textContent = "";
      btn.disabled = true;
      btn.textContent = "Escuchando...";

      recognition.start();

      recognition.onresult = function(event) {
        let texto = "";
        for (let i = event.resultIndex; i < event.results.length; ++i) {
          texto += event.results[i][0].transcript;
        }
        transcripcion.textContent = texto;

        if (event.results[0].isFinal) {
          recognition.stop();
          texto = texto.trim().toLowerCase();
          if (texto === fraseSecreta) {
            mensaje.textContent = "✅ Verificación exitosa. ¡Bienvenido!";
            mensaje.style.color = "lime";
            setTimeout(() => {
              window.location.href = "./HTML/index.php";
            }, 1500);
          } else {
            mensaje.textContent = "❌ Frase incorrecta. Intenta de nuevo.";
            mensaje.style.color = "red";
            btn.disabled = false;
            btn.textContent = "Intentar otra vez";
            wave.style.display = "none";
          }
        }
      };

      recognition.onerror = function(event) {
        mensaje.textContent = "Error: " + event.error;
        mensaje.style.color = "orange";
        btn.disabled = false;
        btn.textContent = "Intentar otra vez";
        wave.style.display = "none";
      };

      recognition.onend = function() {
        if (btn.disabled) {
          btn.disabled = false;
          btn.textContent = "Intentar otra vez";
          wave.style.display = "none";
        }
      };
    });
  </script>
</body>
</html>
