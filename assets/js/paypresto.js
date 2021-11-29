(function($){


   $( document ).on("change", "input[name='payment_method']", function(){
       var parent = $(this).parent();
        if( $(parent).hasClass('payment_method_paypresto') ) {
            var proceedToPay = $('#wpvk-general-setting-tab').detach();
            $('.payment_box.payment_method_paypresto').append(proceedToPay); 
            $("#wpvk-general-setting-tab").css("visibility", "visible");       
        }
   });


})(jQuery);