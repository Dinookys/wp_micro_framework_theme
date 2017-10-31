(function( $ ){
    $(document).ready(function(){
        var custom_uploader;

        $('body').on('click','[data-add=image]', function(e){
            e.preventDefault();
            self = $(this);
        
            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Escolher imagem',
                button: {
                    text: 'Escolher imagem'
                },
                multiple: false
            });
            custom_uploader.on('select', function() {
                custom_uploader.state().get('selection').map( function( attachment ) {                    
                    attachment = attachment.toJSON();
                    self.parent().find('img').length //Verifica se ja existe uma imagem
                    ? self.parent().find('img').attr('src', attachment.url )
                    : self.parent().find(self.data('container-preview')).append('<img style="max-width: 100%" src="'+ attachment.url +'" />');
                    self.parent().find('[name="'+ self.data('field') +'"]').val(attachment.id);
                });
            });
            custom_uploader.open();
        });

        $('body').on('click','[data-remove=image]', function(e){
            e.preventDefault();
            self = $(this);
            self.parent().find(self.data('container-preview')).find('img').remove();
            self.parent().find('[name="'+ self.data('field') +'"]').val('');
        });
        
    });
})(jQuery);