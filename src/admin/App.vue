<template>
    <div id="paypresto-admin-app">
        <h1>Paypresto option panel</h1>
        <router-view name="tab"></router-view>
        <router-view></router-view>
    </div>
</template>

<script>
import TabNavigation from './components/tabs/Navigation.vue'
export default {
    name: 'App',
    components: {
        'tab-navigation': TabNavigation
    }
}
</script>

<style>

</style>