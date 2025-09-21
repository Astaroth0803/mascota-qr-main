<div class="relative">
    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
    </div>
    <input type="search" id="global-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar en el sistema...">
    <div id="search-results" class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-lg dark:bg-gray-700 hidden">
        <!-- Los resultados se cargarán dinámicamente aquí -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            searchResults.innerHTML = data.map(item => `
                                <a href="${item.url}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <div class="font-semibold">${item.title}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">${item.description}</div>
                                </a>
                            `).join('');
                            searchResults.classList.remove('hidden');
                        } else {
                            searchResults.innerHTML = '<div class="px-4 py-2 text-gray-500">No se encontraron resultados</div>';
                            searchResults.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la búsqueda:', error);
                        searchResults.classList.add('hidden');
                    });
            }, 300);
        });
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!searchResults.contains(event.target) && event.target !== searchInput) {
            searchResults.classList.add('hidden');
        }
    });
});
</script>
@endpush 