async function agregarMaestro(tipo) {
    const titulo = tipo === 'marca' ? 'Añadir Nueva Marca' : 'Añadir Nuevo Modelo';
    const placeholder = tipo === 'marca' ? 'Ej: Toyota' : 'Ej: Hilux';
    const idMarcaPadre = document.getElementById('marcaVehiculo').value;

    // Si es modelo, necesitamos saber a qué marca pertenece
    if (tipo === 'modelo' && !idMarcaPadre) {
        Swal.fire('Error', 'Debes seleccionar una Marca primero', 'warning');
        return;
    }

    const { value: nuevoValor } = await Swal.fire({
        title: `<span style="color: #1a252f;">${titulo}</span>`,
        input: 'text',
        inputPlaceholder: placeholder,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        confirmButtonColor: '#3498db',
        inputValidator: (value) => {
            if (!value) return '¡Debes escribir un nombre!';
        }
    });

    if (nuevoValor) {
        const datos = new FormData();
        datos.append('tipo', tipo);
        datos.append('nombre', nuevoValor);
        datos.append('id_marca_padre', idMarcaPadre);

        fetch('api/insertar_datos.php', {
            method: 'POST',
            body: datos
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Registrado!',
                        text: `${nuevoValor} se guardó en la base de datos.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Actualizar el SELECT con el ID real que devolvió la base de datos
                    const idSelect = tipo === 'marca' ? 'marcaVehiculo' : 'modeloVehiculo';
                    const selectElement = document.getElementById(idSelect);

                    const nuevaOpcion = document.createElement('option');
                    nuevaOpcion.value = data.id_nuevo;
                    nuevaOpcion.text = nuevoValor;
                    nuevaOpcion.selected = true;
                    selectElement.add(nuevaOpcion);

                    if (tipo === 'marca') {
                        verificarMarca(data.id_nuevo);
                    }
                } else {
                    Swal.fire('Error', data.error || 'No se pudo guardar', 'error');
                }
            });
    }
}

//Función para eliminar cualquier chofer que yo elija
function eliminarChofer(id) {
    Swal.fire({
        title: '¿Eliminar chofer?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `api/eliminar.php?tipo=chofer&id=${id}`;
        }
    });
}


//Función para verificar la marca
function verificarMarca(idMarca) {
    const selectModelo = document.getElementById('modeloVehiculo');
    if (idMarca) {
        selectModelo.disabled = false;
        // Petición AJAX para obtener modelos de esa marca
        fetch(`api/obtener_modelos.php?id_marca=${idMarca}`)
            .then(res => res.text())
            .then(html => {
                selectModelo.innerHTML = html;
            });
    } else {
        selectModelo.disabled = true;
        selectModelo.innerHTML = '<option value="">Selecciona marca primero...</option>';
    }
}

$(document).ready(function() {
    const dtConfig = {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        responsive: true
    };
    $('#tablaUnidades').DataTable(dtConfig);
    $('#tablaChoferes').DataTable(dtConfig);
});

//Función para editar cualquier unidad o chofer
function editar(tipo, id) {
    Swal.fire({
        title: '¿Modificar ' + tipo + '?',
        text: "Esta acción te redirigirá a la vista de edición",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        confirmButtonText: 'Sí, modificar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `api/modificar.php?tipo=${tipo}&id=${id}`;
        }
    });
}