<template>
    <div class="subscription container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <router-link to="/subscriptions" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
                <div class="card-header p-0 font-weight-bold mb-3 p-3 border-primary d-flex justify-content-between h4">
                    <span>New Subscription</span>
                    <div>
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <form @submit.prevent="addSubscription" class="align-self-center col-md-12 p-0">
                    <input type="text" placeholder="Title" class="form-control mb-3" v-model="subscription.title">
                    <input type="decimal" placeholder="000.00" class="form-control mb-3" v-model="subscription.amount">
                    <select name="basis" id="basis" class="form-control mb-3" v-model="subscription.basis">
                        <option disabled>{{ option }}</option>
                        <option value="basis" v-for="(basis, index) in basises" :key="index">{{basis}}</option>
                    </select>
                    <input type="textarea" placeholder="Title" class="form-control mb-3" v-model="subscription.title">
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'subscription',
    data() {
        return {
            subscription: {
                title: '',
                amount: '',
                basis: '',
                description: ''
            },
            basises: ['yr', 'mth'],
            option: 'Choose...'
        }
    },
    methods: {
        addSubscription() {
            this.$store.dispatch('addSubscription', {
                title: this.subscription.title,
                amount: this.subscription.amount,
                basis: this.subscription.basis,
                description: this.subscription.description
            })
        }
    },
    created() {
        this.subscription.basis = this.option
    }
}
</script>

