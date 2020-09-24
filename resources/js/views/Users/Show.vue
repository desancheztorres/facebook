<template>
    <div class="flex flex-col items-center">
        <div class="relative mb-8">
            <div class="w-100 h-64 overflow-hidden z-10">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max"
                     alt="user background image"
                     class="object-cover w-full"
                >
            </div>

            <div class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20">
                <div class="w-32">
                    <img src="https://pbs.twimg.com/profile_images/966027138225922048/z7AXDFbc_400x400.jpg" alt="user profile image" class="object-cover w-32 h-32 border-4 border-gray-200 rounded-full shadow-lg">
                </div>

                <p class="text-2xl text-gray-100 ml-4">
                    {{ user.data.attributes.name }}
                </p>
            </div>
        </div>

        <p v-if="postLoading">Loading posts...</p>
        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post" />

        <p v-if="!postLoading && posts.data.length < 1">No posts found. Get started...</p>
    </div>
</template>

<script>

import Post from "../../components/Post";

export default {
    name: "Show",

    components: {
      Post
    },

    data: () => {
        return {
            posts: null,
            user: null,
            userLoading: true,
            postLoading: true,
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
                this.userLoading = false
            });

        axios.get('/api/users/' + this.$route.params.userId + '/posts')
            .then(res => {
                this.posts = res.data;
            })
            .catch(error => {
                console.log('Unable to fetch posts')
            })
            .finally(() => {
                this.postLoading = false
            });
    }
}
</script>

<style scoped>

</style>
