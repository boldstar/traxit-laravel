<template>
    <div class="container"  v-if="companyAccount">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <router-link :to="{path: '/company/' + $route.params.uuid+'/account'}" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
               <div class="card card-default">
                    <div class="card-header">
                        Edit Account
                    </div>

                    <div class="card-body">
                    <form @submit.prevent="updateAccount">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].subscription" placeholder="Subscription">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].business_name" placeholder="Business Name">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].email" placeholder="Email">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].phone_number" placeholder="Phone Number">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].fax_number" placeholder="Fax Number">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].address" placeholder="Address">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].city" placeholder="City">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].state" placeholder="State">
                        <input type="text" class="form-control mb-3" v-model="companyAccount[0].postal_code" placeholder="Postal Code">
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
    export default {
        name: 'edit-account',
        computed: {
          ...mapGetters(['companyAccount']),
        },
        methods: {
            updateAccount() {
              this.$store.dispatch('updateCompanyAccount', {
                uuid: this.$route.params.uuid,
                subscription: this.companyAccount[0].subscription,
                business_name: this.companyAccount[0].business_name,
                email: this.companyAccount[0].email,
                phone_number: this.companyAccount[0].phone_number,
                fax_number: this.companyAccount[0].fax_number,
                address: this.companyAccount[0].address,
                city: this.companyAccount[0].city,
                state: this.companyAccount[0].state,
                postal_code: this.companyAccount[0].postal_code
              })
              .then(response => {
                  this.$router.go(-1)
              })
          } 
        },
        created() {
         this.$store.dispatch('getCompanyAccount', this.$route.params.uuid)
        }
    }
</script>
