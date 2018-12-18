<template>
<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <span class="navbar-brand">TRAXIT</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto" v-if="loggedIn">
                <!-- Authentication Links -->
                <li class="nav-item mr-3">
                    <router-link to="/" class="nav-link"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</router-link>
                </li>
                <li class="nav-item mr-3">
                    <router-link to="/register" class="nav-link"><i class="far fa-building mr-2"></i>Add Company</router-link>
                </li>
                <li class="nav-item">
                    <button :disabled="loading" class="nav-link btn btn-light" type="button" @click="logout"><i class="fas fa-sign-out-alt mr-2"></i>
                    <span v-if="loading">Leaving</span>
                    <span v-if="!loading">Logout</span>
                    </button>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" v-if="!loggedIn">
                <li class="nav-item mr-3">
                    <router-link to="/login" class="nav-link">Login</router-link>
                </li>
            </ul>
        </div>
    </div>
</nav>
    
</template>

<script>
import {mapGetters} from 'vuex'
export default {
    name: 'navbar',
    computed: {
        ...mapGetters(['loggedIn', 'loading'])
    },
    methods: {
        logout() {
            this.$store.dispatch('logout')
        }
    }
}
</script>

