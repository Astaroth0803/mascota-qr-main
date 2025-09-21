// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyA_KvyHW_iI5A-aIoR5lo-_W33XUwyixHo",
  authDomain: "buky-world.firebaseapp.com",
  projectId: "buky-world",
  storageBucket: "buky-world.firebasestorage.app",
  messagingSenderId: "453158593787",
  appId: "1:453158593787:web:3b3058a3216634e55abc5e",
  measurementId: "G-XN0XQY4KF2"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);