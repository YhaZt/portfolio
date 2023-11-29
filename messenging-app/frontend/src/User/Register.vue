<script setup>
import { Input, Ripple, initTE } from "tw-elements";
onMounted(() => {
  initTE({ Input, Ripple });
});

import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
  getAuth,
  createUserWithEmailAndPassword,
  GoogleAuthProvider,
  signInWithPopup,
} from "firebase/auth";
import firebaseApp from "@/firebase.js";
const router = useRouter();
const db = getDatabase(firebaseApp);

import {
  getDatabase,
  ref as dbRef,
  push as pushToDB,
  onValue,
  set,
  remove,
} from "firebase/database";

const NewUser = ref({
  username: "",
  email: "",
  password: "",
  confirmpassword: "",
});
const addUser = async () => {
  if (
    NewUser.value.username &&
    NewUser.value.email &&
    NewUser.value.password &&
    NewUser.value.confirmpassword &&
    NewUser.value.password === NewUser.value.confirmpassword
  ) {
    try {
      const userCredential = await createUserWithEmailAndPassword(
        getAuth(),
        NewUser.value.email,
        NewUser.value.password
      );
      const user = userCredential.user;
      if (user) {
        const uid = user.uid;
        console.log("UID:", uid);
        await set(dbRef(db, `user/${uid}`), {
          username: NewUser.value.username,
        });
      }
      router.push("/login");
    } catch (error) {
      console.error("Registration error:", error.message);
      console.error("Full error object:", error);
    }
  } else {
    alert("Please fill out all fields and ensure passwords match.");
  }
};

// const googleSignIn = () => {
//   const provider = new GoogleAuthProvider();
//   signInWithPopup(getAuth(), provider)
//     .then((result) => {
//       router.push("/feed");
//     })
//     .catch((error) => {});
// };
const googleSignIn = () => {
  const provider = new GoogleAuthProvider();

  provider.addScope("profile");
  provider.addScope("email");

  signInWithPopup(getAuth(), provider)
    .then((result) => {
      // The user's display name is available as result.user.displayName
      router.push("/home");
    })
    .catch((error) => {
      console.error("Google sign-in error:", error.message);
      console.error("Full error object:", error);
    });
};
</script>

<template>
  <section class="h-screen">
    <div class="h-full">
      <div
        class="g-6 flex h-full flex-wrap items-center justify-center lg:justify-between"
      >
        <div
          class="shrink-1 mr-10 mb-12 grow-0 basis-auto md:mb-0 md:w-9/12 md:shrink-0 lg:w-6/12 xl:w-6/12"
        >
          <img
            src="https://tecdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
            class="w-full"
            alt="Sample image"
          />
        </div>

        <div class="mb-12 mr-20 mt-20 md:mb-0 md:w-8/12 lg:w-5/12 xl:w-5/12">
          <form @submit.prevent="addUser">
            <div class="relative mb-6" data-te-input-wrapper-init>
              <input
                v-model="NewUser.username"
                required
                autocomplete="off"
                type="text"
                class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                id="exampleFormControlInput2"
                placeholder="Email address"
              />
              <label
                for="exampleFormControlInput2"
                class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                >Username
              </label>
            </div>

            <div class="relative mb-6" data-te-input-wrapper-init>
              <input
                v-model="NewUser.email"
                required
                autocomplete="off"
                type="text"
                class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                id="exampleFormControlInput2"
                placeholder="Email address"
              />
              <label
                for="exampleFormControlInput2"
                class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                >Email address
              </label>
            </div>

            <div class="relative mb-6" data-te-input-wrapper-init>
              <input
                v-model="NewUser.password"
                required
                autocomplete="off"
                type="password"
                class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                id="exampleFormControlInput22"
                placeholder="Password"
              />
              <label
                for="exampleFormControlInput22"
                class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                >Password
              </label>
            </div>
            <div class="relative mb-6" data-te-input-wrapper-init>
              <input
                v-model="NewUser.confirmpassword"
                required
                autocomplete="off"
                type="password"
                class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                id="exampleFormControlInput22"
                placeholder="Password"
              />
              <label
                for="exampleFormControlInput22"
                class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                >Confirm Password
              </label>
            </div>
            <div class="mb-6 flex items-center justify-between"></div>

            <button
              type="submit"
              class="inline-block rounded bg-primary px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
              data-te-ripple-init
              data-te-ripple-color="light"
            >
              Register
            </button>
          </form>
          <div class="text-center lg:text-left">
            <p class="mb-0 mt-2 pt-1 text-sm font-semibold">
              Already have an account?
              <a
                href="/login"
                class="text-success transition duration-150 ease-in-out hover:text-success-600 focus:text-success-600 active:text-success-700"
                >Login</a
              >
              <a
                href="/forgot-password"
                class="text-danger ml-auto flex transition duration-150 ease-in-out hover:text-danger-600 focus:text-danger-600 active:text-danger-700"
                >Forgot password?</a
              >
              <button
                @click="googleSignIn"
                class="inline-block rounded bg-primary px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                data-te-ripple-init
                data-te-ripple-color="light"
              >
                Login with Google
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
