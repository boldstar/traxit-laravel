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
    }
  }
})
