(function($){
    MicroModal.init();
})(jQuery)




var checkout_form = jQuery('form.woocommerce-checkout');

checkout_form.on('checkout_place_order', function () {
    console.log("submitted")
    return false;
});









// var checkout_form = jQuery( 'form.woocommerce-checkout' );
// checkout_form.on("submit", function(){
//     // var selectedPaymentMethod = jQuery('form[name="checkout"] input[name="payment_method"]:checked').val();
//     console.log("hello")

// })


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
            //   completeCheckout();
            console.log("success from script");
            }
        })
        .on('success', txid => payment.pushTx(txid))
        .on('error', err => console.log(err))

    }



    function getSatoshiAmount(){

        const axios = window.axios;
        axios.get('https://api.coinranking.com/v2/coin/yhjMzLPhuIDl?referenceCurrencyUuid=VcMY11NONHSA0')
            .then(( response ) => {
                console.log( response )
        })
        .catch( (error) => {
            // handle error
            console.log(error);
          })

    }