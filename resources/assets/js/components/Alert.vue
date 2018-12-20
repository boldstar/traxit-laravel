<template>
    <div class="alert alert-primary  fade show" role="alert">
        <strong>{{ message }}</strong>
    </div>
</template>

<script>
export default {
    name: 'alert',
    props: ['message']
}
</script>

