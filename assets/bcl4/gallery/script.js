Bcl4Gallery = 
{
    init : function()
    {        
        $(document).on('click', '.osy-bcl4-gallery .osy-bcl4-gallery-thumbnail', function(e) {
            let thumbnailElement = e.target;            
            let galleryElement = $(thumbnailElement).closest('div.osy-bcl4-gallery');
            let fieldValues = $(thumbnailElement).data('fields');            
            $('.osy-bcl4-gallery-viewer', galleryElement).attr('src', $(thumbnailElement).attr('src')); 
            $('.osy-bcl4-gallery-image-delete', galleryElement).attr('data-action-parameters', $(thumbnailElement).data('id')); 
            $('.osy-bcl4-gallery-image-description').attr('data-action-parameters', $(thumbnailElement).data('id')); 
            $('.osy-bcl4-gallery-image-id').val($(thumbnailElement).data('id'));            
            if (!fieldValues) {
                return;
            }
            for (fid in fieldValues) {                    
                if (fid  !== 'id' && fid !== 'url') {
                    let field = $('.modalImageViewer #' + fid, galleryElement);
                    if (field) {
                        $(field).val(fieldValues[fid]);
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