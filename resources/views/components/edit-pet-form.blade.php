<!-- resources/views/components/edit-pet-form.blade.php -->
<form action="{{ route('pet.update', $pet->id) }}" method="POST" class="max-w-sm mx-auto">
    @csrf
    @method('post') <!-- Necesitas este método para indicar que es una actualización -->

    <!-- Aquí rellenarás los campos con los datos de la mascota -->
    <div class="mb-5">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $pet->nombre }}" />
    </div>

    <div class="mb-5">
        <label for="especie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Especie</label>
        <input type="text" name="especie" id="especie" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $pet->especie }}" />
    </div>

    <!-- Repite para cada campo necesario, como raza, edad, sexo -->

    <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded-md">Actualizar</button>
</form>
