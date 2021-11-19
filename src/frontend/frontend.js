import Vue from 'vue'
import Vuex from 'vuex'
import store from '../store/index'
import App from './App.vue'

import BootstrapVue from 'bootstrap-vue'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-vue/dist/bootstrap-vue.css"

Vue.use( Vuex )
Vue.use( BootstrapVue );
new Vue({
    el: '#rt-frontend-app',
    store,
    render: h => h( App )
})