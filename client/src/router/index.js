import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/general-ticket',
    name: 'general-ticket',
    // route level code-splitting
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "general-ticket" */ '../views/GeneralTicket.vue')

  },
  {
    path: '/ticket-control',
    name: 'ticket-control',
    // route level code-splitting
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "ticket-control" */ '../views/TicketControl.vue')
  },
  {
    path: '/queue-monitor',
    name: 'queues-monitor',
    // route level code-splitting
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "queue-monitor" */ '../views/QueuesMonitor.vue')
  },
  {
    path: '/queue-monitor/:id',
    name: 'queue-monitor',
    // route level code-splitting
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "queue-monitor" */ '../views/SingleQueueMonitor.vue')
  }


]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
