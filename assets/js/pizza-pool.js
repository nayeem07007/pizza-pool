;
jQuery(function($){
    "use strict"
     $(document).on('change', '#pool-delivery-option', function(){
        jQuery('body').trigger('update_checkout');
     });
});
