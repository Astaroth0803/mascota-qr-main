<nav class="bg-white light:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 light:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <!-- Logo and Brand Name -->
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://i.postimg.cc/9Fs7Jxfy/Mesa-de-trabajo-2.png" class="h-14" alt="Buky World Logo">
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-black">Buky World</span>
    </a>

    <!-- Hamburger Button (Mobile Only) -->
    <button id="hamburger-button" class="md:hidden p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
      </svg>
    </button>

    <!-- Navigation Links -->
    <div class="hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
      <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white light:bg-gray-800 md:light:bg-gray-900 light:border-gray-700">
        <li>
          <a href="{{ route('home') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('home') ? 'text-blue-700' : '' }}">Home</a>
        </li>
        <li>
          <a href="{{ route('comunidad') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('comunidad') ? 'text-blue-700' : '' }}">Comunidad</a>
        </li>
        <li>
          <a href="{{ route('about') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('about') ? 'text-blue-700' : '' }}">Sobre nosotros</a>
        </li>
        <li>
          <a href="{{ route('contactanos') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('contactanos') ? 'text-blue-700' : '' }}">Contactanos</a>
        </li>
        <li>
          <a href="{{ route('tienda') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('tienda') ? 'text-blue-700' : '' }}">Tienda</a>
        </li>
        <li>
          <a href="{{ route('login') }}" class="block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-black dark:hover:bg-gray-700 dark:hover:text-black md:dark:hover:bg-transparent dark:border-gray-700 {{ request()->routeIs('login') ? 'text-blue-700' : '' }}">Entrar</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- JavaScript for Hamburger Menu -->
<script>
  // Toggle the mobile menu
  const hamburgerButton = document.getElementById('hamburger-button');
  const navbarSticky = document.getElementById('navbar-sticky');

  if (hamburgerButton) {
    hamburgerButton.addEventListener('click', () => {
      navbarSticky.classList.toggle('hidden');
    });
  }
</script>