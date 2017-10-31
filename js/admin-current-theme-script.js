(function($){
    $(document).ready(function(){

        var pure_hash = window.location.hash;
        var hash = pure_hash.replace('!','');
        var pure_redirect = $('[name=_wp_http_referer]').val();

        if( hash ){
            $(hash).addClass('active').siblings().removeClass('active');
            $('[data-tab='+ hash +']').addClass('active').parent().siblings().find('a').removeClass('active');
            addHashfromRedirect( hash, pure_redirect );
        }

        $('[data-tab]').click( function( e ){            
            var target = $(this).data('tab');
            $(this).addClass('active').parent().siblings().find('a').removeClass('active');
            $(target).addClass('active').siblings().removeClass('active');            
            addHashfromRedirect( $(this).attr('href'), pure_redirect );
        });
        
        $('body').on('click','[data-ajax]', function( e ){
            e.preventDefault();
            var ajax_action = $(this).data('ajax');
            var container_target = $(this).data('container-target');
            var item_class = $(this).data('class-of-items');

            $.post(ajaxurl, {
                'action' : ajax_action,
                'key' : next_key(container_target, item_class)
            }).success( function( response ){
                $( container_target ).append( response );
            } ).fail( function( response ){
                alert( 'Falha ao tentar incluir o item' );
            } );

        });

        $('body').on('click','[data-type=remove]', function( e ){
            var parent = $(this).data('parent');

            if( parent ){
                $(this).parents( parent ).remove();
            }else{
                $(this).remove();
            }

        });

        $('[data-shortable]').sortable({
            cursor : 'move'
        });

        function next_key( container_target, item ){

            var items = $( container_target ).find( '[data-key]' );
            var next_key = 1;

            if( items.length > 0 ){

                var current_key = 0;

                $.each( items, function( key, item ){

                    if( current_key < $(item).data('key') ){
                        current_key = $(item).data('key');
                    }

                } );
                
                next_key = Number( current_key ) +1;
            }

            return next_key;
        }

        function addHashfromRedirect( hash, redirect ){
            var redirect = redirect.split("#");
            $('[name=_wp_http_referer]').val( ( redirect[0] ? redirect[0] : redirect ) + hash );
        }
    });
})(jQuery);