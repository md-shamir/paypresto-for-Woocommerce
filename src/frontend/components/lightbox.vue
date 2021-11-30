<template>
  <div id="rt-lightbox">
   <div>
  <b-button id="rt_place_order" @click="openModal" v-b-modal.modal-center>Proceed to pay</b-button>

  <b-modal id="modal-center" v-if="validated" hide-footer hide-header centered>
  <b-spinner class="spinner" v-if="spinner"></b-spinner>
    <div id="paypresto"></div>
  </b-modal>
</div>
  </div>
</template>

<script>
import {Presto, embed} from 'paypresto.js'
import axios from 'axios';
const Coinranking = require('coinranking-api');


  export default {
    data() {
      return {
        validated: false,
        title: '',
        description: '',
        payprestKey: '',
        coinRankingKey: '',       
        spinner: true,
        name: '',
        nameState: null,
        submittedNames: []
      }
    },
    mounted(){
    },
    methods: {
  
    async openModal(){
      var fname = $("#billing_first_name").val();
      var lname = $("#billing_last_name").val();
      var mail = jQuery("#billing_email").val();
      if( fname !== "" && lname !== "" && mail !== "" ) {
        this.validated = true;
      } else {
        $("form.woocommerce-checkout").prepend('<ul class="woocommerce-error"><li>Please fill out the required field(s)</li></ul>');
        $("html, body").animate({ scrollTop: 0 });
      }
      
      if( this.validated ) {
        var self = this;
        this.spinner = true;
        var formData = new FormData();
        formData.append('action', 'rt_checkout_process');
        formData.append('security', RT_FRONTEND.security);  
        try{
        var response = await axios.post(RT_FRONTEND.ajaxURL, formData);
        console.log(response);
          if( response.data.success ) {            
            var obj = response.data.data.cart_items;
            var gateway = response.data.data.gateway_info;
            var formatGateway = JSON.parse(gateway);
            self.title = formatGateway[0].title;
            self.description = formatGateway[0].description;
            self.payprestKey = formatGateway[0].paypresto_api;
            self.coinRankingKey = formatGateway[0].coin_ranking;
            var jsObj = JSON.parse(obj);
            var totalPrice = 0;
            jsObj.forEach(item => {
              totalPrice += parseFloat(item.price) * item.quantity;
            });
            const CoinrankingClient = new Coinranking();
            let convert = await CoinrankingClient.coins.fetch(1509, {base: 'BSV'}) // usd 1509
            let bsv = convert.data.data.coin.price * totalPrice;
            let sats = bsv * 100000000;
            let formatSats = Math.round(sats);
            setTimeout(() => {
                
                const payment = Presto.create({
                key: self.payprestKey,
                description: self.title,
                outputs: [
                    { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: formatSats },//(satoshi 100000000x1.00000 bsv)
                    { data: [Buffer.from("Hello world!")] }
                ]
              })

              payment
              .mount(embed('#paypresto'))
              .on('funded', payment => payment.pushTx())
              .on('success', txid => console.log('TX sent', txid))
              .on('error', erro => console.log(erro));
              payment
              .on('invoice', invoice => console.log(invoice))
              .on('funded', function(funded){
                if( funded ) {
                  completeCheckout();
                }
              })
              .on('success', txid => payment.pushTx(txid))
              .on('error', err => console.log(err))

              this.spinner = false;
              }, 1000);
          } else {
            console.log(response);
          }

        } catch( error ){
            console.log(error);
        }

      }
       
      },
      checkFormValidity() {
        const valid = this.$refs.form.checkValidity()
        this.nameState = valid
        return valid
      },
      resetModal() {
        this.name = ''
        this.nameState = null
      },
      handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault()
        // Trigger submit handler
        this.handleSubmit()
      },
      handleSubmit() {
        // Exit when the form isn't valid
        
        if (!this.checkFormValidity()) {
          return
        }
        // Push the name to submitted names
        this.submittedNames.push(this.name)
        // Hide the modal manually
        this.$nextTick(() => {
          this.$bvModal.hide('modal-prevent-closing')
        })
      }

    }
  }
  async function completeCheckout(){
    var email = jQuery("#billing_email").val();
    var firstNme = jQuery("#billing_first_name").val();
    var lastName = jQuery("#billing_last_name").val();
    var formData = new FormData();
        formData.append('action', 'rt_payment_process');
        formData.append('customer_email', email);
        formData.append('first_name', firstNme);
        formData.append('last_name', lastName);
        formData.append('security', RT_FRONTEND.security);
    var response = await axios.post(RT_FRONTEND.ajaxURL, formData);
        if( response.data.success ) {
          window.location.assign(response.data.data.redirect);
        }
  }
</script>
<style>
  span.spinner.spinner-border {
    margin: auto;
    display: block;
  }
  div.modal-dialog {
      width: 90%;
      margin: 1.75rem auto;
      max-width: 741px;
  }
  #rt_place_order {
    float: right;
    font-size: 20px;
    border: 2px solid;
    padding: 0.5em 1em;
    background: transparent;
    color: #2EA3F2;
  }
.payment_method_paypresto img {
  max-width: 200px;
  height: auto;
}

</style>

