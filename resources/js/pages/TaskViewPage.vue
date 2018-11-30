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
                <div>
                    <p>{{ task.description }}</p>
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
                return this.$store.getters.getTask(this.$route.params.project_id,this.$route.params.task_id);
            },
            back_url() {
                return {
                    name: 'project',
                    params: {
                        project_id: this.task.project_id,
                        filter_status: this.$store.getters.defaultFilterStatusId,
                    }
                };
            }
        },
    }
</script>

<style scoped>

</style>