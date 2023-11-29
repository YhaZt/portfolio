import { createRouter, createWebHistory } from "vue-router";
import { getAuth, onAuthStateChanged } from "firebase/auth";
import firebaseApp from '../firebase';
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/", component: () => import("../User/Register.vue") },
    { path: "/login", component: () => import("../User/Login.vue") },
    { path: "/forgot-password", component: () => import("../User/Forgot.vue") },
    {
      path: "/home",
      name: 'home',
      component: () => import("../Home/Home.vue"),
      meta: {
        requiresAuth: true,
      },
    },
    
  ],
});

const getCurrentUser = () => {
    return new Promise((resolve, reject) => {
      const removeListener = onAuthStateChanged(
        getAuth(firebaseApp), 
        (user) => {
          removeListener();
          resolve(user);
        },
        reject
      );
    });
  };

router.beforeEach(async (to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      try {
        const user = await getCurrentUser();
        if (user) {
          next();
        } else {
          next("/");
        }
      } catch (error) {
        console.error("Error checking authentication:", error);
        next("/");
      }
    } else {
      next();
    }
  });
  
export default router;
