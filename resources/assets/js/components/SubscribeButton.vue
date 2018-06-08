<template lang="html">
    <div v-if="signedIn">
        <button v-if="subscribed" class="btn btn-primary" @click="unsubscribe">unsubscribe</button>
        <button v-else class="btn btn-primary" @click="subscribe">Subscribe</button>
    </div>
</template>

<script>
export default {

    props: ['data'],
    data() {

        return {

            subscribed: this.data
        }

    },
    methods: {

        subscribe() {

            axios.post(location.pathname+'/subscriptions')
            this.subscribed = true
            flash("Subscription successfull", 'success')
        },

        unsubscribe() {

            axios.delete(location.pathname+'/subscriptions')
            this.subscribed = false
            flash("Unsubscription successfull", 'success')
        }
    }
}
</script>
