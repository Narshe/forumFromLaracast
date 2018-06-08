<template>
    <button :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default']
            },

            endpoint() {
                return '/replies/' + this.reply.id + '/favorites'
            }
        },
        methods: {
            toggle() {

                return this.isFavorited ? this.destroy() : this.create()

            },

            create() {
                axios.post(this.endpoint)
                .then(function(){

                    flash("Reply favorited", 'success')

                })
                this.isFavorited = true;
                this.favoritesCount++
            },

            destroy() {
                axios.delete(this.endpoint)
                .then(function() {

                    flash("Reply unfavorited", 'success')

                })
                this.isFavorited = false;
                this.favoritesCount--
            }
        }
    }
</script>
