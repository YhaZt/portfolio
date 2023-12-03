<script setup>
import { Sidenav, Modal, Dropdown, Ripple, initTE } from "tw-elements";
onMounted(() => {
  initTE({ Sidenav, Modal, Ripple, Dropdown });
});

onMounted(() => {
  const sidenav2 = document.getElementById("sidenav-1");
  if (sidenav2) {
    const sidenavInstance2 = Sidenav.getInstance(sidenav2);
    if (sidenavInstance2) {
      let innerWidth2 = null;
      const setMode2 = () => {
        if (window.innerWidth === innerWidth2) {
          return;
        }
        innerWidth2 = window.innerWidth;
        if (window.innerWidth < sidenavInstance2.getBreakpoint("xl")) {
          sidenavInstance2.changeMode("over");
          sidenavInstance2.hide();
        } else {
          sidenavInstance2.changeMode("side");
          sidenavInstance2.show();
        }
      };
      if (window.innerWidth < sidenavInstance2.getBreakpoint("sm")) {
        setMode2();
      }
      window.addEventListener("resize", setMode2);
    } else {
      console.error("Sidenav instance is null");
    }
  } else {
    console.error("Element with ID 'sidenav-1' not found in the DOM");
  }
});

const route = useRoute();
const gUser = route.query.displayName;

const router = useRouter();
const isLoggedIn = ref(false);
let auth;

onMounted(() => {
  auth = getAuth();
  onAuthStateChanged(auth, (user) => {
    if (user) {
      isLoggedIn.value = true;
    } else {
      isLoggedIn.value = false;
    }
  });
});

const logout = async () => {
  const auth = getAuth();

  try {
    await signOut(auth);
    isLoggedIn.value = false;
    await router.push("/login");
  } catch (error) {
    console.error("Logout error:", error.message);
  }
};
const Username = ref("");
onMounted(async () => {
  const auth = getAuth();
  if (auth.currentUser) {
    const uid = auth.currentUser.uid;
    const userRef = dbRef(getDatabase(), `user/${uid}`);
    try {
      const [userSnapshot] = await Promise.all([get(userRef)]);

      console.log("User Snapshot:", userSnapshot.val());

      if (userSnapshot.exists()) {
        Username.value = userSnapshot.val().username;
      } else {
        console.error("User not found in the database");
      }
    } catch (error) {
      console.error("Error fetching user data:", error.message);
    }
  } else {
    console.error("User not authenticated");
  }
});
const formattedUsername = computed(() => {
  if (Username.value) {
    return Username.value.charAt(0).toUpperCase() + Username.value.slice(1);
  }
  if (gUser) {
    return gUser
      .toLowerCase()
      .replace(/(^|\s|-)[a-z]/g, (match) => match.toUpperCase());
  }
  return "";
});
</script>

<template>
  <body class="dark:bg-zinc-800">
    <!--Main Navigation-->
    <header>
      <!-- Sidenav -->
      <nav
        id="sidenav-1"
        class="fixed left-0 top-0 z-[1035] h-screen w-60 -translate-x-full overflow-hidden bg-white shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] dark:bg-zinc-800 xl:data-[te-sidenav-hidden='false']:translate-x-0"
        data-te-sidenav-init
        data-te-sidenav-hidden="false"
        data-te-sidenav-mode-breakpoint-over="0"
        data-te-sidenav-mode-breakpoint-side="xl"
        data-te-sidenav-content="#content"
        data-te-sidenav-accordion="true"
      >
        <img
          id="te-logo"
          class="absolute left-0 top-0 mt-6 ml-5 w-20 h-20"
          src="https://tw-elements.com/img/logo.png"
          alt="TE Logo"
          draggable="false"
        />

        <h3 class="ml-5 mt-32">{{ formattedUsername }}</h3>

        <Toogle />
        <ul class="relative m-0 list-none px-[0.2rem]" data-te-sidenav-menu-ref>
          <li class="relative">
            <a
              class="group flex h-12 cursor-pointer items-center truncate rounded-[5px] px-6 py-4 text-[0.875rem] text-gray-700 outline-none transition duration-300 ease-linear hover:bg-primary-400/10 hover:text-primary-600 hover:outline-none focus:bg-primary-400/10 focus:text-primary-600 focus:outline-none active:bg-primary-400/10 active:text-primary-600 active:outline-none data-[te-sidenav-state-active]:text-primary-600 data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none dark:text-gray-300 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10"
              href="#!"
              data-te-sidenav-link-ref
            >
              <span
                class="mr-4 [&>svg]:h-3.5 [&>svg]:w-3.5 [&>svg]:fill-gray-700 [&>svg]:transition [&>svg]:duration-300 [&>svg]:ease-linear group-hover:[&>svg]:fill-primary-600 group-focus:[&>svg]:fill-primary-600 group-active:[&>svg]:fill-primary-600 group-[te-sidenav-state-active]:[&>svg]:fill-primary-600 motion-reduce:[&>svg]:transition-none dark:[&>svg]:fill-gray-300 dark:group-hover:[&>svg]:fill-gray-300"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    fill-rule="evenodd"
                    d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                    clip-rule="evenodd"
                  />
                </svg>
              </span>
              <span>Profile</span></a
            >
          </li>
          <li class="relative">
            <a
              class="group flex h-12 cursor-pointer items-center truncate rounded-[5px] px-6 py-4 text-[0.875rem] text-gray-700 outline-none transition duration-300 ease-linear hover:bg-primary-400/10 hover:text-primary-600 hover:outline-none focus:bg-primary-400/10 focus:text-primary-600 focus:outline-none active:bg-primary-400/10 active:text-primary-600 active:outline-none data-[te-sidenav-state-active]:text-primary-600 data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none dark:text-gray-300 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10"
              href="#!"
              data-te-sidenav-link-ref
            >
              <span
                class="mr-4 [&>svg]:h-3.5 [&>svg]:w-3.5 [&>svg]:fill-gray-700 [&>svg]:transition [&>svg]:duration-300 [&>svg]:ease-linear group-hover:[&>svg]:fill-primary-600 group-focus:[&>svg]:fill-primary-600 group-active:[&>svg]:fill-primary-600 group-[te-sidenav-state-active]:[&>svg]:fill-primary-600 motion-reduce:[&>svg]:transition-none dark:[&>svg]:fill-gray-300 dark:group-hover:[&>svg]:fill-gray-300"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
                  />
                </svg>
              </span>
              <span>Friends</span></a
            >
          </li>
          <li class="relative">
            <a
              @click.prevent="logout"
              v-if="isLoggedIn"
              :key="isLoggedIn"
              class="group flex h-12 cursor-pointer items-center truncate rounded-[5px] px-6 py-4 text-[0.875rem] text-gray-700 outline-none transition duration-300 ease-linear hover:bg-primary-400/10 hover:text-primary-600 hover:outline-none focus:bg-primary-400/10 focus:text-primary-600 focus:outline-none active:bg-primary-400/10 active:text-primary-600 active:outline-none data-[te-sidenav-state-active]:text-primary-600 data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none dark:text-gray-300 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10"
              href="#!"
              data-te-sidenav-link-ref
            >
              <span
                class="mr-4 [&>svg]:h-3.5 [&>svg]:w-3.5 [&>svg]:fill-gray-700 [&>svg]:transition [&>svg]:duration-300 [&>svg]:ease-linear group-hover:[&>svg]:fill-primary-600 group-focus:[&>svg]:fill-primary-600 group-active:[&>svg]:fill-primary-600 group-[te-sidenav-state-active]:[&>svg]:fill-primary-600 motion-reduce:[&>svg]:transition-none dark:[&>svg]:fill-gray-300 dark:group-hover:[&>svg]:fill-gray-300"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-10 h-10"
                >
                  <path
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                  />
                </svg>
              </span>
              <span>Logout</span></a
            >
          </li>
        </ul>
      </nav>
      <!-- Sidenav -->

      <!-- Navbar -->
      <nav
        id="main-navbar"
        class="max-sm:h-14 md:h-14 fixed left-0 right-0 top-0 flex w-full flex-nowrap items-center justify-between bg-white py-[0.6rem] text-gray-500 shadow-lg hover:text-gray-700 focus:text-gray-700 dark:bg-zinc-700 lg:flex-wrap lg:justify-start xl:pl-60"
        data-te-navbar-ref
      >
        <!-- Container wrapper -->
        <div class="flex w-full flex-wrap items-center justify-between px-4">
          <!-- Toggler -->
          <button
            data-te-sidenav-toggle-ref
            data-te-target="#sidenav-1"
            class="block border-0 bg-transparent px-2.5 text-gray-500 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 xl:hidden"
            aria-controls="#sidenav-1"
            aria-haspopup="true"
          >
            <span
              v-if="darkMode"
              class="block [&>svg]:h-5 [&>svg]:w-5 [&>svg]:text-white"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="h-5 w-5"
              >
                <path
                  fill-rule="evenodd"
                  d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                  clip-rule="evenodd"
                />
              </svg>
            </span>
            <span
              v-else
              class="block [&>svg]:h-5 [&>svg]:w-5 [&>svg]:text-white"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="black"
                class="h-5 w-5"
              >
                <path
                  fill-rule="evenodd"
                  d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                  clip-rule="evenodd"
                />
              </svg>
            </span>
          </button>

          <!-- Search form -->
          <div class="absolute max-sm:right-9 md:right-9">
            <button
              type="button"
              class="inline-block rounded bg-transparent px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white"
              data-te-toggle="modal"
              data-te-target="#searchModal"
              data-te-ripple-init
              data-te-ripple-color="light"
            >
              <span
                class="flex items-center whitespace-nowrap rounded px-3 py-1.5 text-center text-base font-normal text-gray-700 dark:text-gray-200 [&>svg]:w-4"
                id="basic-addon2"
              >
                <svg
                  aria-hidden="true"
                  focusable="false"
                  data-prefix="fas"
                  data-icon="search"
                  role="img"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 512 512"
                >
                  <path
                    fill="currentColor"
                    d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"
                  ></path>
                </svg>
              </span>
            </button>
          </div>
          <button @click="setTheme" class="absolute max-sm:right-3 md:right-3">
            <svg
              v-if="darkMode"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="white"
              class="w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
              />
            </svg>
          </button>
        </div>
        <!-- Container wrapper -->
      </nav>
      <!-- Navbar -->
    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main
      class="absolute mt-14 max-sm:left-0 md:left-0 lg:left-0 xl:left-60 2xl:left-60"
    >
      <div class="ml-5 mt-5">asd</div>
    </main>

    <div
      data-te-modal-init
      class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
      id="searchModal"
      tabindex="-1"
      aria-labelledby="exampleFrameTopModalLabel"
      aria-hidden="true"
    >
      <div
        data-te-modal-dialog-ref
        class="pointer-events-none relative w-full translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out"
      >
        <div
          class="pointer-events-auto relative flex w-full flex-col border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600"
        >
          <div class="relative flex-auto py-1" data-te-modal-body-ref>
            <div class="my-4 flex items-center justify-center">
              <div class="mb-3">
                <div class="relative mb-4 flex w-full flex-wrap items-stretch">
                  <input
                    v-if="darkMode"
                    type="search"
                    class="relative m-0 -mr-0.5 block min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-slate-500 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                    placeholder="Search"
                    aria-label="Search"
                    aria-describedby="button-addon3"
                  />
                  <input
                    v-else
                    type="search"
                    class="relative m-0 -mr-0.5 block min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                    placeholder="Search"
                    aria-label="Search"
                    aria-describedby="button-addon3"
                  />
                  <!--Search button-->
                  <button
                    class="relative z-[2] rounded-r border-2 border-primary px-6 py-2 text-xs font-medium uppercase text-primary transition duration-150 ease-in-out hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0"
                    type="button"
                    id="button-addon3"
                    data-te-ripple-init
                  >
                    Search
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Footer-->
    <footer></footer>
    <!--Footer-->
  </body>
</template>

<script>
import {
  getDatabase,
  ref as dbRef,
  push as pushToDB,
  onValue,
  get,
  set,
  remove,
} from "firebase/database";
import { ref, onMounted, computed } from "vue";
import { getAuth, onAuthStateChanged, signOut } from "firebase/auth";
import firebaseApp from "@/firebase.js";
import { useRouter } from "vue-router";

import { useRoute } from "vue-router";
// import Toogle from '../assets/toggle.vue'

export default {
  //   components: { Toogle },
  data() {
    return {
      darkMode: localStorage.getItem("theme") === "dark",
    };
  },
  methods: {
    setDarkTheme() {
      document.documentElement.classList.add("dark");
      localStorage.setItem("theme", "dark");
      this.darkMode = true;
    },
    setLightTheme() {
      document.documentElement.classList.remove("dark");
      localStorage.setItem("theme", "light");
      this.darkMode = false;
    },
    setTheme() {
      this.darkMode ? this.setLightTheme() : this.setDarkTheme();
    },
  },
  mounted() {
    // Check if there's a theme set in localStorage, then set the theme accordingly
    if (localStorage.getItem("theme") === "dark") {
      this.setDarkTheme();
    } else {
      this.setLightTheme();
    }
  },
};
</script>
