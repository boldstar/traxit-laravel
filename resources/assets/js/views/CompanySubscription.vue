<template>
    <div class="container">
        <router-link to="/subscriptions" class="btn btn-primary btn-sm">Go Back</router-link>
        <Modal v-if="modalState" :name="title" :alert="messageAlert" :id="subscriptionID" :subscription="true"/>
        <div class="card mt-3" v-if="subplan">
            <div class="card-header">
                <span>{{subplan.nickname}}</span>
            </div>
            <div class="card-body">
                <ul class="p-0 text-left font-weight-bold">
                    <li class="mb-3">Active: <input type="checkbox" v-model="subplan.active"></li>
                    <li>Amount: {{subplan.amount}}/{{subplan.interval}}</li>
                </ul>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-sm" v-if="subplan.active">Resume</button>
                <button class="btn btn-primary btn-sm" @click="requestToCancel">Cancel</button>
                <button class="btn btn-primary btn-sm">Upgrade</button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
import Modal from '@/components/Modal.vue'
    export default {
        name: 'CompanySubscription',
        components: {
            Modal
        },
        data() {
            return {
                messageAlert: 'Are you sure you would like to cancel the subscription?',
                subscriptionID: '',
                title: ''
            }
        },
        computed: {
          ...mapGetters(['subscription', 'modalState']),
          subplan() {
            return this.subscription.plan
          }  
        },
        methods: {
            requestToCancel() {
                this.$store.commit('showModal')
                this.subscriptionID = this.$route.params.subscription_id
                this.title = 'Cancel Subscription'
            },
            cancelSubscription() {

            },
            resumeSubscription() {

            } 
        },
        created() {
         this.$store.dispatch('getCompanySubscription', this.$route.params.subscription_id)
        }
    }
</script>
