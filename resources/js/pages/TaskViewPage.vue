<template>
    <div>
        <div v-if="loading">
            Loading...
        </div>
        <div v-if="!loading">
            <div>
                <router-link :to="back_url" class="btn btn-outline-secondary px-4" role="button"><i class="fas fa-long-arrow-alt-left"></i></router-link>
            </div>
            <hr>
            <div>
                <h2>{{ task.name }}</h2>
                <h4 class="text-success">{{ task.rate }}</h4>
                <div>
                    <p class="text-pre">{{ task.description }}</p>
                </div>
                <div v-if="task.files.length">
                    <strong>Attachments</strong>
                    <ul>
                        <li v-for="file in task.files">
                            <a :href="file.path">{{ file.orig_name }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "task-view-page",
        computed: {
            task() {
                return this.$store.getters.getTask(parseInt(this.$route.params.project_id),parseInt(this.$route.params.task_id));
            },
            back_url() {
                return {
                    name: 'project',
                    params: {
                        project_id: this.$route.params.project_id,
                        filter_status: this.$store.getters.defaultFilterStatusId,
                    }
                };
            },
            loading() {
                return this.$store.getters.getIsLoading;
            },
        },
    }
</script>

<style scoped>

</style>