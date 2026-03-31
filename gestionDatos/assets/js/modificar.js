async function modificar(tipo, id) {
    if (tipo === 'vehiculo') {
        const resU = await fetch(`modificar/obtener_unidad.php?id=${id}`);
        const unidad = await resU.json();

        const { value: formValues } = await Swal.fire({
            title: 'Modificar Vehículo Completo',
            html: `
                <div style="text-align: left;">
                    <label style="font-size: 14px; font-weight: bold; color: #333;">Marca</label>
                    <input id="swal-marca" class="swal2-input" value="${unidad.marcas}" style="width: 100%; margin-top: 5px; margin-bottom: 15px; height: 40px; font-size: 16px;">
                    
                    <label style="font-size: 14px; font-weight: bold; color: #333;">Modelo</label>
                    <input id="swal-modelo" class="swal2-input" value="${unidad.modelos}" style="width: 100%; margin-top: 5px; margin-bottom: 15px; height: 40px; font-size: 16px;">

                    <label style="font-size: 14px; font-weight: bold; color: #333;">Placa</label>
                    <input id="swal-placa" class="swal2-input" value="${unidad.placas}" style="width: 100%; margin-top: 5px; height: 40px; font-size: 16px;">
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            confirmButtonText: 'Guardar Diferencias',
            preConfirm: () => {
                const nombre_marca = document.getElementById('swal-marca').value;
                const nombre_modelo = document.getElementById('swal-modelo').value;
                const nombre_placa = document.getElementById('swal-placa').value;
                if (!nombre_marca || !nombre_modelo || !nombre_placa) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }
                return { nombre_marca, nombre_modelo, nombre_placa, id_marcas: unidad.id_marcas, id_modelos: unidad.id_modelos };
            }
        });

        if (formValues) {
            const data = new FormData();
            data.append('tipo', 'vehiculo');
            data.append('id_placas', id);
            data.append('placa', formValues.nombre_placa);
            data.append('id_modelos', formValues.id_modelos);
            data.append('nombre_modelo', formValues.nombre_modelo);
            data.append('id_marcas', formValues.id_marcas);
            data.append('nombre_marca', formValues.nombre_marca);

            fetch('modificar/modificar.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(r => {
                    if (r.success) {
                        location.reload();
                    } else {
                        Swal.fire('Error', r.msg, 'error');
                    }
                });
        }
    } else if (tipo === 'chofer') {
        const resC = await fetch(`modificar/obtener_chofer.php?id=${id}`);
        const chofer = await resC.json();

        const { value: nuevoNombre } = await Swal.fire({
            title: 'Modificar Chofer',
            input: 'text',
            inputValue: chofer.chofer,
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            confirmButtonText: 'Guardar',
            inputValidator: (value) => {
                if (!value) return 'El nombre no puede estar vacío';
            }
        });

        if (nuevoNombre) {
            const data = new FormData();
            data.append('tipo', 'chofer');
            data.append('id_chofer', id);
            data.append('nombre', nuevoNombre);

            fetch('modificar/modificar.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(r => {
                    if (r.success) location.reload();
                    else Swal.fire('Error', r.msg, 'error');
                });
        }
    } else if (tipo === 'marca') {
        const resMarca = await fetch(`api/obtener_marca.php?id=${id}`);
        const marca = await resMarca.json();

        const { value: nuevoNombre } = await Swal.fire({
            title: 'Modificar Marca',
            input: 'text',
            inputValue: marca.marcas,
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            confirmButtonText: 'Guardar',
            inputValidator: (value) => {
                if (!value) return 'El nombre no puede estar vacío';
            }
        });

        if (nuevoNombre) {
            const data = new FormData();
            data.append('tipo', 'marca');
            data.append('id_marca', id);
            data.append('nombre', nuevoNombre);

            fetch('modificar/modificar.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(r => {
                    if (r.success) location.reload();
                    else Swal.fire('Error', r.msg, 'error');
                });
        }
    } else if (tipo === 'modelo') {
        const resMo = await fetch(`api/obtener_modelo.php?id=${id}`);
        const modelo = await resMo.json();

        const resM = await fetch(`api/obtener_marcas_json.php`);
        const marcas = await resM.json();

        let marcasHtml = '<option value="">Seleccione marca...</option>';
        marcas.forEach(m => {
            let sel = m.id_marcas == modelo.id_marcas ? 'selected' : '';
            marcasHtml += `<option value="${m.id_marcas}" ${sel}>${m.marcas}</option>`;
        });

        const { value: formValues } = await Swal.fire({
            title: 'Modificar Modelo',
            html: `
                <div style="text-align: left;">
                    <label style="font-size: 14px; font-weight: bold; color: #333;">Pertenece a la Marca</label>
                    <select id="swal-mod-marca" class="swal2-input" style="width: 100%; margin-top: 5px; margin-bottom: 15px; height: 40px; font-size: 16px;">
                        ${marcasHtml}
                    </select>
                    
                    <label style="font-size: 14px; font-weight: bold; color: #333;">Nombre del Modelo</label>
                    <input id="swal-mod-nombre" class="swal2-input" value="${modelo.modelos}" style="width: 100%; margin-top: 5px; height: 40px; font-size: 16px;">
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            confirmButtonText: 'Guardar',
            preConfirm: () => {
                const id_marca = document.getElementById('swal-mod-marca').value;
                const nombre = document.getElementById('swal-mod-nombre').value;
                if (!id_marca || !nombre) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }
                return { id_marca, nombre };
            }
        });

        if (formValues) {
            const data = new FormData();
            data.append('tipo', 'modelo');
            data.append('id_modelo', id);
            data.append('id_marca', formValues.id_marca);
            data.append('nombre', formValues.nombre);

            fetch('modificar/modificar.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(r => {
                    if (r.success) location.reload();
                    else Swal.fire('Error', r.msg, 'error');
                });
        }
    }
}

async function cargarModelosSwal(idMarca, idSeleccionado = null) {
    const sel = document.getElementById('swal-modelo');
    if (!idMarca) {
        sel.innerHTML = '<option value="">Seleccione marca...</option>';
        return;
    }
    const res = await fetch(`api/obtener_modelos.php?id_marca=${idMarca}`);
    const html = await res.text();
    sel.innerHTML = html;
    if (idSeleccionado) {
        sel.value = idSeleccionado;
    }
}