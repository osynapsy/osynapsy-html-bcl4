const Bcl4Gallery = {
    init: function () {
        document.addEventListener('click', function (e) {
            if (!e.target.classList.contains('osy-bcl4-gallery-thumbnail')) {
                return;
            }

            let thumbnailElement = e.target;
            let galleryElement = thumbnailElement.closest('div.osy-bcl4-gallery');
            let fieldValues = thumbnailElement.dataset.fields ? JSON.parse(thumbnailElement.dataset.fields) : null;

            // Viewer image
            let viewer = galleryElement.querySelector('.osy-bcl4-gallery-viewer');
            if (viewer) {
                viewer.setAttribute('src', thumbnailElement.getAttribute('src'));
            }

            // Delete button
            let deleteBtn = galleryElement.querySelector('.osy-bcl4-gallery-image-delete');
            if (deleteBtn) {
                deleteBtn.setAttribute('data-action-parameters', thumbnailElement.dataset.id);
            }

            // Description button
            let descBtn = galleryElement.querySelector('.osy-bcl4-gallery-image-description');
            if (descBtn) {
                descBtn.setAttribute('data-action-parameters', thumbnailElement.dataset.id);
            }

            // Hidden image id
            let idField = galleryElement.querySelector('.osy-bcl4-gallery-image-id');
            if (idField) {
                idField.value = thumbnailElement.dataset.id;
            }

            // Extra fields
            if (!fieldValues) {
                return;
            }
            for (let fid in fieldValues) {
                if (fid !== 'id' && fid !== 'url') {
                    let field = galleryElement.querySelector('.modalImageViewer #' + fid);
                    if (field) {
                        field.value = fieldValues[fid];
                    }
                }
            }
        });
    }
};

if (window.Osynapsy) {
    Osynapsy.plugin.register('Bcl4Gallery', () => {
        Bcl4Gallery.init();
    });
}
