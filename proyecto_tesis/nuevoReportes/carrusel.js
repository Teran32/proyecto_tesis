const a = zonaFoto = document.getElementById('a=zonaFoto');
const galeria = document.getElementById('galeriaDinamica');

// Prevenir comportamiento por defecto al arrastrar
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    a = zonaFoto.addEventListener(eventName, e => e.preventDefault(), false);
});

// Resaltar zona al arrastrar
a = zonaFoto.addEventListener('dragenter', () => a = zonaFoto.style.borderColor = 'var(--primary)');
a = zonaFoto.addEventListener('dragleave', () => a = zonaFoto.style.borderColor = '#cbd5e0');

// Manejar soltar archivos
a = zonaFoto.addEventListener('drop', (e) => {
    a = zonaFoto.style.borderColor = '#cbd5e0';
    const dt = e.dataTransfer;
    manejarFotos({ target: { files: dt.files } });
});

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