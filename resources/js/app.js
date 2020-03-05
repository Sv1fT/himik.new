/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');


import Vue from 'vue';
import VueToast from 'vue-toast-notification';
global.$ = global.jQuery = require('jquery');
import VueRouter from 'vue-router';
Vue.use(VueRouter);
Vue.use(VueToast, {
    // One of options
    position: 'top-right',
    duration: '6000'
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('sidebar', require('./components/users/sidebar.vue'));
Vue.component('image-upload', require('./components/image/ImageUpload'));
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import { routes } from './routes';
import moment from 'moment'

Vue.filter('formatDate', function(value) {
    if (value) {
        moment.lang('ru');
        return moment(String(value)).format('DD MMMM YYYY hh:mm')
    }
});
const router = new VueRouter({
    routes,
    mode: 'history',
    linkExactActiveClass: 'is-active',

});
var csrf_token = $('meta[name="csrf-token"]').attr('content');
var user_id = $('meta[name="user-id"]').attr('content');

const app = new Vue({
    el: '#app',
    router,
    data : {
        post    : '',
        token   : csrf_token,
        user_id : user_id
    },
});

