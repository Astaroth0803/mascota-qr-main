<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Tienda')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
      <!-- Hero Section -->
  <div class="min-w-screen bg-gradient-to-r from-blue-600 to-black py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">
        Nuestros Productos
      </h2>
      <p class="text-xl text-gray-200">
        
        Descubra nuestra oferta de productos!
      </p>
    </div>
  </div>

  <!-- Store Content -->
  <div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Filters -->
      <div class="mb-12">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
        <div class="flex space-x-4">
          <button class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300">
            Todo
          </button>
          <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300 transition duration-300">
            Servicios en Cloud
          </button>
          <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300 transition duration-300">
            Alimentos para Mascotas
          </button>
          <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300 transition duration-300">
            Otros
          </button>
        </div>
      </div>

      <!-- Producto Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <!-- Producto 1 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 1" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 1</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$29.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
        <!-- Producto 2 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 2" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 2</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$39.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
        <!-- Producto 3 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 3" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 3</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$49.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
        <!-- Producto 4 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 4" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 4</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$59.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
        <!-- Producto 5 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 5" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 5</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$69.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
        <!-- Producto 6 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/300x200" alt="Producto 6" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Producto 6</h3>
          <p class="text-gray-600 mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </p>
          <p class="text-lg font-bold text-blue-600">$79.99</p>
          <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            Anade al Carrito
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

</main>

<footer>
    
</footer> 
@endsection 

</body>
</html>