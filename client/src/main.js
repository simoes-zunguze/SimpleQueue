import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import router from './router'
import store from './store'


//Laravel Echo and pusher configurations
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

//Dev Configs
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '10000',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});


import { library } from '@fortawesome/fontawesome-svg-core'
//import used icons here
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { faUser } from '@fortawesome/free-solid-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

//Used icons here
library.add(faUserSecret)
library.add(faUser)

Vue.component('font-awesome-icon', FontAwesomeIcon)



Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
