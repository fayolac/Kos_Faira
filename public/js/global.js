// Fungsi preview foto tunggal
function previewFoto(input, previewId, placeholderId) {
    const file = input.files[0];
    if (!file) return;

    const preview     = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);

    if (file.type.startsWith('image/')) {
        preview.src           = URL.createObjectURL(file);
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    } else {
        // PDF — tampilkan nama file
        if (placeholder) {
            placeholder.textContent   = '📄 ' + file.name;
            placeholder.style.display = 'block';
        }
        preview.style.display = 'none';
    }
}

// Fungsi preview foto multiple (khusus foto kamar)
function previewFotoMultiple(input, previewWrapId, placeholderId) {
    const files       = Array.from(input.files);
    const previewWrap = document.getElementById(previewWrapId);
    const placeholder = document.getElementById(placeholderId);

    if (!files.length) return;

    if (placeholder) placeholder.style.display = 'none';
    if (previewWrap) {
        previewWrap.style.display = 'flex';
        previewWrap.innerHTML     = '';

        files.forEach(function(file) {
            if (!file.type.startsWith('image/')) return;
            const img       = document.createElement('img');
            img.src         = URL.createObjectURL(file);
            img.style.cssText = 'width:70px; height:55px; object-fit:cover; border-radius:5px; border:1px solid #ebebeb;';
            previewWrap.appendChild(img);
        });
    }
}