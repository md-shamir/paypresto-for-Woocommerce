(function($){

    // $("input[name='payment_method']").parent().each(function(index, item){
    //     if( $(item).hasClass("payment_method_paypresto")) {
    //         var proceedToPay = $('#wpvk-general-setting-tab').detach();
    //         $('.place-order').append(proceedToPay); 
    //         $("#wpvk-general-setting-tab").css("visibility", "visible");
    //         $("#rt_place_order").css("visibility", "visible");
    //         $("#place_order").css("display", "none");    
    //     } else {
    //         $("#place_order").css("display", "block");
    //         $("#rt_place_order").css("visibility", "hidden");
    //     }
    // });



   $( document ).on("change", "input[name='payment_method']", function(){
       var parent = $(this).parent();
        if( $(parent).hasClass('payment_method_paypresto') ) {
            var proceedToPay = $('#wpvk-general-setting-tab').detach();
            $('.place-order').append(proceedToPay); 
            $("#wpvk-general-setting-tab").css("visibility", "visible");
            $("#rt_place_order").css("visibility", "visible");
            $("#place_order").css("display", "none");    
        } else {
            $("#place_order").css("display", "block");
            $("#rt_place_order").css("visibility", "hidden");
        }
   });


})(jQuery);