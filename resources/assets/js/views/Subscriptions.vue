<template>
    <div class="container">    
        <Alert :message="successAlert" v-if="successAlert" />
        <div class="card-header border-primary d-flex justify-content-between mb-3">
            <span class="align-self-center">Subscriptions</span>
            <router-link class="btn btn-sm btn-primary" to="/add-subscription">Add Subscription</router-link>
        </div>
        <div class="d-flex flex-row justify-content-around flex-wrap p-0 col-md-12">
            <div class="card col-3 p-0 d-flex flex-colum subscription-card" v-for="subscription in subscriptions" :key="subscription.id">
                <div class="flex-column d-flex align-items-center">

                        <span class="p-4 h3">{{ subscription.title }}</span>
                        <span class="p-2 h3">${{ subscription.amount}}/{{ subscription.basis }}</span>
                        <span class="p-4 h6">{{ subscription.description }}</span> 
                </div>
                   
                    
                    <div class="card-footer d-flex justify-content-between p-3">
                        <button class="btn btn-sm btn-light" @click="deleteSubscription(subscription.id)">Delete</button>
                        <router-link class="btn btn-sm btn-primary" to="#">Edit</router-link>    
                    </div>     
                </div>
           
        </div>
    </div>
</template>

<script>
import Alert from '@/components/Alert.vue'
import {mapGetters} from 'vuex'

    export default {
        name: 'subscriptions',
        components: {
            Alert
        },
        computed: {
            ...mapGetters(['subscriptions', 'successAlert'])
        },
        methods: {
            deleteSubscription(id) {
                this.$store.dispatch('deleteSubscription', id)
            }
        },
        created() {
           this.$store.dispatch('getSubscriptions') 
        }
    }
</script>


