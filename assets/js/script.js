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
                        // console.log(response);
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
                            
                            processPayprestoCheckout();


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

async function processPayprestoCheckout(){
    console.log(RT_FRONTEND)
    MicroModal.show("rt-paypresto");
    const Presto = window.Paypresto.Presto;
        const embed = window.Paypresto.embed;
        var sats = 0;
        let cartTotal = $("#cart_total").val();
        var settings = {
            "url": "https://api.tonicpow.com/v1/rates/usd?amount=" + cartTotal,
            "method": "GET",
            "timeout": 0,
            "headers": {
              "api_key": RT_FRONTEND.tonic_pow
            },
          };
          
          await $.ajax(settings).done(function (response) {
            sats = response.price_in_satoshis;
          });
        //   console.log(sats);
        const payment = Presto.create({
        key: RT_FRONTEND.payprestoApiKey,
        description: RT_FRONTEND.title,
        outputs: [
            { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: sats },//(satoshi 100000000x1.00000 bsv)
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
                // MicroModal.close();
                // var checkout_form = $('form.woocommerce-checkout');
                // checkout_form.submit();
                // console.log('Payment success');
                var fields = {};
                var checkout_form = $('form.woocommerce-checkout');
                var formData = checkout_form.serializeArray();
                    $.each( formData, function(index, item){
                        fields[item.name] = item.value;
                    })
                $.ajax({
                    type : "POST",
                    dataType : "json",
                    url : RT_FRONTEND.ajaxURL,
                    data : {
                        action: "rt_payment_process",
                        form_data: fields
                    },
                    success: function( res ){
                        // console.log(res);
                        // if( res.data.message === 'sucess') {
                        //    setTimeout(() => {
                        //     // MicroModal.close();
                        //     var url = res.data.redirect;
                        //     $(location).attr('href', url);
                        //     window.location.assign(url);
                        //     window.location.href = url;
                        // }, 500);
                        // }
                        setTimeout(() => {
                            // MicroModal.close();
                            var url = res.data.redirect;
                            window.location.assign(url);
                        }, 500);
                    },
                    error: function( err ){
                        console.log(err)
                    }
                });

            }
        })
        .on('success', txid => payment.pushTx(txid))
        .on('error', err => console.log(err))

    }




 

// jQuery(document).ready(function(){
//     const bsv = $("#coinranking_price").val();
//     let sats = ( 100000000 / bsv );
//     let formatSats = sats.toString().slice(0, sats.toString().lastIndexOf('.'));

//     let productPrice = ( 357 * formatSats );
//     console.log(productPrice);
// });


// jQuery(document).ready(function(){
//     const bsv = $("#coinranking_price").val();
//     let cartTotal = $("#cart_total").val();
//     let sats = ( 100000000 / bsv );
//     let formatSats = sats.toString().slice(0, sats.toString().lastIndexOf('.'));

//     let productPrice = ( cartTotal * sats );
//     console.log(productPrice);
// });

