<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Realizada</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Compra Realizada</h1>
            <p class="mb-4">Gracias por tu compra, {{ $user->name ?? 'Usuario' }}!</p>
            <p class="mb-4">Detalles de la compra:</p>
            <span class="block mt-4 text-gray-700">Gracias por comprar nuestro servicio de pet medical cloud, te contactaremos pronto junto con tus datos de inicio de sesión, te enviaremos un correo con la fecha aproximada de llegada de tu placa con QR personalizado, bienvenido a Buky World.</span>
            <a href="{{ route('home') }}" class="mt-6 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Volver al inicio</a>
        </div>
    </div>
</body>
</html>