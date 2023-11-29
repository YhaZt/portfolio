
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
const firebaseConfig = {
  apiKey: "AIzaSyCTbAyzZj55Bn3Swo_ABk7JSOnnjuE6eHo",
  authDomain: "sc-messenger-21e74.firebaseapp.com",
  projectId: "sc-messenger-21e74",
  storageBucket: "sc-messenger-21e74.appspot.com",
  messagingSenderId: "1048603447239",
  appId: "1:1048603447239:web:5ba781d460be06fe5e13e2",
  measurementId: "G-G3H885WLC0"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
export default app;