<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Add Company
                    </div>

                    <div class="card-body">
                    <form @submit.prevent="addCompany">
                        <div class="p-2">
                            <span class="font-weight-bold">Company</span>
                        </div>
                        <input type="text" v-model="company.company" class="form-control mb-3" placeholder="Company Name">
                        <input type="text" v-model="company.company_email" class="form-control mb-3" placeholder="Company Email">
                        <input type="text" v-model="company.company_number" class="form-control mb-3" placeholder="Company Number">
                        <div class="d-flex">
                        <input type="text" v-model="company.fqdn" class="form-control mb-3 col-8" placeholder="FQDN">
                        <span class="col-4 align-self-center">.multitenant-diy.test</span>
                        </div>
                        <div class="p-2">
                            <span class="font-weight-bold">Admin User</span>
                        </div>
                        <input type="text" v-model="company.name" class="form-control mb-3" placeholder="User Name">
                        <input type="email" v-model="company.email" class="form-control mb-3" placeholder="User Email">
                        <input type="password" v-model="company.password" class="form-control mb-3" placeholder="Password">
                        <input type="password" v-model="company.password_confirmation" class="form-control mb-3" placeholder="Confirm Password">
                        <div>
                            <button :disabled="loading" type="submit" class="btn btn-primary btn-block">
                                <span v-if="loading">Processing....</span>
                                <span v-if="!loading">Submit</span>
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
        name:'register',
        data(){
            return {
                company: {
                    company: '',
                    company_email: '',
                    company_number: '',
                    name: '',
                    email: '',
                    fqdn: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },
        computed: {
            ...mapGetters(['loading'])
        },
        methods: {
            addCompany() {
            this.$store.dispatch('addCompany', {
                company: this.company.company,
                company_email: this.company.company_email,
                company_number: this.company.company_number,
                name: this.company.name,
                email: this.company.email,
                fqdn: this.company.fqdn,
                password: this.company.password,
                password_confirmation: this.company.password_confirmation
            })
            }
        }
    }
</script>
