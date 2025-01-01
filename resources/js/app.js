/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Team from './components/Team.vue';
import Championship from './components/Championship.vue';
import Match from './components/Match.vue';
import User from './components/User.vue';
import Classification from './components/ClassificationTable.vue';

const app = createApp({});

app.component('navbar-vue', Navbar);
app.component('footer-vue', Footer);
app.component('team-vue', Team);
app.component('championship-vue', Championship);
app.component('match-vue', Match);
app.component('user-vue', User);
app.component('classification-vue', Classification);

app.mount('#app');
