document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('upload-input');
    const label = document.getElementById('upload-label');

    if (!input || !label) return;

    input.addEventListener('input', function () {
        const file = input.files[0];
        if (!file) return;

        const imgURL = window.URL.createObjectURL(file);
        const img = new Image();
        img.src = imgURL;

        img.addEventListener('load', function() {
            const width = img.width;
            const height = img.height;
            const ratio = width / height;

            if (Math.abs(ratio - 16/9) > 0.05) {
                alert("❌ Veuillez ajouter une image au format 16:9");
                input.value = "";
                return;
            }

            label.innerHTML = "";
            img.className = "preview-image";
            label.appendChild(img);
        });
    });
});
