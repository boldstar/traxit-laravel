
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//Imports
import Vue from 'vue'
import App from '@/App'
import router from '@/router.js'
import store from '@/store.js'


new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
