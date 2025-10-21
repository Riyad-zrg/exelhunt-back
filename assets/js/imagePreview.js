document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('upload-input');
    const previewEl = document.getElementById('preview');

    if (!input || !previewEl) return;

    input.addEventListener('input', function () {
        const file = input.files[0];
        if (!file) return;

        previewEl.innerHTML = '';

        const imgURL = window.URL.createObjectURL(file);
        const img = new Image();

        img.src = imgURL;

        img.addEventListener('load', function() {
            const width = img.width;
            const height = img.height;
            const ratio = width / height;

            if (Math.abs(ratio - 16/9) > 0.05) {
                previewEl.textContent = "❌ Veuillez ajouter une image au format 16:9";
                return;
            }

            img.className = "preview-image";
            previewEl.appendChild(img);

            console.log(`✅ Image OK : ${width}x${height}, ratio = ${ratio.toFixed(2)}`);
        });
    });
});
