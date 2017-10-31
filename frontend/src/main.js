import Vue from 'vue'
import App from './app.vue'

new Vue({
    delimiters: ['${', '}'],
    el: 'app',
    components: { App }
});