<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Sobre nosotros')
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
          Quienes Somos?
        </h2>
        <!-- <p class="text-xl text-gray-200">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan tortor.
        </p> -->
      </div>
    </div>

    <!-- About Section -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <!-- Left Column: Image -->
          <div class="flex items-center justify-center">
          <img src="https://i.postimg.cc/9Fs7Jxfy/Mesa-de-trabajo-2.png" class="h-14" alt="Buky World Logo">
          </div>
          <!-- Right Column: Text -->
          <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">
              Nuestra Historia
            </h2>
            <p class="mb-4">
            Esta es la historia de un sueño, un ideal, una promesa, ¿Que pasa por 
la mente de una niña de 7 años que no entiende de limitaciones y 
solo cree que puede conquistar su mundo con tan solo sus 
sentimientos más puros? desde los 7 años la pequeña niña junto a 
sus amigas más cercanas, inspiradas en una serie infantil que 
ayudaban a mascotas, se juntaron como equipo y sin limitaciones 
mentales o sociales ayudaban a cuánta mascota desamparada se 
encontraban.</p>
<p class="mb-4"> Se organizaban, pasaban de casa en casa por los 
vecinos pidiendo alimentos, enseres y lo principal, ¡¡buscándoles un 
hogar!!  Ese pequeño equipo fueron la sensación de los adultos que 
las veíamos desde nuestra “madurez” y mundo de 
responsabilidades. Imposible resistirse a su tenacidad, ante sus 
pedidos, muchos las apoyamos en sus aventuras infantiles 
ayudando a los pequeños peludos abandonados.
</p>
<p class="mb-4">
Así lo que al principio se pensó que sería la “moda” del momento, 
como suele suceder en la mayoría de niños que son influenciados 
por los programas infantiles de turno, resultó un movimiento que 
aún perdura.
</p>
<p class="mb-4">
 La idea del proyecto de Buky surge luego de conocerse sobre la 
noticia del otrora alcalde de la ciudad de San Salvador, Nayib 
Bukele cuando rescató una cachorra Husky de las calles y le 
cambió la vida. Veló por su recuperación médica, compartió en las 
redes todo el progreso y terminó buscándole un hogar. Esta noticia 
generó un punto de inflexión en tantas personas que hacen de 
manera similar pero ni de cerca tienen un impacto social, ni tienen 
el alcance mediático que tuvo el actual presidente del Salvador. 
Así pasó por años con el equipo de esta niña pequeña. Héroes 
anónimos que de manera desinteresada comparten esperanza y 
amor. </p>
<p class="mb-4">Lo que llevo a la pregunta ¿¡qué tal si creamos una 
comunidad comprometida que sea autosustentable y con el 
potencial de viralizarse en las redes sociales a fin de apoyar con 
nuestro esfuerzo y gestionar recursos en favor de los peludos 
menos afortunados?!</p>
<p class="mb-4">
De ahí surge el nombre de Buky y su parecido al del presidente 
Bukele. Con el ideal de ser una comunidad comprometida de 
manera social y financiera y sin olvidar, el mismo sentir de esas 
niñas que no entendían de limitaciones y obstáculos y desde sus 
sentimientos más nobles lograron que muchos compartiéran 
bienestar a los animalitos menos afortunados.</p>
<p class="mb-4">
 ¡Si lo puedes creer lo puedes lograr!.</p>
 <p class="mb-4">
 Think Different!!!</p>
 <p class="mb-4">
 Por un mundo más consciente y libre</p>
 <p class="mb-4 font-bold">
  
 #ImBuky</p>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Mission Section -->
    <div class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
          Nuestra Mision y Vision
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Mission 1 -->
          <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="bg-blue-100 rounded-full p-4 inline-block">
            <svg class="w-6 h-6 text-blue-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M7.05 4.05A7 7 0 0 1 19 9c0 2.407-1.197 3.874-2.186 5.084l-.04.048C15.77 15.362 15 16.34 15 18a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1c0-1.612-.77-2.613-1.78-3.875l-.045-.056C6.193 12.842 5 11.352 5 9a7 7 0 0 1 2.05-4.95ZM9 21a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2h-4a1 1 0 0 1-1-1Zm1.586-13.414A2 2 0 0 1 12 7a1 1 0 1 0 0-2 4 4 0 0 0-4 4 1 1 0 0 0 2 0 2 2 0 0 1 .586-1.414Z" clip-rule="evenodd"/>
</svg>

            </div>
            <h3 class="mt-6 text-xl font-semibold text-gray-900">Mision</h3>
            <p class="mt-2 text-gray-600">
            Somos una comunidad comprometida con  la mejora de 
las condiciones de animales vulnerables, influenciamos a 
otros y nos unirnos bajo una imagen de marca para 
generar un mayor impacto,  con el objetivo de ser 
sostenibles económicamente a través de la venta de 
productos, pero principalmente ofreciendo soluciones 
tecnológicas de hospedaje (hosting) de los datos de tus 
mascotas. (cloud)
            </p>
          </div>
          <!-- Mission 2 -->
          <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="bg-purple-100 rounded-full p-4 inline-block">
            <svg class="w-6 h-6 text-purple-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7.171 12.906-2.153 6.411 2.672-.89 1.568 2.34 1.825-5.183m5.73-2.678 2.154 6.411-2.673-.89-1.568 2.34-1.825-5.183M9.165 4.3c.58.068 1.153-.17 1.515-.628a1.681 1.681 0 0 1 2.64 0 1.68 1.68 0 0 0 1.515.628 1.681 1.681 0 0 1 1.866 1.866c-.068.58.17 1.154.628 1.516a1.681 1.681 0 0 1 0 2.639 1.682 1.682 0 0 0-.628 1.515 1.681 1.681 0 0 1-1.866 1.866 1.681 1.681 0 0 0-1.516.628 1.681 1.681 0 0 1-2.639 0 1.681 1.681 0 0 0-1.515-.628 1.681 1.681 0 0 1-1.867-1.866 1.681 1.681 0 0 0-.627-1.515 1.681 1.681 0 0 1 0-2.64c.458-.361.696-.935.627-1.515A1.681 1.681 0 0 1 9.165 4.3ZM14 9a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
</svg>

            </div>
            <h3 class="mt-6 text-xl font-semibold text-gray-900">Vision</h3>
            <p class="mt-2 mb-4 text-gray-600">
            Convertirse en una gran comunidad, descentralizada, 
autosostenible, y que genera iniciativas, ideas de desarrollo 
social q sirvan para ayudar a las mascotas, a través de los 
embajadores, Logrando unir esfuerzos, poder de 
convocatoria, generando tendencia en redes etc.</p>
<p class="text-gray-600">
Posicionarnos en el nicho de las mascotas con la identidad de 
nuestra marca y ser una opción en ventas de productos 
relacionados a las mismas. Sin perder el enfoque principal 
que nos impulsa de generar economía para ayudar a los más 
vulnerables.
            </p>
          </div>

        </div>
      </div>
    </div>
  
</main>
@endsection 

</body>
</html>