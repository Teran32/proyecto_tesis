// funcion para selecnionar el vehiculo que se necesita rellenando input 
function gestionarCambioVehiculo(tipo, valor) {
    if (!valor) {
        return;
    }

    fetch(`buscar_vehiculo_datos.php?tipo=${tipo}&valor=${valor}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            if (data.modo === 'rellenar') {
                document.getElementById('mostrarMarca').value = data.id_marca;
                document.getElementById('mostrarModelo').value = data.id_modelo;
                document.getElementById('selectPlaca').value = data.id_placa;
            } 
            else if (data.modo === 'filtrar') {
                if (tipo === 'marca') {
                    actualizarSelect('mostrarModelo', data.modelos);
                    actualizarSelect('selectPlaca', data.placas);
                } 
                else if (tipo === 'modelo') {

                    document.getElementById('mostrarMarca').value = data.id_marca;
                    actualizarSelect('selectPlaca', data.placas);
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

function actualizarSelect(idElemento, opciones) {
    const select = document.getElementById(idElemento);
    const valorPrevio = select.value;
    
    select.innerHTML = '<option value="">Seleccione...</option>';

    for (const [id, texto] of Object.entries(opciones)) {
        let opt = document.createElement('option');
        opt.value = id;
        opt.textContent = texto;
        select.appendChild(opt);
    }
}
// final de la eleccion de vehiculo su logica claramente