import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login.vue'
import Dashboard from '@/views/Dashboard.vue'
import Register from '@/views/Register.vue'
import Company from '@/views/Company.vue'
import EditCompany from '@/views/EditCompany.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {path: '/', component: Dashboard},
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/company/:uuid', component: Company},
    {path: '/edit-company/:uuid', component: EditCompany},
  ],
  mode: 'history'
})
