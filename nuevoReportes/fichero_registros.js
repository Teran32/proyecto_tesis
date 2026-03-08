// CONFIGURACIÓN DE TU BASE DE DATOS EN LA NUBE
const _supabase = supabase.createClient('https://quojudgnpxpndmyrhjcy.supabase.co', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InF1b2p1ZGducHhwbmRteXJoamN5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzI5NzU5MjQsImV4cCI6MjA4ODU1MTkyNH0.MEbjV0vAcXt8W-TWCgsN1_gRT3ZP7wtMCGw72Dyy1I0');

class GestionTaller {
    constructor() {
        this.fotosTemp = [];
    }

    // --- FUNCIÓN PARA AUTOCOMPLETAR (ADS: Integridad de datos) ---
    async verificarVehiculo() {
        const placaInput = document.getElementById('placa').value.toUpperCase();
        if (placaInput.length < 5) return;

        // Buscamos la placa y traemos su marca y modelo mediante las relaciones (Foreign Keys)
        const { data, error } = await _supabase
            .from('vehiculos')
            .select(`
                placa,
                modelos (
                    nombre,
                    marcas (nombre)
                )
            `)
            .eq('placa', placaInput)
            .single();

        if (data && data.modelos) {
            document.getElementById('vehiculo_marca').value = data.modelos.marcas.nombre;
            document.getElementById('Modelo').value = data.modelos.nombre;
            console.log("Vehículo encontrado en la base de datos");
        }
    }

    // --- FUNCIÓN PARA GUARDAR TODO (ADS: Transaccionalidad manual) ---
    async guardarReporte(esFinalizado) {
        const id_existente = document.getElementById('reporte_id')?.value;
        const id_final = id_existente || "REP-" + Date.now();

        const placa = document.getElementById('placa').value.toUpperCase();
        const marcaNombre = document.getElementById('vehiculo_marca').value;
        const modeloNombre = document.getElementById('Modelo').value;

        try {
            // PASO A: Asegurar que la MARCA existe
            let { data: marcaObj } = await _supabase.from('marcas').select('id').eq('nombre', marcaNombre).single();
            if (!marcaObj) {
                const { data: nuevaMarca } = await _supabase.from('marcas').insert([{ nombre: marcaNombre }]).select().single();
                marcaObj = nuevaMarca;
            }

            // PASO B: Asegurar que el MODELO existe
            let { data: modeloObj } = await _supabase.from('modelos').select('id').eq('nombre', modeloNombre).eq('marca_id', marcaObj.id).single();
            if (!modeloObj) {
                const { data: nuevoModelo } = await _supabase.from('modelos').insert([{ nombre: modeloNombre, marca_id: marcaObj.id }]).select().single();
                modeloObj = nuevoModelo;
            }

            // PASO C: Asegurar que el VEHÍCULO existe (Relaciona Placa con Modelo)
            await _supabase.from('vehiculos').upsert([{ placa: placa, modelo_id: modeloObj.id }]);

            // PASO D: Guardar el REPORTE
            const imagenesVisibles = document.querySelectorAll('#galeriaDinamica img, #vista_previa_fotos img');
            const todasLasFotos = Array.from(imagenesVisibles).map(img => img.src);

            const datosReporte = {
                id: id_final,
                placa: placa,
                fecha_entrada: document.getElementById('fecha_entrada')?.value || "",
                fecha_salida: document.getElementById('fecha_salida')?.value || "",
                km_actual: document.getElementById('km_actual')?.value || "",
                proximo_km: document.getElementById('proximo_km')?.value || "",
                falla_detectada: document.getElementById('falla_detectada')?.value || "",
                trabajo_realizado: document.getElementById('trabajo_realizado')?.value || "",
                repuestos: document.getElementById('repuestos')?.value || "",
                pedido_repuestos: document.getElementById('pedido_repuestos')?.value || "",
                estado: esFinalizado ? "Finalizado" : "En Proceso",
                fotos: todasLasFotos
            };

            const { error: errorReporte } = await _supabase.from('reportes').upsert([datosReporte]);

            if (errorReporte) throw errorReporte;

            alert("¡Datos guardados en la nube correctamente!");
            window.location.href = id_existente ? "../finalizados/lista_reportes.html?filtro=" + (esFinalizado ? 'finalizado' : 'proceso') : "../InterfazPrincipal.html";

        } catch (err) {
            console.error("Error en el proceso:", err);
            alert("Error al guardar. Revisa la consola.");
        }
    }

    // --- CARGAR DATOS PARA EDITAR ---
    async cargarReporteParaEdicion(id) {
        const { data: reporte, error } = await _supabase
            .from('reportes')
            .select(`*, vehiculos(placa, modelos(nombre, marcas(nombre)))`)
            .eq('id', id)
            .single();

        if (reporte) {
            document.getElementById('reporte_id').value = reporte.id;
            document.getElementById('placa').value = reporte.placa;
            document.getElementById('fecha_entrada').value = reporte.fecha_entrada;
            document.getElementById('fecha_salida').value = reporte.fecha_salida;
            document.getElementById('km_actual').value = reporte.km_actual;
            document.getElementById('proximo_km').value = reporte.proximo_km;
            document.getElementById('falla_detectada').value = reporte.falla_detectada;
            document.getElementById('trabajo_realizado').value = reporte.trabajo_realizado;
            document.getElementById('repuestos').value = reporte.repuestos;
            document.getElementById('pedido_repuestos').value = reporte.pedido_repuestos;

            // Datos del vehículo desde las otras tablas
            document.getElementById('vehiculo_marca').value = reporte.vehiculos.modelos.marcas.nombre;
            document.getElementById('Modelo').value = reporte.vehiculos.modelos.nombre;

            // Fotos
            const contenedorFotos = document.getElementById('vista_previa_fotos') || document.getElementById('galeriaDinamica');
            if (contenedorFotos && reporte.fotos) {
                contenedorFotos.innerHTML = "";
                reporte.fotos.forEach(src => {
                    const card = document.createElement('div');
                    card.className = 'foto-card';
                    card.innerHTML = `<img src="${src}"><button type="button" class="btn-borrar-pro" onclick="this.parentElement.remove()">✕</button>`;
                    contenedorFotos.appendChild(card);
                });
            }
        }
    }
}

const taller = new GestionTaller();