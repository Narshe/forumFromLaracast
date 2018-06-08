<template lang="html">
    <div>
        <h1 v-text="user.name"></h1>


        <form v-if="canUpdate" method="POST" action="/api/users/user.name/avatar" enctype="multipart/form-data">
            <image-upload name="avatar" @loaded="onLoad"></image-upload>
        </form>

        <img :src="avatar" width="50" height="50">
    </div>
</template>

<script>
import ImageUpload from './ImageUpload.vue'

export default {
    components: {ImageUpload},
    props: ['user'],
    data() {
        return {
            avatar: this.user.avatar_path
        }
    },
    computed: {

        canUpdate() {

            return this.authorize(user => user.id === this.user.id)
        }
    },
    methods: {
        onLoad(avatar) {

            this.avatar = avatar.src
            this.persist(avatar.file)
        },
        persist(file) {

            let data = new FormData()
            data.append('avatar', file)

            axios.post(`/api/users/${this.user.name}/avatar`, data)
            .then(() => flash('Avatar uploaded', 'success'))
        }
    }
}
</script>
