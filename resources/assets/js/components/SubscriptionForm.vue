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
                   <!-- add subscription form fields here -->
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
    name: 'subscription-form',
    data() {
        return {
            subscription: {
               //add vmodel data here
            },

            option: 'Choose...'
        }
    },
    methods: {
        addSubscription() {
            this.$store.dispatch('addSubscription', {
               //send data to store for submisssion
            })
        }
    },
    created() {
      //set select fields to this.option
    }
}
</script>

