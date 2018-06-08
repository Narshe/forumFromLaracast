<template lang="html">
    <div class="dropdown" v-if="notificationsNumber">
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        Notifications - {{ notificationsNumber }}
      </button>
  <div class="dropdown-menu">
    <a
        v-for="notification in notifications"
        class="dropdown-item"
        :href="notification.data.link"
        v-text="notification.data.message"
        @click="markAsRead(notification)"
        >
    </a>
  </div>
</div>
</template>

<script>
export default {

    data() {
        return {
            notifications: []
        }
    },
    computed: {

        notificationsNumber() {

            return this.notifications.length
        }
    },
    created() {
        axios.get("/profiles/"+ window.App.user.name +"/notifications")
            .then(response => this.notifications = response.data)
    },
    methods: {

        markAsRead(notification) {

            axios.delete("/profiles/"+window.App.user.name+"/notifications/"+notification.id+"")
        }
    }
}
</script>

<style lang="css">
</style>
