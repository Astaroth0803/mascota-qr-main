import './bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';

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
            _token: '{{ csrf_token() }}', // CSRF Token para seguridad
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


