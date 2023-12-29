<template>
  <div>
    <select @change="fetchTasks" v-model="selected">
      <option value="3">All</option>
      <option value="1">Completed</option>
      <option value="0">Not Completed</option>
    </select>
    <table class="table">
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
            <button v-if="task.is_completed" @click="changeStatus(task)" type="button" class="p-2 col border btn btn-dark">Uncompleted</button>
            <button v-else @click="changeStatus(task)" type="button" class="p-2 col border btn btn-dark">Completed</button>
            <router-link :to="`/tasks/${task.id}`" class="p-2 col border btn btn-primary">View</router-link>
            <router-link :to="`/tasks/${task.id}/edit`" class="p-2 col border btn btn-success">Edit</router-link>
            <button @click="deleteTask(task.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      selected: 3,
      tasks: [],
      currentPage: 1,
      perPage: 15, // Set the number of items per page
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
        if (this.selected < 2) {
          params.is_completed = this.selected
        }
        const response = await axios.get('/api/tasks', {
          params: params,
        });
        this.tasks = response.data['data'];
      } catch (error) {
        console.error(error); // place to handle API errors
      }
    },
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter(task => task.id !== id);
      } catch (error) {
        console.error(error); // place to handle API errors
      }
    },
    changeStatus(task) {
      this.$store.commit('changeStatus', task)
    }
  }
}
</script>