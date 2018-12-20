<template>
    <div class="subscription container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <router-link :to="{path: '/company/' +$route.params.uuid+'/account'}" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
                <div class="card-header p-0 font-weight-bold p-3 border-primary d-flex justify-content-between h4">
                    <span>Account Form</span>
                    <div>
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <form @submit.prevent="addAccount" class="align-self-center col-md-12 p-3 border">
                    <div>
                        <input type="text" class="form-control mb-3" v-model="account.subscription" placeholder="Subscription">
                        <input type="text" class="form-control mb-3" v-model="account.business_name" placeholder="Business Name">
                        <input type="text" class="form-control mb-3" v-model="account.email" placeholder="Email">
                        <input type="text" class="form-control mb-3" v-model="account.phone_number" placeholder="Phone Number">
                        <input type="text" class="form-control mb-3" v-model="account.fax_number" placeholder="Fax Number">
                        <input type="text" class="form-control mb-3" v-model="account.address" placeholder="Address">
                        <input type="text" class="form-control mb-3" v-model="account.city" placeholder="City">
                        <input type="text" class="form-control mb-3" v-model="account.state" placeholder="State">
                        <input type="text" class="form-control mb-3" v-model="account.postal_code" placeholder="Postal Code">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form> 
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'account-form',
    data() {
        return {
           account: {
               subscription: '',
               business_name: '',
               email: '',
               phone_number: '',
               fax_number: '',
               address: '',
               city: '',
               state: '',
               postal_code: ''
           }
        }
    },
    methods: {
        addAccount() {
            this.$store.dispatch('addCompanyAccount', {
               subscription: this.account.subscription,
               business_name: this.account.business_name,
               email: this.account.email,
               phone_number: this.account.phone_number,
               fax_number: this.account.fax_number,
               address: this.account.address,
               city: this.account.city,
               state: this.account.state,
               postal_code: this.account.postal_code
            })
            .then(response => {
                this.$router.go(-1)
            })
        }
    },
}
</script>

