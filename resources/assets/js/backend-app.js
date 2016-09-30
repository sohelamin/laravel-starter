
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

var router = new VueRouter();

var App = {};
var Dashboard = require('./components/Dashboard.vue');
var Users = require('./components/Users.vue');
var Roles = require('./components/Roles.vue');
var Permissions = require('./components/Permissions.vue');

router.map({
    '/': {
        component: Dashboard
    },
    '/users': {
        component: Users
    },
    '/roles': {
        component: Roles
    },
    '/permissions': {
        component: Permissions
    }
})

// const App = new Vue({
//     el: 'body'
// });

router.start(App, 'body')