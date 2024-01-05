<template>
  <div>
    <h5 id="associating-form-text-with-form-controls">Title:</h5>
    <h6>{{ task.title }}</h6>
    <h5 id="associating-form-text-with-form-controls">Description:</h5>
    <p>{{ task.description }}</p>
    <h5 id="associating-form-text-with-form-controls">Is Completed:</h5>
    <p>{{ task.is_completed }}</p>
    <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-primary">Edit</router-link>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      task: {}
    }
  },
  async created() {
    try {
      const response = await axios.get(`/api/tasks/${this.$route.params.id}`);
      this.task = response.data['data'];
    } catch (error) {
      alert('Bad response form server!');
    }
  }
}
</script>