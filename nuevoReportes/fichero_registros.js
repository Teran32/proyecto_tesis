// 1. La "Ficha" de cada reporte
class Reporte {
    constructor(datos) {
        this.id = "REP-" + Date.now(); // Crea un número único
        this.fecha_entrada = datos.fecha_entrada;
        this.placa = datos.placa;
        this.vehiculo_modelo = datos.vehiculo_modelo;
        this.km_actual = datos.km_actual;
        this.falla_detectada = datos.falla_detectada;
        this.trabajo_realizado = datos.trabajo_realizado;
        this.repuestos = datos.repuestos;
        this.estado = datos.estado; // "En Proceso" o "Finalizado"
    }
}

// 2. El "Administrador" del taller
class GestionTaller {
    constructor() {
        // Trae los reportes guardados o empieza con una lista vacía
        this.base_de_datos = JSON.parse(localStorage.getItem("reportes_sertransafal")) || [];
    }

    // Esta función guarda la información
    guardarReporte(esFinalizado) {
        // Agarramos lo que escribiste en los cuadritos del HTML
        const datos = {
            fecha_entrada: document.getElementById('fecha_entrada').value,
            placa: document.getElementById('placa').value,
            vehiculo_modelo: document.getElementById('vehiculo_modelo').value,
            km_actual: document.getElementById('km_actual').value,
            falla_detectada: document.getElementById('falla_detectada').value,
            trabajo_realizado: document.getElementById('trabajo_realizado').value,
            repuestos: document.getElementById('repuestos').value,
            estado: esFinalizado ? "Finalizado" : "En Proceso"
        };

        // Creamos el reporte y lo metemos en la lista
        const nuevoReporte = new Reporte(datos);
        this.base_de_datos.push(nuevoReporte);

        // Lo guardamos en la memoria de la PC para que no se borre
        localStorage.setItem("reportes_sertransafal", JSON.stringify(this.base_de_datos));

        alert("¡Reporte guardado con éxito como: " + nuevoReporte.estado + "!");
        window.location.href = "index.html"; // Nos regresa al inicio
    }
}

// Creamos al administrador para que esté listo para trabajar
const taller = new GestionTaller();

// Función que activan los botones
function guardar(esFinalizado) {
    taller.guardarReporte(esFinalizado);
}