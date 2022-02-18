<template>
    <div id="wpvk-general-setting-tab" class="tab-container" style="visibility:hidden">
       <form action="">
            <div id="widget"></div>
            <h2>Hello world hello worlds</h2>
       </form>
        <div class="clear"></div>
    </div>
</template>

<script>
const Coinranking = require('coinranking-api');

export default {
    components: {
        
    },

    // data() {
    //     return {}
    // },

    mounted() {
        // this.getSatoshiAmount();
        const Coinranking = require('coinranking-api');
        const CoinrankingClient = new Coinranking();
        let data = CoinrankingClient.coins.all({});
        console.log(data);
    },

    computed: {

    },

    methods: {
        getSatoshiAmount(){
            const CoinrankingClient = new Coinranking();
           jQuery(document).on("click", function(){
                let convert = CoinrankingClient.coins.fetch(1509, {base: 'BSV'}) // usd 1509   yhjMzLPhuIDl - bsv VcMY11NONHSA0
                let bsv = convert.data.data.coin.price * totalPrice;
                let sats = bsv * 100000000;
                let formatSats = Math.round(sats);
                console.log(formatSats);
           });
        }

    }
}
</script>
