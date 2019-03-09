<template>
    <div class="app">
        <Navbar />
        <Login class="mt-5" v-if="!loggedIn" />
        <div class="mt-5" v-if="loggedIn">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import Navbar from '@/components/Navbar.vue'
import Login from '@/views/Login.vue'
import {mapGetters} from 'vuex'

export default {
    name: 'App',
    components: {
        Navbar,
        Login
    },
    computed: {
        ...mapGetters(['loggedIn'])
    }
}
</script>

<style>
    li {
        list-style: none;
    }

    .subscription-card {
        height: 275px;
    }
    .spacer {
        margin-bottom: 24px;
    }
    /**
        * The CSS shown here will not be introduced in the Quickstart guide, but shows
        * how you can use CSS to style your Element's container.
        */
    .StripeElement {
        background-color: white;
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid #ccd0d2;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
    #card-errors {
        color: #fa755a;
    }
</style>


