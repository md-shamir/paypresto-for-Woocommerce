<template>
  <div id="rt-lightbox">
   <div>
  <b-button @click="openModal" v-b-modal.modal-center>Proceed to pay</b-button>

  <b-modal id="modal-center" centered title="Pay with Paypresto">
  <b-spinner class="spinner" v-if="spinner"></b-spinner>
    <div id="paypresto"></div>
  </b-modal>
</div>
  </div>
</template>

<script>
import {Presto, embed} from 'paypresto.js'
import axios from 'axios';

  export default {
    data() {
      return {
        spinner: true,
        name: '',
        nameState: null,
        submittedNames: []
      }
    },
    mounted(){
      // $("#place_order").on("click", function(e){
      //   e.preventDefault();

      //   console.log("order placed");
      // });
 
    },
    methods: {
    async openModal(){
        this.spinner = true;
        var formData = new FormData();
        formData.append('action', 'rt_checkout_process');
        formData.append('security', RT_FRONTEND.security);
        try{
        var response = await axios.post(RT_FRONTEND.ajaxURL, formData);
        var obj = response.data.data.cart_items;
            console.log(JSON.parse(obj));
          if( response.data.success ) {

            setTimeout(() => {
                
                const payment = Presto.create({
                key: '5KRBoTUQQtRwjJEPHz9MBou7UoMmUsb2afMSZR1QudF7UfctRSR',
                description: 'My test payment',
                outputs: [
                    { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: 1766 },
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
              .on('funded', payment => console.log(payment))
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



        // try {
        //   let response = await axios.post( 'http://localhost/wordpress/paypresto/index.php/wp-admin/admin-ajax.php',
        //   data
        //   );
        //   console.log(response);
        // } catch( erro ){
        //   console.log(erro);
        // }


        // this.spinner = true;
        //     setTimeout(() => {
        //       this.spinner = false;
              
        //       const payment = Presto.create({
        //       key: '5KRBoTUQQtRwjJEPHz9MBou7UoMmUsb2afMSZR1QudF7UfctRSR',
        //       description: 'My test payment',
        //       outputs: [
        //           { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: 500 },
        //           { data: [Buffer.from("Hello world!")] }
        //       ]
        //     })

        //     payment
        //     .mount(embed('#paypresto'))
        //     .on('funded', payment => payment.pushTx())
        //     .on('success', txid => console.log('TX sent', txid))
        //     .on('error', erro => console.log(erro));
        //     payment
        //     .on('invoice', invoice => console.log(invoice))
        //     .on('funded', payment => console.log(payment))
        //     .on('success', txid => payment.pushTx(txid))
        //     .on('error', err => console.log(err))
        //     }, 1000);
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

</style>
