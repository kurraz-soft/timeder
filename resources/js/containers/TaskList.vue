<template>
    <div class="mt-4 position-relative">
        <div class="spinner" v-if="loading">
            <i class="fas fa-sync-alt"></i>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>Task</th>
                <th style="width: 100px">Rate</th>
                <th style="width: 200px">
                    <select class="form-control" v-model="c_filter_status">
                        <option v-for="status in task_statuses" :value="status.id">{{ status.name }}</option>
                    </select>
                </th>
                <th style="width: 100px"></th>
            </tr>
            <tr v-for="task in tasks">
                <td>{{ task.name}}</td>
                <td class="text-success">{{ task.rate }}</td>
                <td>
                    <select :value="task.status" :class="statusClass(task.status)" @change="(e) => handleStatusChange(e, task)">
                        <option v-for="status in statuses" :value="status.id" :class="status.class">{{ status.name }}</option>
                    </select>
                </td>
                <td>
                    <div class="row">
                        <div class="col-6">
                            <router-link :to="`/project/${task.project_id}/task/${task.id}`"><i class="fas fa-pencil-alt"></i></router-link>
                        </div>
                        <div class="col-6">
                            <a href="#" @click="(e) => handleDelete(e, task.id)"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        name: "task-list",
        props: {
            tasks: Array,
            filter_status: String,
        },
        methods: {
            handleDelete(e, id) {
                e.preventDefault();

                if(confirm('Are you sure?'))
                    this.$store.dispatch('deleteTask', id);
            },
            handleStatusChange(e, task) {
                this.$store.dispatch('changeTaskStatus', {task, status: e.target.value});
            }
        },
        computed: {
            task_statuses() {
                return this.$store.getters.getFilterStatusOptions;
            },
            c_filter_status: {
                get() {
                    return this.filter_status;
                },
                set(val) {
                    this.$router.push({name: 'project', params: { project_id:  this.$route.params.project_id, filter_status: val}});
                }
            },
            statuses()
            {
                return TASK_STATUSES;
            },
            statusClass()
            {
                return (status_id) => {
                    const c = this.statuses.find((item) => {
                        return item.id === status_id;
                    }).class;

                    let ret = {'form-control': true};

                    ret[c] = true;
                    return ret;
                };
            },
            loading() {
                return this.$store.getters.getIsLoading;
            }
        }
    }
</script>

<style scoped>
    .spinner {
        position: absolute;
        right: 3px;
        top: -33px;
    }
</style>