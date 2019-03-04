<template>
    <div class="alert alert-primary  fade show" role="alert">
        <strong>{{ message }}</strong>
    </div>
</template>

<script>
export default {
    name: 'alert',
    props: ['message'],
    methods: {
        removeAlert() {
            this.$store.commit('clearAlert')
        }
    },
    mounted() {
        var self = this
        setTimeout(() => {
            self.removeAlert()
        }, 10000)
    }
}
</script>

