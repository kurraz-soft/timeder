<template>
    <div>
        <div v-if="project">
            <h4 class="text-center">{{ project.name }}</h4>
            <div>
                <router-link :to="link_new_task" class="btn col-12 col-sm-1 btn-success ">New Task</router-link>
            </div>
            <task-list :tasks="tasks" :filter_status="$route.params.filter_status"></task-list>
        </div>
    </div>
</template>

<script>
    export default {
        name: "project-tasks",
        computed: {
            project() {
                return this.$store.getters.getCurrentProject;
            },
            tasks() {
                return this.project.tasks.filter((item) => {
                    switch (this.$route.params.filter_status)
                    {
                        case 'all_but_closed':
                            return item.status !== TASK_STATUS_CLOSED;
                        case 'all':
                            return true;
                            break;
                        default:
                            return item.status === parseInt(this.$route.params.filter_status);
                    }
                });
            },
            link_new_task() {
                return this.project ? `/project/${this.project.id}/task/new` : '/task/new';
            },
        },
    }
</script>

<style scoped>

</style>