<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de acceso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007BFF;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .credentials {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .credentials p {
            margin: 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a nuestro servicio</h1>
        <p>Gracias por registrarte. A continuación, te proporcionamos tus datos de acceso:</p>

        <div class="credentials">
            <p><strong>Correo electrónico:</strong> {{ $email }}</p>
            <p><strong>Contraseña:</strong> {{ $password }}</p>
        </div>

        <p>Puedes iniciar sesión en nuestra plataforma haciendo clic en el siguiente enlace:</p>
        <p>
            <a href="{{ url('/login') }}" style="color: #007BFF; text-decoration: none;">
                Iniciar sesión
            </a>
        </p>

        <div class="footer">
            <p>Si no has solicitado este registro, por favor ignora este correo.</p>
            <p>Gracias,</p>
            <p>El equipo de {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>