require('./bootstrap');

import Vue from 'vue'
import VueAxios from 'vue-axios'
// import App from './App.vue'

Vue.use(VueAxios, axios)
Vue.component('app-component', require('./App.vue').default)

new Vue({
    el: '#app',
    // render: h => h(App)
});
