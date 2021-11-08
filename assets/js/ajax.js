(function( $ ){
    var email = "";
    email = $("#billing_email").val();
    if( email ) {
        let firstName = $("#billing_first_name").val();
        let lastName = $("#billing_last_name").val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ABC_FRONTEND.ajaxURL,
            data: {
                action: 'abc_save_checkout_information',
                first_name: firstName,
                last_name: lastName,
                email: email
            },
            success: function( response ){
                console.log(response);
            },
            error: function( error ){
                console.log(error);
            }
        });
        
    } else {
        
        $("#billing_email").on("keyup", function(){
            
            let isEmail = validateEmail($(this).val());
            if( isEmail ) {
               setTimeout(() => {
                let firstName = $("#billing_first_name").val();
                let lastName = $("#billing_last_name").val();
                let email = $(this).val();
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ABC_FRONTEND.ajaxURL,
                    data: {
                        action: 'abc_save_checkout_information',
                        first_name: firstName,
                        last_name: lastName,
                        email: email
                    },
                    success: function( response ){
                        console.log(response);
                    },
                    error: function( error ){
                        console.log(error);
                    }
                });
               }, 2000);
            }
            
            function validateEmail($email) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                return emailReg.test( $email );
            }
        });  
    }


    $("#billing_email").on("keyup", function(){
            
        let isEmail = validateEmail($(this).val());
        if( isEmail ) {
            setTimeout(() => {
                let firstName = $("#billing_first_name").val();
                let lastName = $("#billing_last_name").val();

                let email = $(this).val();
                console.log(email);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ABC_FRONTEND.ajaxURL,
                    data: {
                        action: 'abc_save_checkout_information',
                        first_name: firstName,
                        last_name: lastName,
                        email: email
                    },
                    success: function( response ){
                        console.log(response);
                    },
                    error: function( error ){
                        console.log(error);
                    }
                });
            }, 2000);
        }
        
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( $email );
        }

        
    }); 


})(jQuery);