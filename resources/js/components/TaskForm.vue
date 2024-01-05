<template>
  <div>
    <h2 v-if="isNewTask">Add Task</h2>
    <h2 v-else>Edit Task</h2>
    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input class="form-control" type="text" id="name" v-model="task.title" required />
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea class="form-control" id="description" v-model="task.description" required></textarea>
      </div>
      <div v-if="!isNewTask" class="mb-3">
        <label for="isCompleted" class="form-label">Task completed: </label>
        <input class="form-check-input" type="checkbox" id="isCompleted" v-model="task.is_completed" />
      </div>
      <button type="submit" v-if="isNewTask" class="btn btn-primary">Add Task</button>
      <button type="submit" v-else class="btn btn-primary">Update Task</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      task: {
        title: '',
        description: '',
        is_completed: false
      }
    }
  },
  computed: {
    isNewTask() {
      return !this.$route.path.includes('edit');
    }
  },
  async created() {
    if (!this.isNewTask) {
      const response = await axios.get(`/api/tasks/${this.$route.params.id}`);
      this.task = response.data['data'];
    }
  },
  methods: {
    async submitForm() {
      try {
        if (this.isNewTask) {
          await axios.post('/api/tasks', this.task);
        } else {
          await axios.patch(`/api/tasks/${this.$route.params.id}`, this.task);
        }
        await this.$router.push('/');
      } catch (error) {
        alert('Bad response form server!');
      }
    }
  }
}
</script>