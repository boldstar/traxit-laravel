<template>
    <div class="container">    
       Subscriptions go here...
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


