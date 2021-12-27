(function($){
    MicroModal.init();
})(jQuery)

var $ = jQuery;


var checkout_form = $('form.woocommerce-checkout');
checkout_form.on('checkout_place_order', function () {
var selectedPaymentMethod = jQuery('form[name="checkout"] input[name="payment_method"]:checked').val();
    if( selectedPaymentMethod === 'paypresto' ) {

        var errors = new Array();
        var html = '';
        html += '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">';
            html+='<ul class="woocommerce-error">';
        var formRow = $(this).find("p.form-row");
        $(formRow).each(function(index, item){
            if( $(item).hasClass("validate-required") ) {
                var inputText = $(item).find('input[type=text], input[type=email], input[type=tel], input[type=number], select');
                if( $(inputText).attr("id") !== 'billing_email') {
                    if( $(inputText).val() === "" ) {
                        var field = $(inputText).attr("id");
                        var formatUnderscore = field.replaceAll('_', ' ');
                        errors.push({id:field, text:formatUnderscore});
                    }
                } else {
                    if( $(inputText).val() == "" ) {
                        var emailId = $(inputText).attr("id");
                        var mailFormatUnderscore = emailId.replaceAll('_', ' ');
                        errors.push({id : field, text : mailFormatUnderscore});// console.group(mailFormatUnderscore)
                    }
                    if( $(inputText).val() !== "" && !isEmail($(inputText).val()) ) {
                        errors.push({id : 'email-validation', text : 'Email address'});
                    }
                }
                
            }
        });
        $(".woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout").remove();
            if( errors.length >= 1 ) {
                for( var i=0; i < errors.length; i++ ){
                    if( errors[i].id === 'email-validation' ) {
                        html += '<li id="'+errors[i].id+'"><strong>'+errors[i].text+'</strong> is not valid</li>';
                    } else {
                        html += '<li id="'+errors[i].id+'"><strong>'+errors[i].text+'</strong> is required</li>';
                    }
                }
            checkout_form.prepend(html);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            } else {
                var email = $("#billing_email").val();
                $.ajax({
                    type : "GET",
                    dataType : "json",
                    async: true,
                    url : RT_FRONTEND.ajaxURL,
                    data : {
                        action: "rt_validate_user_email",
                        email: email
                    },
                    success: function(response) {
                        // verify email address
                        console.log(response);
                        if( response ) {
                            var html = '';
                            html += '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">';
                                html+='<ul class="woocommerce-error">';
                                html += '<li>An account is already registered with your email address <a href="'+response.data.login+'">Please login</a></li>';
                                html +='</ul>';
                            html += '</div>';
                            checkout_form.prepend(html);
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        } else {
                            // process checkout 
                            
                            // processPayprestoCheckout();


                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });


            }

            html += '</ul>';
        html +='</div>';

        return false;


    }    

});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

function processPayprestoCheckout(){
    MicroModal.show("rt-paypresto");
    const Presto = window.Paypresto.Presto;
        const embed = window.Paypresto.embed;
    
        const payment = Presto.create({
        key: '5KRBoTUQQtRwjJEPHz9MBou7UoMmUsb2afMSZR1QudF7UfctRSR',
        description: 'payment gateway title',
        outputs: [
            { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: 5000 },//(satoshi 100000000x1.00000 bsv)
            // { data: [Buffer.from("Hello world!")] }
        ]
      })

        payment
        .mount(embed('#paypresto_widget'))
        .on('funded', payment => payment.pushTx())
        .on('success', txid => console.log('TX sent', txid))
        .on('error', erro => console.log(erro));
        payment
        .on('invoice', invoice => console.log(invoice))
        .on('funded', function(funded){
            if( funded ) {
            
                // $.ajax({
                //     type : "POST",
                //     dataType : "json",
                //     url : RT_FRONTEND.ajaxURL,
                //     data : {
                //         action: "rt_payment_process",
                //     },
                //     success: function( res ){
                //         console.log( res );
                //     },
                //     error: function( err ){
                //         console.log(err)
                //     }
                // });




            }
        })
        .on('success', txid => payment.pushTx(txid))
        .on('error', err => console.log(err))

    }



    function getSatoshiAmount(){

        // const axios = window.axios;
        // axios.get('https://api.coinranking.com/v2/coin/yhjMzLPhuIDl?referenceCurrencyUuid=VcMY11NONHSA0')
        //     .then(( response ) => {
        //         console.log( response )
        // })
        // .catch( (error) => {
        //     // handle error
        //     console.log(error);
        //   })



    }




    processPayprestoCheckout();