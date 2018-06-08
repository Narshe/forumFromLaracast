<template lang="html">
    <div>
        <div v-for="(reply,index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <p v-if="$parent.locked">
            The thread has been locked
        </p>
        <new-reply @updated="updateItems" v-else></new-reply>
        <paginator :dataSet="dataSet" @updated="fetch"></paginator>
    </div>
</template>

<script>

    import Reply from './Reply.vue'
    import Paginator from './Paginator.vue'
    import NewReply from './NewReply.vue'

    export default {
        components: {Reply, Paginator, NewReply},
        data() {

            return {

                items: [],
                dataSet: ''
            }
        },
        created() {
            this.fetch()
        },
        methods: {

            fetch(page) {

                axios.get(this.url(page))
                    .then(this.refresh)
            },

            refresh({data}) {

                this.dataSet = data
                this.items = data.data
            },

            url(page) {

                if (!page) {

                    let query = location.search.match(/page=(\d+)/)
                    page = query ? query[1] : 1
                }
                return `${location.pathname}/replies?page=${page}`
            },
            remove(index) {

                this.items.splice(index,1)

                this.$emit('removed')
            },
            updateItems(data) {

                this.items.push(data)
            }
        }
    }
</script>
