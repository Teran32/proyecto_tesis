async function abrirModal(tipo) {
    // 1. Definimos textos dinámicos según el botón presionado
    const titulo = tipo === 'marca' ? 'Añadir Nueva Marca' : 'Añadir Nuevo Modelo';
    const placeholder = tipo === 'marca' ? 'Ej: Toyota' : 'Ej: Hilux';

    // 2. Lanzamos la alerta de SweetAlert2
    const { value: nuevoValor } = await Swal.fire({
        title: `<span style="color: #1a252f; font-family: 'Segoe UI', sans-serif;">${titulo}</span>`,
        input: 'text',
        inputPlaceholder: placeholder,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3498db', // Color --primary de tu CSS
        cancelButtonColor: '#95a5a6',
        background: '#ffffff',
        borderRadius: '16px',
        inputAttributes: {
            autocapitalize: 'words'
        },
        // Validación para que no guarden campos vacíos
        inputValidator: (value) => {
            if (!value) {
                return '¡Debes escribir un nombre!';
            }
        }
    });

    // 3. Si el usuario escribió algo y dio a "Guardar"
    if (nuevoValor) {
        // Mostramos una alerta de éxito
        Swal.fire({
            title: '¡Registrado!',
            text: `${nuevoValor} se agregó a la lista de ${tipo}s.`,
            icon: 'success',
            confirmButtonColor: '#27ae60', // Color --success de tu CSS
            timer: 2000,
            showConfirmButton: false
        });

        // 4. Actualizamos el SELECT correspondiente automáticamente
        const idSelect = tipo === 'marca' ? 'marcaVehiculo' : 'modeloVehiculo';
        const selectElement = document.getElementById(idSelect); //

        const nuevaOpcion = document.createElement('option');
        nuevaOpcion.value = nuevoValor.toLowerCase();
        nuevaOpcion.text = nuevoValor;
        nuevaOpcion.selected = true; // Lo deja seleccionado de una vez

        selectElement.add(nuevaOpcion);
    }
}

function eliminarChofer(id) {
    if (confirm("¿Estás seguro de eliminar a este chofer?")) {
        window.location.href = `eliminar_chofer.php?id=${id}`;
    }
}