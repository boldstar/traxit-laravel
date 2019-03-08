import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login.vue'
import Dashboard from '@/views/Dashboard.vue'
import Register from '@/views/Register.vue'
import Company from '@/views/Company.vue'
import CompanySubscription from '@/views/CompanySubscription.vue'
import EditCompany from '@/views/EditCompany.vue'
import CompanyAccount from '@/views/CompanyAccount.vue'
import AccountForm from '@/components/AccountForm.vue'
import EditAccount from '@/views/EditAccount.vue'
import Subscriptions from '@/views/Subscriptions.vue'
import SubscriptionForm from '@/components/SubscriptionForm.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {path: '/', component: Dashboard},
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/company/:uuid', name: 'company', component: Company,
      children: [
        {path: 'account', component: CompanyAccount},
        {path: 'add-account', component: AccountForm},
        {path: 'edit-account', component: EditAccount}
      ]
    },
    {path: '/edit-company/:uuid', component: EditCompany},
    {path: '/subscriptions', name: 'subscriptions', component: Subscriptions,
    children: [
      {path: 'company/:subscription_id', name: 'company-subscription', component: CompanySubscription}
    ]
  },
    {path: '/add-subscription', component: SubscriptionForm},
  ],
  mode: 'history'
})
