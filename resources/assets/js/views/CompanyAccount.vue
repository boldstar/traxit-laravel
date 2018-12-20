<template>
    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-md-6">
                <router-link :to="{path: '/company/' +$route.params.uuid}" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between">
                        <span class="align-self-center font-weight-bold text-primary">
                            Account Details
                        </span> 
                        <div>
                            <router-link class="btn btn-sm btn-secondary mr-3" :to="{path: '/company/' +$route.params.uuid+'/add-account'}">Add Details</router-link>
                            <router-link class="btn btn-sm btn-primary" :to="{path: '/company/' +$route.params.uuid+'/edit-account'}">Edit</router-link>
                        </div>
                    </div>
                    <div class="p-0"> 
                        <div class="col-12" v-if="companyAccount">
                            <div class="p-4 d-flex justify-content-center" v-if="companyAccount <= 0">
                                <router-link class="btn btn-sm btn-secondary mr-3" :to="{path: '/company/' +$route.params.uuid+'/add-account'}">Add Details</router-link>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Business Name</span>
                                <span>{{companyAccount[0].business_name}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Address</span>
                                <span>{{companyAccount[0].address}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">City</span>
                                <span>{{companyAccount[0].city}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">State</span>
                                <span>{{companyAccount[0].state}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Postal Code</span>
                                <span>{{companyAccount[0].postal_code}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Phone Number</span>
                                <span>{{companyAccount[0].phone_number}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Fax Number</span>
                                <span>{{companyAccount[0].fax_number}}</span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span class="font-weight-bold">Subscription</span>
                                <span>{{companyAccount[0].subscription}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'

    export default {
        name: 'company',
        computed: {
          ...mapGetters(['companyAccount']),
          website() {
            return this.company.website
          }  
        },
        methods: {
            
        },
        created() {
         this.$store.dispatch('getCompanyAccount', this.$route.params.uuid)
        }
    }
</script>
