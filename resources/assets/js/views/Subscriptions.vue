<template>
    <div class="container">    
       <div class="card-body bg-white py-2 d-flex justify-content-between shadow">
           <h3 class="mb-0">Subscriptions</h3>
           <router-link to="/add-subscription" class="btn btn-sm btn-primary pt-2 font-weight-bold">Add Subscription</router-link>
       </div>
       <table class="table ">
            <thead class="border">
                <tr class="text-center ">
                <th scope="col">Name</th>
                <th scope="col">Card Brand</th>
                <th scope="col">Last Four</th>
                <th scope="col">Stripe Id</th>
                </tr>
            </thead>
            <tbody class="table-sm">
                <tr class="text-center table-bordered bg-white" v-for="(subscription, index) in subscriptions" :key="index">
                <th scope="row" class="text-capitalize">{{ subscription.subdomain }}</th>
                <td>{{ subscription.card_brand }}</td>
                <td>{{ subscription.card_last_four}}</td>
                <td>{{ subscription.stripe_id }}</td>
                </tr>
            </tbody>
        </table>
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


