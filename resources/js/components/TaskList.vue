<template>
  <div>
    <div class="col-sm-4">
      <select class="form-select form-select-sm mb-3" @change="fetchTasks" v-model="selected">
        <option value=3>All</option>
        <option value=1>Completed</option>
        <option value=0>Not Completed</option>
      </select>
    </div>
    <table class="table table-striped">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Completed</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="task in tasks" :key="task.id">
        <td>{{ task.id }}</td>
        <td>{{ task.title }}</td>
        <td v-if="task.description.length<40">{{ task.description }}</td>
        <td v-else>{{ task.description.substring(0, 40) + "..." }}</td>
        <td>{{ task.is_completed }}</td>
        <td>
          <div class="row gap-1">
            <button v-if="task.is_completed" @click="changeStatus(task)" type="button" class="p-2 col border btn btn-primary">Uncompleted</button>
            <button v-else @click="changeStatus(task)" type="button" class="p-2 col border btn btn-primary">Completed</button>
            <button @click="$router.push(`/tasks/${task.id}`)" type="button" class="p-2 col border btn btn-primary">View</button>
            <button @click="$router.push(`/tasks/${task.id}/edit`)" type="button" class="p-2 col border btn btn-primary">Edit</button>
            <button @click="deleteTask(task.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  <div class="m-4">
    <ul class="pagination justify-content-center">
      <li
          class="page-item"
          v-for="pageNumber in totalPages"
          :key="pageNumber"
          :class="{
          'page-item active': page === pageNumber
          }"
      >
        <a
            class="page-link"
            type="button"
            @click="changePage(pageNumber)"
        >
          {{ pageNumber }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      selected: 3,
      tasks: [],
      page: 1,
      perPage: 5, // Set the number of items per page
      totalPages: 0,
    }
  },
  async created() {
    await this.fetchTasks();
  },
  methods: {
    async fetchTasks() {
      try {
        const params = {};
        params.per_page = this.perPage;
        params.page = this.page;

        if (this.selected < 2) {
          params.is_completed = this.selected
        }
        const response = await axios.get('/api/tasks', {
          params: params,
        });
        this.tasks = response.data['data'];
        this.totalPages = response.data['meta']['last_page'];
      } catch (error) {
        alert('Bad response form server!');
      }
    },

    // page change
    changePage(pageNumber) {
      this.page = pageNumber;
    },

    // delete task
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter(task => task.id !== id);
      } catch (error) {
        alert('Bad response form server!');
      }
    },

    // change task completion status
    changeStatus(task) {
      this.$store.commit('changeStatus', task)
    }
  },

  // reload tasks on page changing
  watch: {
    page() {
      this.fetchTasks();
    }
  }
}
</script>