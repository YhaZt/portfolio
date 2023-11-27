
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
const firebaseConfig = {
  apiKey: "AIzaSyAgc-s_shTIhFsAMuutUQWdrzkZwGV3qlE",
  authDomain: "todo-list-bafbc.firebaseapp.com",
  databaseURL: "https://todo-list-bafbc-default-rtdb.firebaseio.com",
  projectId: "todo-list-bafbc",
  storageBucket: "todo-list-bafbc.appspot.com",
  messagingSenderId: "999700169782",
  appId: "1:999700169782:web:28c9a33a8aee179b2d0497",
  measurementId: "G-V7N2RFZBLX"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
export default app;