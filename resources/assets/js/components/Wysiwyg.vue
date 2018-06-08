<template lang="html">
    <div class="form-group">
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor ref="trix" id="body" input="trix" class="test"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix'
    export default {
        props: ['name', 'value', 'shouldClear'],
        mounted () {

            this.$refs.trix.addEventListener('trix-change', e => {
                this.$emit('input', e.target.innerHTML)
            })

            this.$watch('shouldClear', () => {
                this.$refs.trix.value = ''
            })
        }
    }
</script>
