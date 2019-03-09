<template>
    <div>
        <card class='stripe-card'
          :class='{ complete }'
          stripe='pk_test_KYFZxHTonLt3CcLZGG802H7i'
          :options='stripeOptions'
          @change='change($event)'
        />
        <div id="card-errors" role="alert" v-text="errorMessage"></div>
    </div>
</template>

<script>
    import { Card, createToken } from 'vue-stripe-elements-plus'
    export default {
        components: { Card },
        data () {
            return {
              complete: false,
              errorMessage: '',
              stripeOptions: {
                style: {
                  base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Raleway", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                      color: '#aab7c4'
                    }
                  },
                  invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                  }
                },
                hidePostalCode: true
            }
          }
        },
        methods: {
            change(event) {
                this.errorMessage = event.error ? event.error.message : ''
            }
      }
    }
</script>