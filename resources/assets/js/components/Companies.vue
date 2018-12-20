<template>
    <div class="container">
        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Company</th>
                        <th scope="col">FQDN</th>
                        <th scope="col">Database</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody v-if="companies">
                    <tr v-for="(company, index) in companies" :key="index">
                        <th>{{ company.company }}</th>
                        <td v-if="company.hostnames">{{ company.hostnames[0].fqdn }}</td>
                        <td scope="row">{{ company.uuid }}</td>
                        <td><router-link :to="{path: '/company/' + company.uuid}" class="btn btn-sm btn-primary">View</router-link></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name:'companies',
        props: ['companies']
    }
</script>
