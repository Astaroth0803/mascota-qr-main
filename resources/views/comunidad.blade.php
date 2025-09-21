<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Comunidad')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
    <div class="min-w-screen bg-gradient-to-r from-blue-600 to-black py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">
        Unete a nuestra comunidad
      </h2>
      <p class="text-xl text-gray-200">
        
        Conecta, comparte y crece con otros amantes de las mascotas en Buky World.
      </p>
      <a href="#" class="inline-block mt-8 bg-white text-blue-600 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
        Unete a nuestra comunidad
      </a>
    </div>
  </div>

  <!-- Community Highlights Section -->
  <div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
        Momentos Destacados
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Highlight 1 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/400x300" alt="Community Event" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Reuniones Mensuales</h3>
          <p class="text-gray-600">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor.
          </p>
        </div>
        <!-- Highlight 2 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/400x300" alt="Community Event" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Talleres</h3>
          <p class="text-gray-600">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor.
          </p>
        </div>
        <!-- Highlight 3 -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
          <img src="https://via.placeholder.com/400x300" alt="Community Event" class="rounded-lg mb-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Foros</h3>
          <p class="text-gray-600">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Testimonials Section -->
  <div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
        Testimonios, Lo que dice nuestra comunidad
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Testimonial 1 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <p class="text-gray-600 italic mb-4">
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor. Nulla facilisi."
          </p>
          <div class="flex items-center">
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="rounded-full mr-4">
            <div>
              <p class="font-semibold text-gray-900">John Doe</p>
              <p class="text-sm text-gray-600">Community Member</p>
            </div>
          </div>
        </div>
        <!-- Testimonial 2 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <p class="text-gray-600 italic mb-4">
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor. Nulla facilisi."
          </p>
          <div class="flex items-center">
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="rounded-full mr-4">
            <div>
              <p class="font-semibold text-gray-900">Jane Smith</p>
              <p class="text-sm text-gray-600">Community Member</p>
            </div>
          </div>
        </div>
        <!-- Testimonial 3 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
          <p class="text-gray-600 italic mb-4">
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor. Nulla facilisi."
          </p>
          <div class="flex items-center">
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="rounded-full mr-4">
            <div>
              <p class="font-semibold text-gray-900">Alex Johnson</p>
              <p class="text-sm text-gray-600">Community Member</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Call-to-Action Section -->
  <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-4xl font-bold text-white mb-4">
        Listo para formar parte de Buky World?
      </h2>
      <p class="text-xl text-gray-200 mb-8">
        Unete a nuestra comunidad!
      </p>
      <a href="#" class="inline-block bg-white text-blue-600 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
        Registrate
      </a>
    </div>
  </div>
  
</main>
@endsection 

</body>
</html>