<template>
  <div class="flex justify-center w-screen items-center h-screen bg-red-500">
    <fwb-table hoverable>
      <fwb-table-head>
        <fwb-table-head-cell></fwb-table-head-cell>
        <fwb-table-head-cell>Todo Name</fwb-table-head-cell>
        <fwb-table-head-cell>Description</fwb-table-head-cell>
        <fwb-table-head-cell>
          <span class="sr-only">Edit</span>
        </fwb-table-head-cell>
      </fwb-table-head>
      <fwb-table-body>
        <fwb-table-row
          class="divide-x divide-y"
          v-for="(task, taskId) in tasks"
          :key="taskId"
        >
          <fwb-table-cell
            ><input
              id="default-checkbox"
              type="checkbox"
              value=""
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            />
          </fwb-table-cell>
          <fwb-table-cell>{{ task.title }}</fwb-table-cell>
          <fwb-table-cell>{{ task.description }}</fwb-table-cell>
          <fwb-table-cell>
            <fwb-a :href="'#/' + taskId">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
              >
                <path
                  fill="green"
                  d="M5 21q-.825 0-1.413-.588T3 19V5q0-.825.588-1.413T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.588 1.413T19 21H5Zm7-9Zm-3 3v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4q0 .375-.138.738t-.437.662L13.25 15H9ZM21.025 4.4l-1.4-1.4l1.4 1.4ZM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575V13Zm6.5-6.5l-.725-.7l.725.7l.7.7l-.7-.7Z"
                />
              </svg>
            </fwb-a>
            <fwb-a :href="'#/' + taskId"> </fwb-a>
          </fwb-table-cell>
        </fwb-table-row>
      </fwb-table-body>
    </fwb-table>
  </div>
</template>

<script setup>
import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from "flowbite-vue";
import { ref, onMounted } from "vue";
import { getDatabase, ref as dbRef, onValue } from "firebase/database";
import firebaseApp from "../firebase.js";

const db = getDatabase(firebaseApp);

const tasks = ref([]);

onMounted(() => {
  const tasksRef = dbRef(db, "tasks");

  onValue(tasksRef, (snapshot) => {
    const data = snapshot.val();
    tasks.value = Object.values(data || {});
  });
});
</script>
