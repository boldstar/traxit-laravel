<template>
<div class="container d-flex justify-content-center">
    <form action="/subscriptions" method="POST" id="payment-form" @submit.prevent="pay()" ref="form" class="form">
        <div class="form-group">
            <label for="hostname">Select Company Host Name</label>
            <select class="form-control" v-model="hostname">
                <option disabled>{{option}}</option>
                <option :value="host.fqdn" v-for="(host, index) in subscriptions" :key="index" class="text-capitalize">{{ host.subdomain}}</option>
            </select>
        </div>

      <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" class="form-control" id="email" v-model="email">
      </div>

      <div class="form-group">
          <label for="name_on_card">Name on Card</label>
          <input type="text" class="form-control" id="name_on_card" name="name_on_card" v-model="name_on_card">
      </div>

      <div class="form-group">
          <label for="card-element">Credit Card</label>
          <card-element></card-element>
      </div>

      <!-- CSRF Field -->
      <input type="hidden" name="_token" :value="csrf">

      <div class="spacer"></div>

      <button type="submit" class="btn btn-success">Submit Payment</button>
  </form>
</div>
</template>

<script>
    import { createToken, Card } from 'vue-stripe-elements-plus'
    import CardElement from '@/components/CardElement.vue'
    import {mapGetters} from 'vuex'

    export default {
        data () {
            return {
              csrf: document.head.querySelector('meta[name="csrf-token"]').content,
              name_on_card: '',
              stripeToken: '',
              email: '',
              hostname: '',
              option: 'Choose..'
            }
        },
        components: {
            CardElement
        },
        computed: {
            ...mapGetters(['subscriptions', 'successAlert'])
        },
        methods: {
            pay () {
              var options = {
                name: this.name_on_card,
              }
              createToken(options).then(result => {
                console.log(result.token)
                this.stripeToken = result.token.id
                this.$store.dispatch('addSubscription', {
                    fqdn: this.hostname,
                    stripeToken: this.stripeToken,
                    email: this.email
                })
              })
            }
      },
      created() {
            this.$store.dispatch('getSubscriptions')
            this.hostname = this.option 
      }
    }
</script>

<style scoped>
.form {
    width: 500px;
}
</style>


