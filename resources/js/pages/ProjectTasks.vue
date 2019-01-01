<template>
    <div>
        <div v-if="project">
            <div class="d-flex justify-content-center">
                <form action="#" @submit.prevent="handleNameChange" enctype="application/x-www-form-urlencoded">
                    <input name="_method" type="hidden" value="PUT">
                    <h4><input-button ref="edit_name_input" name="name" :value="project.name">{{ project.name }}</input-button></h4>
                </form>
            </div>
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
        methods: {
            handleNameChange(e) {
                this.$store.dispatch('updProject', {id: this.project.id, data: new FormData(e.target)});
                this.$refs.edit_name_input.switchMode();
            }
        }
    }
</script>

<style scoped>

</style>