<template>

    <div>

        <wysiwyg name="body" v-model="body" :shouldClear="completed"></wysiwyg>
        <button class="btn btn-primary" @click="store" type="submit">Répondre</button>
    </div>


</template>

<script>

    import 'jquery.caret'
    import 'at.js'

    export default {

        data() {

            return {

                body: '',
                completed:false

            }
        },
        mounted() {

            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function(query, callback) {
                      $.getJSON("/api/users", {q: query}, function(usernames) {
                        callback(usernames)
                      });
                    }
                }
            })
        },
        methods: {

            store() {

                if (this.body !== '') {

                    let path = window.location.pathname

                    axios.post(`${path}/replies`, { body: this.body})
                    .catch(error => {

                        flash(error.response.data, 'danger')
                    })
                    .then((response) => {

                        this.$emit('updated', response.data)
                        this.body = ''
                        flash("Message added", 'success')
                        this.completed = true
                    });

                }
            }
        }
    }
</script>
