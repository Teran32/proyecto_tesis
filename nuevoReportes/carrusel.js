
const zonaFoto = document.getElementById('zonaFoto');
const galeria = document.getElementById('galeriaDinamica');

if (zonaFoto) {

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        zonaFoto.addEventListener(eventName, e => e.preventDefault(), false);
    });

    zonaFoto.addEventListener('dragenter', () => {
        zonaFoto.style.borderColor = 'var(--primary)';
    });

    zonaFoto.addEventListener('dragleave', () => {
        zonaFoto.style.borderColor = '#cbd5e0';
    });

    zonaFoto.addEventListener('drop', (e) => {
        zonaFoto.style.borderColor = '#cbd5e0';
        const dt = e.dataTransfer;
        manejarFotos({ target: { files: dt.files } });
    });

} else {
    console.error("No se encontró el elemento con ID 'zonaFoto'");
}

function manejarFotos(event) {
    const archivos = event.target.files;
    const placeholder = document.getElementById('placeholderTexto');

    if (archivos.length > 0 && placeholder) placeholder.remove();

    Array.from(archivos).forEach(archivo => {
        if (!archivo.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            const card = document.createElement('div');
            card.className = 'foto-card';
            card.innerHTML = `
                <img src="${e.target.result}" onclick="ampliarFoto('${e.target.result}')">
                <button class="btn-borrar-pro" onclick="this.parentElement.remove()">✕</button>
            `;
            galeria.appendChild(card);
        };
        reader.readAsDataURL(archivo);
    });
}


function ampliarFoto(src) {
    const modal = document.getElementById('modalVisor');
    document.getElementById('imgGrande').src = src;
    modal.style.display = 'flex';
}

function cerrarVisor() {
    document.getElementById('modalVisor').style.display = 'none';
}