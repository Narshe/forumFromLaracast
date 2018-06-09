<template lang="html">
    <div class="form-group">
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor ref="trix" id="body" input="trix" contenteditable></trix-editor>
    </div>
</template>

<script>

    import 'jquery.caret'
    import Trix from 'trix'
    import 'at.js'
    export default {
        props: ['name', 'value', 'shouldClear'],
        mounted () {

                $(document).on('trix-initialize', function(e){
                    $(e.target).atwho({
                    at: "@",
                    delay: 750,
                    callbacks: {
                        remoteFilter: function(query, callback) {
                          $.getJSON("/api/users", {q: query}, function(usernames) {
                            callback(usernames)
                          });
                      },
                    },

                })
            });

            this.$refs.trix.addEventListener('trix-change', e => {
                this.$emit('input', e.target.innerHTML)
            })

            this.$watch('shouldClear', () => {
                this.$refs.trix.value = ''
            })
        }
    }
</script>
