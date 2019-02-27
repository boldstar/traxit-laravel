<template>
<div class="container">
    <form action="/subscriptions" method="POST" id="payment-form" @submit.prevent="pay()" ref="form">
      <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" class="form-control" id="email" v-model="email">
      </div>

      <div class="form-group">
          <label for="name_on_card">Name on Card</label>
          <input type="text" class="form-control" id="name_on_card" name="name_on_card" v-model="name_on_card">
      </div>

      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" v-model="address">
              </div>
          </div>

          <div class="col-md-3">
              <div class="form-group">
                  <label for="city">City</label>
                  <input type="text" class="form-control" id="city" name="city" v-model="city">
              </div>
          </div>

          <div class="col-md-3">
              <div class="form-group">
                  <label for="province">Province</label>
                  <input type="text" class="form-control" id="province" name="province" v-model="province">
              </div>
          </div>

      </div>

      <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="postalcode">Postal Code</label>
                  <input type="text" class="form-control" id="postalcode" name="postalcode" v-model="postalcode">
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                  <label for="country">Country</label>
                  <input type="text" class="form-control" id="country" name="country" v-model="country">
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone" v-model="phone">
              </div>
          </div>

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
    export default {
        data () {
            return {
              csrf: document.head.querySelector('meta[name="csrf-token"]').content,
              name_on_card: '',
              postalcode: '',
              address: '',
              country: '',
              province: '',
              stripeToken: '',
              phone: '',
              city: '',
              email: ''
            }
        },
        components: {
            CardElement
        },
        methods: {
            pay () {
              // createToken returns a Promise which resolves in a result object with
              // either a token or an error key.
              // See https://stripe.com/docs/api#tokens for the token object.
              // See https://stripe.com/docs/api#errors for the error object.
              // More general https://stripe.com/docs/stripe.js#stripe-create-token.
              var options = {
                name: this.name_on_card,
              }
              createToken(options).then(result => {
                console.log(result.token)
                this.stripeToken = result.token.id
                this.$store.dispatch('addSubscription', {
                    uuid: '63519b1c3c754bdab569f8d787fb1341',
                    postalcode: this.postalcode,
                    address: this.address,
                    country: this.country,
                    province: this.province,
                    stripeToken: this.stripeToken,
                    phone: this.phone,
                    city: this.city,
                    email: this.email
                })
              })
            }
      }
    }
</script>

