<template>
    <div class="container"  v-if="website">
        <Modal v-if="modalState" :name="companyName" :alert="messageAlert" :id="companyUUID" />
        <div class="row justify-content-center" v-if="$route.name == 'company'">
            <div class="col-md-6">
                <router-link to="/" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between" v-if="website">
                        <span class="align-self-center font-weight-bold text-primary">
                                {{ website[0].company }}
                        </span>
                        <div class="d-flex">
                            <router-link :to="'/company/' +website[0].uuid+ '/account'" class="btn btn-sm btn-outline-info  mr-3">Account Details</router-link>
                            <router-link class="btn btn-sm btn-primary mr-3" :to="{path: '/edit-company/' + website[0].uuid}">Edit</router-link>
                            <button class="btn btn-sm btn-secondary" @click="requestDelete">Delete</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                    <div>
                        <div class="col-12" v-if="website">
                            <div class="h4 px-2 my-3 font-weight-bold">
                                <span><i class="far fa-building mr-2"></i>Details</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mb-2  font-weight-bold">Company Email</span>
                                <span class="mb-2"> {{website[0].email}} </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mb-2  font-weight-bold">Company Number</span>
                                <span class="mb-2"> {{website[0].number}} </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mb-2  font-weight-bold">Database</span>
                                <span class="mb-2"> {{website[0].uuid}}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mb-2  font-weight-bold">Domain</span>
                                <span class="mb-2" v-if="website[0].hostnames"> {{website[0].hostnames[0].fqdn}}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mb-2  font-weight-bold">Stripe ID</span>
                                <span class="mb-2" v-if="website[0].hostnames"> {{website[0].hostnames[0].stripe_id}}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <div class="h4 px-2 my-3 font-weight-bold">
                                <span><i class="fas fa-users mr-2"></i>Users</span>
                            </div>
                            <ul class="p-0">
                                <li class="card-body border mb-3" v-for="user in company.users" :key="user.id">
                                <div class="d-flex justify-content-between">
                                    <span class="mb-2  font-weight-bold">Name</span>
                                    <span class="mb-2"> {{user.name}} </span>
                                </div> 
                                <div class="d-flex justify-content-between">
                                    <span class="font-weight-bold">Email</span>
                                    <span>{{user.email}}</span>    
                                </div>   
                                </li>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
import Modal from '@/components/Modal.vue'
    export default {
        name: 'company',
        components: {
            Modal
        },
        data() {
            return {
                companyName: '',
                messageAlert: 'Are you sure you would like to delete this company?',
                companyUUID: ''
            }
        },
        computed: {
          ...mapGetters(['company', 'modalState']),
          website() {
            return this.company.website
          }  
        },
        methods: {
            deleteCompany() {
                this.$store.dispatch('deleteCompany', this.$route.params.uuid)
                .then(response => {
                    this.$router.push('/')
                })
            },
            requestDelete() {
                this.companyName = this.company.website[0].company
                this.companyUUID = this.company.website[0].uuid
                this.$store.commit('showModal')
            } 
        },
        created() {
         this.$store.dispatch('getCompany', this.$route.params.uuid)
        }
    }
</script>
