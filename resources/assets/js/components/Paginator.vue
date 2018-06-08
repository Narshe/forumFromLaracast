<template lang="html">
    <div>
        <nav aria-label="..." v-if="shouldPaginate">
            <ul class="pagination pagination-sm">
                <li :class="pageItem(pageIndex)" v-for="(pageIndex) in totalPages">
                    <a class="page-link" href="#" @click.prevent="page = pageIndex" tabindex="-1">{{ pageIndex }}</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>

export default {

    props: ['dataSet'],
    data() {

        return {

            page: 1,
            prevUrl: false,
            nextUrl: false
        }
    },
    watch: {

        dataSet() {

            this.page = this.dataSet.current_page
            this.prevUrl = this.dataSet.prev_page_url
            this.nextUrl = this.dataSet.next_page_url
        },

        page() {
            this.broadcast()
        }
    },
    computed: {

        shouldPaginate() {

            return !! this.prevUrl || !! this.nextUrl
        },

        totalPages() {

            return Math.ceil(this.dataSet.total / this.dataSet.per_page)
        }
    },
    methods: {

        pageItem(index) {
            return ['page-item', this.dataSet.current_page == index ? 'disabled' : '']
        },
        broadcast() {

            this.$emit('updated', this.page)
        }
    }
}

</script>

<style lang="css">
</style>
