<!-- Modal Aceptar Cita -->
<div id="aceptarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aceptar Cita</h3>
            
            <form id="aceptarForm" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="fecha_asignada" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha y Hora de la Cita
                    </label>
                    <input type="datetime-local" 
                           id="fecha_asignada" 
                           name="fecha_asignada" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="{{ now()->format('Y-m-d\TH:i') }}">
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>Fecha solicitada por el cliente:</strong> <span id="fecha-solicitada-info">Se cargará automáticamente</span><br>
                        Puedes confirmar esta fecha o cambiarla si es necesario.
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('aceptarModal').classList.add('hidden')"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Aceptar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
