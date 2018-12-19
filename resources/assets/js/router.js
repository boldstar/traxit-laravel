import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login.vue'
import Dashboard from '@/views/Dashboard.vue'
import Register from '@/views/Register.vue'
import Company from '@/views/Company.vue'
import EditCompany from '@/views/EditCompany.vue'
import Subscriptions from '@/views/Subscriptions.vue'
import SubscriptionForm from '@/components/SubscriptionForm.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {path: '/', component: Dashboard},
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/company/:uuid', component: Company},
    {path: '/edit-company/:uuid', component: EditCompany},
    {path: '/subscriptions', component: Subscriptions},
    {path: '/add-subscription', component: SubscriptionForm},
  ],
  mode: 'history'
})
