<template>
    <div class="alert-box">
        <transition name="fade">
            <div :class="classes" role="alert" v-show="show" @click="show=false">
                {{ body }}
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },
        created() {
            if (this.message) {

                this.flash()
            }

            window.events.$on(
                'flash', data => this.flash(data)
            )
        },
        computed: {

            classes() {

                return 'alert alert-'+this.level
            }
        },
        methods: {

            flash(data) {

                if (data) {

                    this.body = data.message
                    this.level = data.level
                }
                this.show = true
            }
        }
    }
</script>

<style media="screen">

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to  {
        opacity: 0;
    }

    .alert-box{

        position: fixed;
        right:0;
        top:50%;

    }
</style>
