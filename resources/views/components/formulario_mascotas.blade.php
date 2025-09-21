<form action="{{ route('solicitudes.store') }}" method="POST" class="max-w-sm mx-auto" enctype="multipart/form-data">
    @csrf
    <div id="formulario_mascotas">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Nombre de la mascota</label>
            <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('nombre') }}" placeholder="Nombre de la mascota" required />
        </div>

        <div class="mb-5">
            <label for="especie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Especie</label>
            <input type="text" name="especie" id="especie" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('especie') }}" placeholder="Especie de la mascota" required />
        </div>

        <div class="mb-5">
            <label for="raza" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Raza</label>
            <input type="text" name="raza" id="raza" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('raza') }}" placeholder="Raza de la mascota" required />
        </div>

        <div class="mb-5">
            <label for="edad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Edad</label>
            <input type="text" name="edad" id="edad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('edad') }}" placeholder="Edad de la mascota" required />
        </div>

        <div class="mb-5">
            <label for="sexo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Sexo</label>
            <input type="text" name="sexo" id="sexo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('sexo') }}" placeholder="Sexo de la mascota" required />
        </div>

        <div class="mb-5" id="nombre_owner1">
            <label for="nombre_owner" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Nombre del dueño</label>
            <input type="text" name="nombre_owner" id="nombre_owner" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('nombre_owner') }}" placeholder="Nombre del dueño" required />
        </div>

        <div class="mb-5" id="apellido_owner1">
            <label for="apellido_owner" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Apellido del dueño</label>
            <input type="text" name="apellido_owner" id="apellido_owner" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('apellido_owner') }}" placeholder="Apellido del dueño" required />
        </div>

        <div class="mb-5" id="telefono_owner1">
            <label for="telefono_owner" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Teléfono</label>
            <input type="text" name="telefono_owner" id="telefono_owner" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('telefono_owner') }}" placeholder="Teléfono" required />
        </div>

        <div class="mb-5" id="correo_owner1">
            <label for="correo_owner" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Correo</label>
            <input type="email" name="correo_owner" id="correo_owner" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('correo_owner') }}" placeholder="Correo Electrónico" required />
            @error('correo_owner')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5" id="id_pago_yappy1">
            <label for="id_pago_yappy" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Id del pago en Yappy</label>
            <input type="text" name="id_pago_yappy" id="id_pago_yappy" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('id_pago_yappy') }}" placeholder="Id del pago realizado" required />
        </div>

        <button type="submit" id="btm_enviar_form"
            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2">
            Registrar Solicitud
        </button>
    </div>
</form>
