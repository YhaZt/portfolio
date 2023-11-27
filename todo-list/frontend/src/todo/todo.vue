<script setup>
import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton,
  FwbModal,
  FwbInput,
} from "flowbite-vue";
import { ref, onMounted } from "vue";
import {
  getDatabase,
  ref as dbRef,
  push as pushToDB,
  onValue, set, remove,
} from "firebase/database";
//sample code ko sa ang backend ay ci4
// import axios from 'axios';
// onMounted(async () => {
//   try {
//     const response = await axios.get('backend.test/api/tasks');
//     tasks.value = response.data;
//   } catch (error) {
//     console.error('Error fetching tasks:', error);
//   }
// });
import firebaseApp from "@/firebase.js";
const db = getDatabase(firebaseApp);
const tasks = ref([]);

onMounted(() => {
  const tasksRef = dbRef(db, "tasks");
  onValue(tasksRef, (snapshot) => {
    const data = snapshot.val();
    tasks.value = Object.entries(data || {}).map(([id, task]) => ({
      id,
      ...task,
    }));
  });
});
const newTask = ref({
  title: "",
  description: "",
});
const addTask = () => {
  if (newTask.value.title && newTask.value.description) {
    pushToDB(dbRef(db, "tasks"), newTask.value);
    newTask.value.title = "";
    newTask.value.description = "";
  } else {
    alert("Please fill out both title and description.");
  }
};

const updateTask = () => {
  if (newTask.value.title && newTask.value.description) {
    const taskRef = dbRef(db, `tasks/${selectedTask.value.id}`);
    set(taskRef, {
      title: newTask.value.title,
      description: newTask.value.description,
    });
    closeEditModal();
    newTask.value.title = "";
    newTask.value.description = "";
  } else {
    alert("Please fill out both title and description.");
  }
};

const deleteTask = () => {
  const taskRef = dbRef(db, `tasks/${selectedTask.value.id}`);
  remove(taskRef);
  closeDeleteModal();
}

const isShowModal = ref(false);
const isEditModal = ref(false);
const isDeleteModal = ref(false);
const selectedTask = ref(null);

function closeModal() {
  isShowModal.value = false;
}
function closeEditModal() {
  isEditModal.value = false;
}
function closeDeleteModal() {
  isDeleteModal.value = false;
}
function showModal() {
  isShowModal.value = true;
}
function editModal(task) {
  newTask.value.title = task.title;
  newTask.value.description = task.description;
  selectedTask.value = task;
  isEditModal.value = true;
}

function deleteModal(task) {
  selectedTask.value = task;
  isDeleteModal.value = true;
}
</script>

<template>
  <div class="flex justify-center w-screen items-center h-screen bg-red-500">
    <fwb-table hoverable>
      <fwb-table-head>
        <fwb-table-head-cell>
          <fwb-button class="bg-white hover:bg-white" @click="showModal"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
            >
              <path
                fill="green"
                d="M5 21q-.825 0-1.413-.588T3 19V5q0-.825.588-1.413T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.588 1.413T19 21H5Zm7-9Zm-3 3v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4q0 .375-.138.738t-.437.662L13.25 15H9ZM21.025 4.4l-1.4-1.4l1.4 1.4ZM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575V13Zm6.5-6.5l-.725-.7l.725.7l.7.7l-.7-.7Z"
              />
            </svg>
          </fwb-button>
        </fwb-table-head-cell>
        <fwb-table-head-cell>Todo Name</fwb-table-head-cell>
        <fwb-table-head-cell>Description</fwb-table-head-cell>
        <fwb-table-head-cell>
          <span class="sr-only">Edit</span>
        </fwb-table-head-cell>
      </fwb-table-head>
      <fwb-table-body>
        <fwb-table-row
          class="divide-x divide-y"
          v-for="(task, index) in tasks"
          :key="index"
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
            <fwb-a :href="'javascript:void(0)'" @click="() => editModal(task)">
              <fwb-button class="bg-white hover:bg-white">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="15"
                  height="15"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="orange"
                    d="M5 19h1.425L16.2 9.225L14.775 7.8L5 17.575V19Zm-2 2v-4.25L17.625 2.175L21.8 6.45L7.25 21H3ZM19 6.4L17.6 5L19 6.4Zm-3.525 2.125l-.7-.725L16.2 9.225l-.725-.7Z"
                  />
                </svg>
              </fwb-button>
            </fwb-a>

            <fwb-a :href="'javascript:void(0)'" @click="() => deleteModal(task)">
              <fwb-button class="bg-white hover:bg-white">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="15"
                  height="15"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="red"
                    d="M6 20V6H5V5h4v-.77h6V5h4v1h-1v14H6Zm1-1h10V6H7v13Zm2.808-2h1V8h-1v9Zm3.384 0h1V8h-1v9ZM7 6v13V6Z"
                  />
                </svg>
              </fwb-button>
            </fwb-a>
          </fwb-table-cell>
        </fwb-table-row>
      </fwb-table-body>
    </fwb-table>
  </div>
  <fwb-modal v-if="isShowModal" @close="closeModal">
    <template #header>
      <div class="flex items-center text-lg text-gray-900">Add Todo</div>
    </template>
    <template #body>
      <form @submit.prevent="addTask">
        <fwb-input
          v-model="newTask.title"
          label="Todo"
          placeholder="Enter Todo"
          required
        />
        <fwb-input
          v-model="newTask.description"
          label="Description"
          placeholder="Enter Description"
          required
        />
        <br />
        <fwb-button color="default" outline class="py-2 px-4"
          >Add Task</fwb-button
        >
      </form>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeModal" color="red"> Close </fwb-button>
      </div>
    </template>
  </fwb-modal>

  <fwb-modal v-if="isEditModal" @close="closeEditModal">
    <template #header>
      <div class="flex items-center text-lg text-gray-900">Edit Todo</div>
    </template>
    <template #body>
      <form @submit.prevent="updateTask">
        <fwb-input
          v-model="newTask.title"
          label="Todo"
          placeholder="Enter Todo"
          required
        />
        <fwb-input
          v-model="newTask.description"
          label="Description"
          placeholder="Enter Description"
          required
        />
        <br />
        <fwb-button color="default" outline class="py-2 px-4"
          >Update Task</fwb-button
        >
      </form>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeEditModal" color="red"> Close </fwb-button>
      </div>
    </template>
  </fwb-modal>
  
  <fwb-modal v-if="isDeleteModal" @close="closeDeleteModal">
  <template #header>
    <div class="flex items-center text-lg text-red-500">
      <!-- You can add an icon or text indicating it's a delete confirmation modal -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
      <span class="ml-2">Delete Confirmation</span>
    </div>
  </template>
  <template #body>
    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
      Are you sure you want to delete this task? This action cannot be undone.
    </p>
  </template>
  <template #footer>
    <div class="flex justify-end">
      <fwb-button @click="closeDeleteModal" color="green">
        Cancel
      </fwb-button>
      <fwb-button @click="deleteTask" style="margin-left: 5px;" color="red">
        Delete
      </fwb-button>
    </div>
  </template>
</fwb-modal>

</template>
