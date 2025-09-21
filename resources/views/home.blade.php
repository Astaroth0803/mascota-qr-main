<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Home')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
    <!-- <x-alert type="Info" >
        <x-slot name="title">Bienvenido</x-slot>
        Gracias por ser parte de Buky World!      
    </x-alert>

    <h1>Welcome!</h1> -->

      <!-- Hero Section -->
  <div class="min-h-screen min-w-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-black">
    <div class="text-center">
      <!-- Main Catch -->
      <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 animate-bounce">
        Bienvenidos a Buky World
      </h1>
      <!-- Subtext -->
      <p class="text-xl md:text-2xl text-gray-200 mb-8">
        [FRASES][FRASES][FRASES][FRASES][FRASES][FRASES]
      </p>
      <!-- Call-to-Action Button -->
      <a href="#" class="inline-block bg-white text-blue-600 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
        Unete
      </a>
    </div>
  </div>

  <!-- Features Section -->
  <div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
        Porque Buky World?
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="text-center">
          <div class="bg-blue-100 rounded-full p-6 inline-block">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="mt-6 text-xl font-semibold text-gray-900">Sobre Nosotros</h3>
          <p class="mt-2 text-gray-600">
            Discover new experiences and explore the unknown with Buky World.
          </p>
        </div>
        <!-- Feature 2 -->
        <div class="text-center">
          <div class="bg-purple-100 rounded-full p-6 inline-block">
            <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="mt-6 text-xl font-semibold text-gray-900">Comunidad</h3>
          <p class="mt-2 text-gray-600">
            Join a thriving community of explorers and creators.
          </p>
        </div>
        <!-- Feature 3 -->
        <div class="text-center">
          <div class="bg-pink-100 rounded-full p-6 inline-block">
            <svg class="w-12 h-12 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="mt-6 text-xl font-semibold text-gray-900">Proposito</h3>
          <p class="mt-2 text-gray-600">
            We constantly innovate to bring you the best experiences.
          </p>
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