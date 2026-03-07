class GestionTaller {
    constructor() {
        this.base_de_datos = JSON.parse(localStorage.getItem("reportes_sertransafal")) || [];
        this.fotosTemp = []; // Guarda fotos en la memoria temporal antes de guardar
    }

    // Selecciona fotos del dispositivo y las convierte en texto (Base64) para guardar
    previsualizarFotosExtra(event) {
        const archivos = event.target.files;
        const contenedor = document.getElementById('vista_previa_fotos');

        if (!archivos || archivos.length === 0) return;

        for (let img of archivos) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const base64 = e.target.result;
                this.fotosTemp.push(base64);
                contenedor.innerHTML += `<img src="${base64}" alt="Evidencia de Taller">`;
            };
            reader.readAsDataURL(img);
        }
    }

    guardarReporte(esFinalizado) {
        const id_existente = document.getElementById('reporte_id')?.value;
        const id_final = id_existente || "REP-" + Date.now();

        const datos = {
            id: id_final,
            fecha_entrada: document.getElementById('fecha_entrada')?.value || "",
            fecha_salida: document.getElementById('fecha_salida')?.value || "",
            placa: document.getElementById('placa')?.value || "",
            vehiculo_marca: document.getElementById('vehiculo_marca')?.value || "",
            modelo: document.getElementById('Modelo')?.value || "",
            km_actual: document.getElementById('km_actual')?.value || "",
            proximo_km: document.getElementById('proximo_km')?.value || "",
            falla_detectada: document.getElementById('falla_detectada')?.value || "",
            trabajo_realizado: document.getElementById('trabajo_realizado')?.value || "",
            repuestos: document.getElementById('repuestos')?.value || "",
            pedido_repuestos: document.getElementById('pedido_repuestos')?.value || "",
            estado: esFinalizado ? "Finalizado" : "En Proceso",
            fotos: this.fotosTemp
        };

        if (id_existente) {
            // Actualizando uno que ya existe
            const index = this.base_de_datos.findIndex(r => r.id === id_final);
            if (index !== -1) {
                // Conservamos fotos viejas y le sumamos las nuevas
                if (this.base_de_datos[index].fotos) {
                    datos.fotos = [...this.base_de_datos[index].fotos, ...this.fotosTemp];
                }
                this.base_de_datos[index] = datos;
            }
        } else {
            // Guardando uno nuevo desde "nuevo_reporte.html"
            this.base_de_datos.push(datos);
        }

        localStorage.setItem("reportes_sertransafal", JSON.stringify(this.base_de_datos));
        alert("¡Reporte marcado como " + datos.estado + "!");

        // Redirigir al lugar correcto
        if (id_existente) {
            window.location.href = "../finalizados/lista_reportes.html?filtro=" + (esFinalizado ? 'finalizado' : 'proceso');
        } else {
            window.location.href = "../InterfazPrincipal.html";
        }
    }

    actualizarReporte(esFinalizado) {
        // Un simple alias semántico para no confundir funciones en HTML
        this.guardarReporte(esFinalizado);
    }

    mostrarEnLista(filtro) {
        const contenedor = document.getElementById('contenedor_tarjetas');
        if (!contenedor) return;

        const filtrados = this.base_de_datos.filter(r =>
            filtro === 'finalizado' ? r.estado === "Finalizado" : r.estado === "En Proceso"
        );

        contenedor.innerHTML = "";

        if (filtrados.length === 0) {
            contenedor.innerHTML = `<div style="text-align:center; padding: 40px; color:#94a3b8; grid-column: 1 / -1;">No hay vehículos en esta categoría.</div>`;
            return;
        }

        // Título dinámico
        const titulo = document.getElementById('titulo_seccion');
        if (titulo) {
            titulo.innerText = filtro === 'finalizado' ? "Historial de Vehículos Listos" : "Vehículos Actualmente en Proceso";
        }

        filtrados.forEach(repo => {
            const urlDestino = repo.estado === "En Proceso"
                ? `../enProceso/editar_reporte.html?id=${repo.id}`
                : `../finalizados/ver_reporte.html?id=${repo.id}`;

            contenedor.innerHTML += `
                <div class="tarjeta_form" style="cursor:pointer;" onclick="location.href='${urlDestino}'">
                    <p style="margin:5px 0;"><b>🚗 Placa:</b> ${repo.placa}</p>
                    <p style="margin:5px 0;"><b>🔧 Unidad:</b> ${repo.vehiculo_marca || ''} ${repo.modelo || ''}</p>
                    <p style="margin:5px 0; color: ${repo.estado === 'Finalizado' ? '#16a34a' : '#ca8a04'}; font-weight: bold;">
                        ◉ ${repo.estado}
                    </p>
                </div>
            `;
        });
    }

    cargarReporteParaEdicion(id) {
        const reporte = this.base_de_datos.find(r => r.id === id);
        if (!reporte) {
            alert("No se encontró la información del vehículo.");
            return;
        }

        const textoTitulo = document.getElementById('num_reporte');
        if (textoTitulo) textoTitulo.innerText = `No. ${reporte.id}`;

        const ocultoId = document.getElementById('reporte_id');
        if (ocultoId) ocultoId.value = reporte.id;

        const campos = ['fecha_entrada', 'fecha_salida', 'placa', 'vehiculo_marca', 'km_actual', 'proximo_km', 'falla_detectada', 'trabajo_realizado', 'repuestos', 'pedido_repuestos'];
        campos.forEach(campo => {
            if (document.getElementById(campo) && reporte[campo]) {
                document.getElementById(campo).value = reporte[campo];
            }
        });

        if (document.getElementById('Modelo') && reporte.modelo) {
            document.getElementById('Modelo').value = reporte.modelo;
        }

        const contenedorFotos = document.getElementById('vista_previa_fotos');
        if (contenedorFotos && reporte.fotos) {
            contenedorFotos.innerHTML = "";
            reporte.fotos.forEach(base64 => {
                contenedorFotos.innerHTML += `<img src="${base64}" alt="Evidencia Guardada">`;
            });
        }
    }

    generarPDF() {
        // Asegurarnos de que jsPDF este disponible
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Obtener el ID del reporte actual desde el HTML
        const params = new URLSearchParams(window.location.search);
        const reporteId = params.get('id');
        const reporte = this.base_de_datos.find(r => r.id === reporteId);

        if (!reporte) {
            alert("Error: No se encontró el reporte para generar el PDF.");
            return;
        }

        // ================= ENCABEZADO =================
        doc.setFontSize(16);
        doc.setFont("helvetica", "bold");
        doc.text("REPORTE DE MANTENIMIENTO VEHICULAR", 105, 20, { align: "center" });

        doc.setFontSize(10);
        doc.setFont("helvetica", "normal");
        doc.text("Empresa: Sertransafal C.A. - Valencia, Carabobo", 105, 28, { align: "center" });
        doc.text(`Identificador: ${reporte.id}`, 105, 34, { align: "center" });

        // Linea divisoria superior
        doc.setLineWidth(0.5);
        doc.line(14, 38, 196, 38);

        // ================= TABLA UNIFICADA =================
        doc.autoTable({
            startY: 45,
            head: [['Campo', 'Información']],
            body: [
                ['Fecha Entrada/Salida', `${reporte.fecha_entrada ? new Date(reporte.fecha_entrada).toLocaleString() : 'N/A'} - ${reporte.fecha_salida ? new Date(reporte.fecha_salida).toLocaleString() : 'N/A'}`],
                ['Placa del Vehículo', reporte.placa || 'N/A'],
                ['Unidad / Modelo', `${reporte.vehiculo_marca || ''} ${reporte.modelo || ''}`],
                ['Kilometraje Testeado', reporte.km_actual ? `${reporte.km_actual} KM` : 'N/A'],
                ['Próximo Kilometraje', reporte.proximo_km ? `${reporte.proximo_km} KM` : 'N/A'],
                ['Repuestos Suministrados', reporte.repuestos || 'N/A'],
                ['Falla Detectada', reporte.falla_detectada || 'N/A'],
                ['Trabajo Realizado', reporte.trabajo_realizado || 'N/A'],
                ['Pedidos Pendientes', reporte.pedido_repuestos || 'N/A']
            ],
            theme: 'striped',
            headStyles: { fillColor: [44, 62, 80], textColor: 255, fontStyle: 'bold' }, // Azul marino institucional
            styles: { overflow: 'linebreak', fontSize: 10, cellPadding: 6 },
            columnStyles: {
                0: { cellWidth: 50, fontStyle: 'bold', fillColor: [248, 250, 252] }, // Gris claro
                1: { cellWidth: 'auto' }
            }
        });

        // ================= IMÁGENES (EVIDENCIA) =================
        if (reporte.fotos && reporte.fotos.length > 0) {
            doc.addPage();
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.text("Anexo: Galería de Inspección", 105, 20, { align: "center" });

            let yActual = 30; // Posicion Y inicial para fotos

            reporte.fotos.forEach((fotoBase64, index) => {
                if (yActual > 220) {
                    doc.addPage();
                    yActual = 20;
                }

                try {
                    doc.addImage(fotoBase64, 'JPEG', 55, yActual, 100, 75);
                } catch (e) {
                    console.error("No se pudo adjuntar la imagen " + index);
                }

                yActual += 85;
            });
        }

        // ================= GUARDADO =================
        const nombreFecha = new Date().toLocaleDateString().replace(/\//g, "-");
        const placaSegura = reporte.placa ? reporte.placa.replace(/\s+/g, '') : "Vehiculo";
        doc.save(`Reporte_${placaSegura}_${nombreFecha}.pdf`);
    }
}

const taller = new GestionTaller();

// Actualiza automáticamentes los números en el inicio
window.addEventListener('DOMContentLoaded', () => {
    const total_proceso = document.getElementById('total_proceso');
    const total_finalizado = document.getElementById('total_finalizado');

    if (total_proceso && total_finalizado) {
        const db = JSON.parse(localStorage.getItem("reportes_sertransafal")) || [];
        total_proceso.innerText = db.filter(r => r.estado === "En Proceso").length;
        total_finalizado.innerText = db.filter(r => r.estado === "Finalizado").length;
    }
});