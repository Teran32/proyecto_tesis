class GestionTaller {
    constructor() {
        // La caja donde guardamos todo
        this.base_de_datos = JSON.parse(localStorage.getItem("reportes_sertransafal")) || [];
    }

    guardarReporte(esFinalizado) {
        // Sacamos la info de tus cuadros actuales
        const datos = {
            id: "REP-" + Date.now(),
            fecha: document.getElementById('fecha_entrada').value,
            placa: document.getElementById('placa').value,
            vehiculo: document.getElementById('vehiculo_marca').value, // Asegúrate que este ID exista en tu HTML
            falla: document.getElementById('falla_detectada')?.value || "Sin falla",
            estado: esFinalizado ? "Finalizado" : "En Proceso"
        };

        this.base_de_datos.push(datos);
        localStorage.setItem("reportes_sertransafal", JSON.stringify(this.base_de_datos));

        alert("¡Guardado! Ahora puedes verlo en la lista de " + datos.estado);
        window.location.href = "../InterfazPrincipal.html"; // Volver al inicio
    }

    // Esta función dibuja las tarjetas en tu lista_reportes.html
    mostrarEnLista(filtro) {
        const contenedor = document.getElementById('cuerpo_tabla') || document.getElementById('contenedor_tarjetas');
        if (!contenedor) return;

        const filtrados = this.base_de_datos.filter(r =>
            filtro === 'finalizado' ? r.estado === "Finalizado" : r.estado === "En Proceso"
        );

        contenedor.innerHTML = "";
        filtrados.forEach(repo => {
            // Aquí creamos la tarjeta "alojada"
            contenedor.innerHTML += `
                <div class="tarjeta_form" onclick="location.href='ver_reporte.html?id=${repo.id}'">
                    <p><b>Placa:</b> ${repo.placa}</p>
                    <p><b>Estado:</b> ${repo.estado}</p>
                    <button class="btn_proceso">Ver Detalle</button>
                </div>
            `;
        });
    }
}

const taller = new GestionTaller();