<!-- Modal Rechazar Cita -->
<div id="rechazarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Cita</h3>
            
            <form id="rechazarForm" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="motivo_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del Rechazo *
                    </label>
                    <textarea id="motivo_rechazo" 
                              name="motivo_rechazo" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Explica el motivo del rechazo..."
                              required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('rechazarModal').classList.add('hidden')"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Rechazar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
