require('./bootstrap');

window.Vue = require('vue')
import router from './router'

import Icon from 'vue-awesome'
Vue.component('icon', Icon)

import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton)

const app = new Vue({
    el: '#app',
    router
});
