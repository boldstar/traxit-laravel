<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <Alert :message="successAlert" v-if="successAlert" />
                <div class="card card-default">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body p-0">
                       <companies v-if="companies" :companies="companies"></companies>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Companies from '@/components/Companies.vue'
import Alert from '@/components/Alert.vue'
import {mapGetters} from 'vuex'
    export default {
        name: 'dashboard',
        components: {
            Companies,
            Alert
        },
        computed: {
            ...mapGetters(['companies', 'successAlert'])
        },
        created() {
           this.$store.dispatch('getCompanies') 
        }
    }
</script>
