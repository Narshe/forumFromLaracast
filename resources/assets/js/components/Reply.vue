<template>

    <div :id="'reply-'+id" class="card">
        <div class="card-header" :class="isBest ? 'bg-success text-white' : ''">

            <a :href="'/profiles/'+reply.owner.name" v-text="reply.owner.name">

            </a>
            said
            {{ reply.created_at }}

            <div v-if="signedIn">
                <favorite :reply="reply"></favorite>
            </div>


            <!-- @endcan -->
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <wysiwyg v-model="body" name="body"></wysiwyg>
                </div>
                <button class="btn btn-warning btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="cancel">Cancel</button>
            </div>

            <div v-else v-html="body"></div>
        </div>
        <div class="card-footer" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-warning btn-sm" @click="edit">Edit</button>
                <button type="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
            <div v-show="!isBest && this.authorize('owns', reply.thread)">
                <button type="btn btn-default btn-sm" @click="markAsBestReply">Best reply</button>
            </div>
        </div>
    </div>

</template>



<script>
    import Favorite from './Favorite.vue'

    export default {
        props: ['reply'],
        components: {Favorite},

        data() {
            return {
                id: this.reply.id,
                editing:false,
                body: this.reply.body,
                oldBody: this.reply.body,
                isBest: this.reply.isBest,
            }
        },
        created() {

            window.events.$on('best-reply-selected', id => {

                this.isBest = (id === this.id)
            })
        },
        methods: {
            markAsBestReply() {
                window.events.$emit('best-reply-selected', this.id)
                axios.post('/replies/' + this.id + '/best')
            },
            edit() {

                this.editing = true
                this.oldBody = this.body
            },
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                .then((data) => {
                    flash("Message edited", 'success')
                })
                .catch((error) => {

                    this.body = this.oldBody
                    flash(error.response.data, 'danger')
                });

                this.editing = false;

            },
            cancel() {

                this.body = this.oldBody
                this.editing=false
            },
            destroy() {

                axios.delete('/replies/'+this.id).then(function() {
                    flash("Message deleted", 'success')
                });

                this.$emit('deleted', this.id)
            }
        }
    }
</script>

<style media="screen">

</style>
