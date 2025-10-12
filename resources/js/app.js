import './bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './notifications';

// Configurar axios con CSRF token
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Configurar Laravel Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'cffab9fc9e9dee0dbb7f',
    cluster: 'us2',
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});

window.Alpine = Alpine;

Alpine.start();


    function editField(field, id) {
        // Mostrar el campo de edición y ocultar el texto normal
        document.getElementById(field + '-' + id).classList.add('hidden');
        document.getElementById('edit-' + field + '-' + id).classList.remove('hidden');
    }

    function saveField(field, id) {
        let value = document.getElementById('input-' + field + '-' + id).value;

        // Enviar la actualización al servidor usando AJAX
        axios.put(`/pets/${id}`, {
            [field]: value
        })
        .then(response => {
            // Si la actualización es exitosa, ocultamos el campo de edición
            document.getElementById(field + '-' + id).innerText = value;
            document.getElementById(field + '-' + id).classList.remove('hidden');
            document.getElementById('edit-' + field + '-' + id).classList.add('hidden');
        })
        .catch(error => {
            console.error("Error al actualizar el campo", error);
        });
    }


