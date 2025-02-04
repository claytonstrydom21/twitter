import './bootstrap';

import Vue from 'vue'
import SideNav from './components/SideNav.vue'
import RegisterModal from "./components/auth/RegisterModal.vue";
import LandingPage from "./components/LandingPage.vue";
import LoginModal from "./components/auth/LoginModal.vue";
import SetUsernameModal from "./components/auth/SetUsernameModal.vue";
import CreatePost from "./components/posts/CreatePost.vue";
import PostFeed from "./components/posts/PostFeed.vue";
import Logo from "./components/misc/Logo.vue";
import GoogleButton from "./components/misc/GoogleButton.vue";
import AppleButton from "./components/misc/AppleButton.vue";

Vue.component('side-nav', SideNav)
Vue.component('register-modal', RegisterModal)
Vue.component('landing-page', LandingPage)
Vue.component('login-modal', LoginModal)
Vue.component('create-post', CreatePost)
Vue.component('post-feed', PostFeed)
Vue.component('set-username-modal', SetUsernameModal)
Vue.component('logo', Logo)
Vue.component('apple-button', AppleButton)
Vue.component('google-button', GoogleButton)

const app = new Vue({
    el: '#app'
});
