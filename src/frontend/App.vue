<template>
    <div id="wpvk-general-setting-tab" class="tab-container" style="visibility:hidden">
       <form action="">
            <div id="widget"></div>
       </form>
       <lightbox/>
        <div class="clear"></div>
    </div>
</template>


<script>
import {Presto, embed} from 'paypresto.js'
import Lightbox from './components/lightbox.vue';

export default {
    components: {
        Lightbox
    },

    // data() {
    //     return {}
    // },

    mounted() {
        // const payment = Presto.create({
        //     key: '5KRBoTUQQtRwjJEPHz9MBou7UoMmUsb2afMSZR1QudF7UfctRSR',
        //     description: 'My test payment',
        //     outputs: [
        //         { to: '1CBTGrChDDGsewF1eAV6FQyxRaSXRvUT7o', satoshis: 500 },
        //         { data: [Buffer.from("Hello world!")] }
        //     ]
        //     })

        //     payment
        //     .mount(embed('#widget'))
        //     .on('funded', payment => payment.pushTx())
        //     .on('success', txid => console.log('TX sent', txid))
        //     .on('error', erro => console.log(erro));
            // payment
            // .on('invoice', invoice => console.log(invoice))
            // .on('funded', payment => console.log(payment))
            // .on('success', txid => payment.pushTx(txid))
            // .on('error', err => console.log(err))

    },

    // computed: {
    //     ...mapGetters([ 'GET_GENERAL_SETTINGS', 'GET_LOADING_TEXT' ]),

    //     formData: {
    //         get() {
    //             return this.GET_GENERAL_SETTINGS
    //         },
    //     },

    //     loadingText: {
    //         get() {
    //             return this.GET_LOADING_TEXT
    //         }
    //     }
    // },

    methods: {
        

    }
}
</script>

<style>

</style>