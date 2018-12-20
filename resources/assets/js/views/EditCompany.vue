<template>
    <div class="container"  v-if="companyToUpdate">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <router-link :to="{path: '/company/' + companyToUpdate.uuid}" class="nav-link"><i class="fas fa-arrow-left mr-2"></i>Back</router-link>
               <div class="card card-default">
                    <div class="card-header">
                        Edit Company
                    </div>

                    <div class="card-body">
                    <form @submit.prevent="updateCompany">
                        <div class="p-2">
                            <span class="font-weight-bold">Company</span>
                        </div>
                            <input type="text" v-model="companyToUpdate.company" class="form-control mb-3" placeholder="Company Name">
                            <input type="text" v-model="companyToUpdate.email" class="form-control mb-3" placeholder="Company Email">
                            <input type="text" v-model="companyToUpdate.number" class="form-control mb-3" placeholder="Company Number">
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </div>
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
        name: 'edit-company',
        computed: {
          ...mapGetters(['companyToUpdate']),
        },
        methods: {
            updateCompany() {
              this.$store.dispatch('updateCompany', {
                  uuid: this.$route.params.uuid,
                  company: this.companyToUpdate.company,
                  email: this.companyToUpdate.email,
                  number: this.companyToUpdate.number
              })
              .then(response => {
                  this.$router.go(-1)
              })
          } 
        },
        created() {
         this.$store.dispatch('getCompanyToUpdate', this.$route.params.uuid)
        }
    }
</script>
