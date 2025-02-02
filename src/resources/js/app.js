import './bootstrap';

import Vue from 'vue'
import SideNav from './components/SideNav.vue'

Vue.component('side-nav', SideNav)

console.log('Vue is initializing')

const app = new Vue({
    el: '#app'
})

console.log('Vue has initialized')
