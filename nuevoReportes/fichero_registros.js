// Definimos la "plantilla" o clase para cada reporte
class Reporte {
    constructor(datos) {
        this.id = datos.id || "REP-" + Date.now();
        this.fecha = datos.fecha;
        this.placa = datos.placa;
        this.vehiculo = datos.vehiculo;
        this.kilometraje = datos.kilometraje;
        this.falla = datos.falla;
        this.trabajo = datos.trabajo;
        this.repuestos = datos.repuestos;
        this.estado = datos.estado;
    }
}

// Clase que maneja toda la lógica del taller (El Administrador)
class GestionTaller {
    constructor() {
        // Cargamos los datos guardados al iniciar
        this.base_de_datos = JSON.parse(localStorage.getItem("reportes_sertransafal")) || [];
    }

    // Método para capturar los datos del formulario y guardarlos
    procesarFormulario(esFinalizado) {
        const info = {
            fecha: document.getElementById('fecha_entrada').value,
            placa: document.getElementById('placa').value.toUpperCase(), // Siempre en mayúsculas
            vehiculo: document.getElementById('vehiculo_modelo').value,
            kilometraje: document.getElementById('km_actual').value,
            falla: document.getElementById('falla_detectada').value,
            trabajo: document.getElementById('trabajo_realizado').value,
            repuestos: document.getElementById('repuestos').value,
            estado: esFinalizado ? "Finalizado" : "En Proceso"
        };

        if (!info.placa) {
            alert("⚠️ La placa es obligatoria.");
            return;
        }

        const nuevo = new Reporte(info);
        this.base_de_datos.push(nuevo);
        this.guardarEnMemoria();

        alert(`✅ Reporte ${nuevo.id} guardado como ${nuevo.estado}`);
        window.location.href = "index.html";
    }

    guardarEnMemoria() {
        localStorage.setItem("reportes_sertransafal", JSON.stringify(this.base_de_datos));
    }
}

// Creamos una instancia única del administrador para usarla en la página
const taller = new GestionTaller();

// Función puente para los botones del HTML
function guardar(esFinalizado) {
    taller.procesarFormulario(esFinalizado);
}