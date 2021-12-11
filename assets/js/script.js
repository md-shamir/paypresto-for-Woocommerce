(function($){
    $("form.woocommerce-checkout").on("submit", function(){
        console.log("Hello")
    });
})(jQuery)



(function($){
    
    // usd uuid yhjMzLPhuIDl (bsv uuid: VcMY11NONHSA0)

    // $("form.woocommerce-checkout").

    // var settings = {
    //     "url": "https://api.coinranking.com/v2/coin/yhjMzLPhuIDl?referenceCurrencyUuid=VcMY11NONHSA0",
    //     "method": "GET",
    //     "headers": {
    //         "x-access-token": "coinrankingc00c85952937480718b01faee8ba1c7a9aba35fca9cd3dc4"
    //       },
    //     "timeout": 0,
    //   };
      
    //   $.ajax(settings).done(function (response) {
    //     console.log(response);
    //   });


    // const payment = Presto.create({
    //     key: self.payprestKey,
    //     description: self.title,
    //     outputs: [
    //         { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: formatSats },//(satoshi 100000000x1.00000 bsv)
    //         { data: [Buffer.from("Hello world!")] }
    //     ]
    //   })

    //   payment
    //   .mount(embed('#rt-paypresto'))
    //   .on('funded', payment => payment.pushTx())
    //   .on('success', txid => console.log('TX sent', txid))
    //   .on('error', erro => console.log(erro));
    //   payment
    //   .on('invoice', invoice => console.log(invoice))
    //   .on('funded', function(funded){
    //     if( funded ) {
    //     //   completeCheckout();
    //     console.log("success from script");
    //     }
    //   })
    //   .on('success', txid => payment.pushTx(txid))
    //   .on('error', err => console.log(err))





})(jQuery)