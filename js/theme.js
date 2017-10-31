(function( $ ){
    $(document).ready(function(){
        $('[data-toggle=swipe-left]').click(function(e){
            e.preventDefault();
            var target = $(this).data('target');
            $(target).toggleClass('opened');
        });
    })
})(jQuery);