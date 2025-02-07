import './bootstrap';

import Vue from 'vue'
import SideNav from './components/SideNav.vue'
import RegisterModal from "./components/auth/RegisterModal.vue";
import LandingPage from "./components/LandingPage.vue";
import LoginModal from "./components/auth/LoginModal.vue";
import SetUsernameModal from "./components/auth/SetUsernameModal.vue";
import CreatePost from "./components/posts/CreatePost.vue";
import BaseFeed from "./components/posts/BaseFeed.vue";
import Logo from "./components/misc/Logo.vue";
import GoogleButton from "./components/misc/GoogleButton.vue";
import AppleButton from "./components/misc/AppleButton.vue";
import OrDivider from "./components/misc/OrDivider.vue";
import Footer from "./components/Footer.vue";
import FeedContainer from "./components/posts/FeedContainer.vue";
import FollowButton from "./components/FollowButton.vue";
import ExplorePage from './components/explore/ExplorePage.vue';
import UserSearch from './components/ui/UserSearch.vue';
import LikeButton from "./components/ui/LikeButton.vue";

Vue.component('like-button', LikeButton)
Vue.component('register-modal', RegisterModal)
Vue.component('landing-page', LandingPage)
Vue.component('login-modal', LoginModal)
Vue.component('create-post', CreatePost)
Vue.component('base-feed', BaseFeed)
Vue.component('set-username-modal', SetUsernameModal)
Vue.component('logo', Logo)
Vue.component('apple-button', AppleButton)
Vue.component('google-button', GoogleButton)
Vue.component('or-divider', OrDivider)
Vue.component('footer', Footer)
Vue.component('feed-container', FeedContainer)
Vue.component('side-nav', SideNav)
Vue.component('follow-button', FollowButton)
Vue.component('explore-page', ExplorePage)
Vue.component('user-search', UserSearch)

const app = new Vue({
    el: '#app'
});
