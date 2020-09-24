<template>
    <div>
        <div class="w-100 h-64 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max"
                 alt="user background image"
                 class="object-cover w-full"
            >
        </div>
    </div>
</template>

<script>
export default {
    name: "Show",

    data: () => {
        return {
            user: null,
            loading: true
        }
    },

    mounted() {
        axios.get('/api/users/' + this.$route.params.userId)
            .then(res => {
                this.user = res.data
            })
            .catch(error => {
                console.log('Unable to fetch the user from the server.')
            })
            .finally(() => {
                this.loading = false
            })
        console.log(this.$route.params)

        axios.get('/api/posts/' + this.$route.params.userId)
            .then(res => {
                this.posts = res.data;
                this.loading = false;
            })
            .catch(error => {
                console.log('Unable to fetch posts')
                this.loading = false;
            })

    }
}
</script>

<style scoped>

</style>
