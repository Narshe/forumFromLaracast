<script>

    import Replies from '../components/Replies.vue'
    import SubscribeButton from '../components/SubscribeButton.vue'

    export default {
        components: {Replies, SubscribeButton},
        props: ['thread'],
        data() {

            return {

                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                }
            }
        },
        methods: {

            toggleLock() {

                axios[this.locked ? 'delete' : 'post'](`/locked-threads/${this.thread.id}`).then((response) => {

                    flash(response.data, 'success')
                 })

                 this.locked = !this.locked
            },

            update () {

                axios.patch(`/threads/${this.thread.id}`, this.form).then( (response) => {

                    this.title = this.form.title
                    this.body = this.form.body
                    flash("Thread updated", 'success')

                })

                this.editing = false
            }

        }
    }
</script>
