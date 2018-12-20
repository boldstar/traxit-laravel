<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Login
                    </div>

                    <div class="card-body">
                    <form @submit.prevent="login">
                        <input type="email" v-model="email" class="form-control mb-3" placeholder="Email">
                        <input type="password" v-model="password" class="form-control mb-3" placeholder="Password">
                        <div>
                            <button :disabled="loading" type="submit" class="btn btn-primary btn-block">
                                <span v-if="loading">Processing...</span>
                                <span v-if="!loading">Login</span>
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
    export default {
        name: 'login',
        data() {
            return {
                email: '',
                password: ''
            }
        },
        computed: {
            ...mapGetters(['loading'])
        },
        methods: {
            login() {
                this.$store.dispatch('login', {                    
                    email: this.email,
                    password: this.password
                }).then(response => {
                    this.password = ''
                }).catch(error => {
                    this.password = ''
                })
            }
        }
    }
</script>
