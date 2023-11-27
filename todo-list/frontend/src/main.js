import './assets/main.css'
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Todo from './todo/todo.vue'

const routes = [
    { path: '/', component: Todo },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

createApp(App).use(router).mount('#app')