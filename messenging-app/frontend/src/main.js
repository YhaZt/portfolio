import './assets/main.css'
import 'tw-elements';
import { createApp } from 'vue'
import App from './App.vue'
import router from './routes';
import firebaseApp from './firebase'; 

const app = createApp(App)
app.config.globalProperties.$firebaseApp = firebaseApp;

app.use(router)
app.mount('#app')