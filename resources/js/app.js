
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import store from './store/store';
import VueRouter from 'vue-router'
import Vue2TouchEvents from 'vue2-touch-events'
import helpers from './helpers';

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(Vue2TouchEvents);
Vue.use({
    install(Vue, options) {
        Vue.prototype.$helpers = helpers;
    },
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('input-button', require('./components/InputButton'));
Vue.component('task-list', require('./containers/TaskList'));
Vue.component('project-select', require('./containers/ProjectSelect'));
Vue.component('project-new-form', require('./containers/ProjectNewForm'));

import routes from './routes';
import moment from 'moment';

const router = new VueRouter({
    routes,
});

const app = new Vue({
    router,
    store,
    el: '#app',
    data() {
        return {
            transitionName: 'slide-right',
        }
    },
    watch: {
        '$route'(to, from) {
            const toDepth = to.path.split('/').length;
            const fromDepth = from.path.split('/').length;
            this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';
            this.changeMonth();
            this.changeProject();

            localStorage.route = to.path;
        }
    },
    async created() {
        this.changeMonth();
        await this.$store.dispatch('loadProjectList');
        this.changeProject();
    },
    methods: {
        changeProject() {
            this.$store.dispatch('changeProject', parseInt(this.$route.params.project_id));
        },
        changeMonth() {
            let month = this.$route.params.date;
            if(!month)
                month = moment().format('MM-YYYY');
            this.$store.dispatch('changeMonth', month);
        }
    }
});

if(localStorage.route)
    app.$router.push(localStorage.route);