import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router';

// Import components
import App from './components/App.vue';
import TaskList from "./components/TaskList.vue";
import TaskForm from "./components/TaskForm.vue";
import Task from "./components/Task.vue";
import axios from "axios";

//routes
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: TaskList },
        { path: '/tasks/create', component: TaskForm },
        { path: '/tasks/:id', component: Task },
        { path: '/tasks/:id/edit', component: TaskForm },
    ]
});

// store
const store = createStore({
    state () {
        return {}
    },
    mutations: {
        async changeStatus(state, task) {
            try {
                await axios.put(`/api/tasks/${task.id}/status`);
                task.is_completed = !task.is_completed;
            } catch (error) {
                console.error(error); // place to handle API errors
            }
        },
    }
})

const app = createApp(App);
app.use(router);
app.use(store);
app.mount('#app');
