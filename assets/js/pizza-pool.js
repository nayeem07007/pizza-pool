;
jQuery(function($){
    "use strict"
    
     $(document).on('change', '#airport_pickup', function(){
        jQuery('body').trigger('update_checkout');
     });
    
});
