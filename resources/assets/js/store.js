import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from './router.js'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://traxit.test/web'
const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.headers.common['header1'] = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': token.content
}

export default new Vuex.Store({
  state: {
    user: '',
    successalert: '',
    erroralert: '',
    token: JSON.parse(localStorage.getItem('access_token')) || false,
    companies: [],
    company: '',
    companyAccount: '',
    subscriptions: [],
    subscription: '',
    modal: false,
    loading: false
  },
  getters: {
    loggedIn(state) {
        return state.token
    },
    companies(state) {
        return state.companies
    },
    company(state) {
        return state.company
    },
    companyAccount(state) {
        return state.companyAccount
    },
    subscriptions(state) {
        return state.subscriptions
    },
    subscription(state) {
        return state.subscription
    },
    companyToUpdate(state) {
        return state.company
    },
    successAlert(state) {
        return state.successalert
    },
    errorAlert(state) {
        return state.erroralert
    },
    loading(state) {
        return state.loading
    },
    modalState(state) {
        return state.modal
    }
  },
  mutations: {
    loggedIn(state) {
        state.token = !state.token
    },
    getCompanies(state, companies) {
        state.companies = companies
    },
    getCompany(state, company) {
        state.company = company
    },
    getCompanyAccount(state, account) {
        state.companyAccount = account
    },
    getCompanyToUpdate(state, company) {
        state.company = company
    },
    deleteCompany(state, uuid) {
        const index = state.companies.findIndex(company => company.uuid == uuid)
        state.companies.splice(index, 1)
    },
    updateCompany(state, company) {
        const index = state.companies.findIndex(item => item.uuid == company.uuid)
        state.companies.splice(index, 1, company)
    },
    getSubscriptions(state, subscriptions) {
        state.subscriptions = subscriptions
    },
    getCompanySubscription(state, subscription) {
        state.subscription = subscription
    },
    addSubscription(state, subscripion) {
        state.subscriptions.push(subscripion)
    },
    deleteSubscription(state, id) {
        const index = state.subscriptions.findIndex(subscription => subscription.id == id)
        state.subscriptions.splice(index, 1)
    },
    updateSubscription(state, subscription) {
        const index = state.subscriptions.findIndex(item => item.id == subscription.id)
        state.subscriptions.splice(index, 1, subscription)
    },
    successAlert(state, alert) {
        state.successalert = alert
    },
    errorAlert(state, alert) {
        state.erroralert = alert
    },
    loadingRequest(state) {
        state.loading = !state.loading
    },
    closeModal(state) {
        state.modal = !state.modal
    },
    showModal(state) {
        state.modal = !state.modal
    }
  },
  actions: {
    login(context, credentials) {
        context.commit('loadingRequest')
        axios.post('/login', {
            email: credentials.email,
            password: credentials.password
        })
        .then(response => {
            context.commit('loggedIn')
            localStorage.setItem('access_token', true)
            context.commit('loadingRequest')
            router.push('/')
        })
        .catch(error => {
            console.log(error.response.data)
            context.commit('loadingRequest')
        })
    },
    logout(context) {
        context.commit('loadingRequest')
        axios.post('/logout')
        .then(response => {
            context.commit('loggedIn')
            localStorage.removeItem('access_token')
            context.commit('loadingRequest')
            router.push('/login')
        })
        .catch(error => {
            context.commit('loadingRequest')
            localStorage.removeItem('access_token')
            context.commit('loggedIn')
            console.log(error.response.data)
        })
    },
    addCompany(context, company) {
        context.commit('loadingRequest')
        axios.post('/register', {
            company: company.company,
            company_email: company.company_email,
            company_number: company.company_number,
            name: company.name,
            email: company.email,
            fqdn: company.fqdn,
            password: company.password,
            password_confirmation: company.password_confirmation
        })
        .then(response => {
            context.commit('loadingRequest')
            context.commit('successAlert', response.data.message)
            router.push('/')
        })
        .catch(error => {
            context.commit('loadingRequest')
            console.log(error.response.data)
        })
    },
    getCompanies(context) {
        axios.get('/companies')
        .then(response => {
            context.commit('getCompanies', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    getCompany(context, uuid) {
        axios.get('/company/' + uuid)
        .then(response => {
            context.commit('getCompany', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    getCompanyToUpdate(context, uuid) {
        axios.get('/companyToUpdate/' + uuid)
        .then(response => {
            context.commit('getCompanyToUpdate', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    updateCompany(context, company) {
        axios.patch('/company/' + company.uuid, {
            company: company.company,
            email: company.email,
            number: company.number
        })
        .then(response => {
            context.commit('updateCompany', response.data)
        })        
        .catch(error => {
            console.log(error.response.data)
        })
    },
    deleteCompany(context, uuid) {
        axios.delete('/company/' + uuid)
        .then(response => {
            context.commit('successAlert', response.data)
            context.commit('deleteCompany', uuid)
            router.push('/')
        })
        .catch(error => {
            context.commit('errorAlert', error.response.data)
            console.log(error.response.data)
        })
    },
    getCompanyAccount(context, uuid) {
        axios.get('/companyAccount/' + uuid)
        .then(response => {
            context.commit('getCompanyAccount', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    addCompanyAccount(context, account) {
        context.commit('loadingRequest')
        axios.post('/companyAccount', {
            business_name: account.business_name,
            email: account.email,
            phone_number: account.phone_number,
            fax_number: account.fax_number,
            address: account.address,
            city: account.city,
            state: account.state,
            postal_code: account.postal_code
        })
        .then(response => {
            context.commit('loadingRequest')
            context.commit('successAlert', response.data.message)
            router.push('/')
        })
        .catch(error => {
            context.commit('loadingRequest')
            console.log(error.response.data)
        })
    },
    updateCompanyAccount(context, account) {
        axios.patch('/updateCompanyAccount/' +account.uuid, {
            business_name: account.business_name,
            email: account.email,
            phone_number: account.phone_number,
            fax_number: account.fax_number,
            address: account.address,
            city: account.city,
            state: account.state,
            postal_code: account.postal_code,
            subscription: account.subscription
        })
        .then(response => {
            console.log(response.data)
            context.commit('successAlert', response.data.message)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    getSubscriptions(context) {
        axios.get('/subscriptions')
        .then(response => {
            context.commit('getSubscriptions', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    getCompanySubscription(context, id) {
        axios.get('/subscriptions/' + id)
        .then(response => {
            context.commit('getCompanySubscription', response.data)
        })
        .catch(error => {
            console.log(error.response.data)
        })
    },
    addSubscription(context, subscription) {
        context.commit('loadingRequest')
        axios.post('/subscriptions', {
            fqdn: subscription.fqdn,
            stripeToken: subscription.stripeToken,
            email: subscription.email
        })
        .then(response => {
            console.log(response.data)
            context.commit('loadingRequest')
            context.commit('successAlert', response.data)
            router.push('/subscriptions')
        })
        .catch(error => {
            context.commit('loadingRequest')
            console.log(error.response.data)
        })
    }, 
    updateSubscription(context, subscripion) {
        axios.patch('/subscriptions/' + subscripion.id, {
            //add proper fields here
        })
        .then(response => {
            context.commit('updateSubscription', response.data)
        })        
        .catch(error => {
            console.log(error.response.data)
        })
    },
    cancelSubscription(context, id) {
        console.log(id)
        axios.delete('/cancel-subscription/' + id)
        .then(response => {
            console.log(response.data)
            // context.commit('successAlert', response.data)
            // context.commit('cancelSubscriptions', id)
            router.push('/subscriptions')
        })
        .catch(error => {
            context.commit('errorAlert', error.response.data)
            console.log(error.response.data)
        })
    }
  }
})
