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
</style>


