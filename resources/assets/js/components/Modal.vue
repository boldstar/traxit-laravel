<template>
    <div class="modal-backdrop">
        <div class="modal">
            <header class="modal-header">
            <slot name="header">
                <span class="font-weight-bold align-self-center">
                    {{name}}
                </span>
                <button type="button" class="btn-close p-0 mr-3" @click="close">
                x
                </button>
            </slot>
            </header>
            <section class="modal-body">
            <slot name="body" class="align-self-center h3">
                  {{alert}}
            </slot>
            </section>
            <footer class="modal-footer">
                <slot name="footer">
                <button type="button" class="btn btn-sm btn-secondary" @click="close">
                    Cancel
                </button>
                <button type="button" class="btn btn-sm btn-primary" @click="cancelSubscription(id)" v-if="subscription">
                    Confirm
                </button>
                <button type="button" class="btn btn-sm btn-primary" @click="confirmRequest(id)" v-else>
                    Delete Company
                </button>
            </slot>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    name: 'modal',
    props: ['name', 'alert', 'id', 'subscription'],
    computed: {
        
    },
    methods: {
      close() {
          this.$store.commit('closeModal')
      },
      confirmRequest(id) {
          this.$store.dispatch('deleteCompany', id)
          this.$store.commit('closeModal')
          this.$router.push('/')
      },
      cancelSubscription(id) {
          this.$store.dispatch('cancelSubscription', id)
          this.$store.commit('closeModal')
      }
    },
}
</script>

<style>
.modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  .modal {
    position: relative;
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    border-radius: 10px;
    width: 500px;
    height: 250px;
    display: flex;
    flex-direction: column;
  }

  .modal-header,
  .modal-footer {
    padding: 15px;
    display: flex;
  }

  .modal-header {
    border-bottom: 1px solid #eeeeee;
    color: rgb(92, 99, 97);
    justify-content: space-between;
  }

  .modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: space-between;
  }

  .modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: rgb(92, 99, 97);
    background: transparent;
  }

</style>


