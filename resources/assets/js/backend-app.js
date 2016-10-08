
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
const VueRouter = require('vue-router');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.use(VueRouter);

var Dashboard = require('./components/Dashboard.vue');
var Users = require('./components/Users.vue');
var Roles = require('./components/Roles.vue');
var Permissions = require('./components/Permissions.vue');

var router = new VueRouter({
    routes: [
        { path: '/', component: Dashboard },
        { path: '/users', component: Users },
        { path: '/roles', component: Roles },
        { path: '/permissions', component: Permissions }
    ]
})

const app = new Vue({
    router
}).$mount('#app')