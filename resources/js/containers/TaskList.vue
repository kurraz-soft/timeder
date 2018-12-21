<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <div class="mt-4 position-relative">
        <div class="spinner" v-if="loading">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div>
            <div class="task-list-item row flex-nowrap mx-0 align-items-center" style="overflow: hidden">
                <div class="col-5 col-sm-5"><strong>Task</strong></div>
                <div class="col-2 col-sm-2 px-0"><strong>Rate</strong></div>
                <div class="col-5 col-sm-3 pr-0">
                    <select class="form-control" v-model="c_filter_status">
                        <option v-for="status in task_statuses" :value="status.id">{{ status.name }}</option>
                    </select>
                </div>
                <div class="control-block"></div>
            </div>
            <div v-for="(task, index) in tasks" class="task-list-item">
                <div style="width: 100%; overflow: hidden">
                    <div
                        v-touch:swipe.left="() => handleSwipeLeft(index)"
                        v-touch:swipe.right="() => handleSwipeRight(index)"
                        class="row align-items-center swipe-row flex-nowrap mx-0"
                        :class="{opened: tasks_opened[index]}"
                    >
                        <div class="col-5 col-sm-5 task-list-item__col-name"><router-link :to="{name: 'project_task_view', params: {project_id: task.project_id, task_id: task.id}}">{{ task.name}}</router-link></div>
                        <div class="col-2 col-sm-2 text-success task-list-item__col-rate px-0">{{ task.rate }}</div>
                        <div class="col-5 col-sm-3 pr-0">
                            <select :value="task.status" :class="statusClass(task.status)" @change="(e) => handleStatusChange(e, task)">
                                <option v-for="status in statuses" :value="status.id" :class="status.class">{{ status.name }}</option>
                            </select>
                        </div>
                        <div class="control-block col-sm-2">
                            <div class="d-flex" style="height: 100%">
                                <div class="col-6 bg-primary d-flex align-items-center justify-content-center">
                                    <div style="height: 23px">
                                        <router-link class="text-white" :to="`/project/${task.project_id}/task/${task.id}`"><i class="fas fa-pencil-alt"></i></router-link>
                                    </div>
                                </div>
                                <div class="col-6 bg-danger d-flex align-items-center justify-content-center">
                                    <div style="height: 23px">
                                        <a class="text-white" href="#" @click="(e) => handleDelete(e, task.id)"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "task-list",
        data() {
            return {
                tasks_opened: {},
            };
        },
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
            },
            handleSwipeLeft(index) {
                this.tasks_opened = {
                    ...this.tasks_opened,
                    [index]: true,
                };
            },
            handleSwipeRight(index) {
                this.tasks_opened = {
                    ...this.tasks_opened,
                    [index]: false,
                };
            },
        },
        watch: {
            '$route'(to, from) {
                this.tasks_opened = {};
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
            },
        }
    }
</script>

<style scoped>

    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }

    .spinner {
        position: absolute;
        right: 3px;
        top: -33px;
        animation: spin infinite 4000ms;
    }

    .swipe-row {
        transition: transform 300ms;
    }

    .swipe-row.opened {
        transform: translate(-100px);
    }

    .control-block {
        height: 37px;
        padding: 0 0 0 1px;
        margin: 0;
    }

    @media screen and (max-width: 720px){
        .control-block {
            max-width: 100px;
            flex: 0 0 100px;
        }
    }

    .task-list-item {
        outline: 1px solid #dee2e6;
        margin-top: 1px;
    }

    .task-list-item__col-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 16px;
    }

    .task-list-item__col-rate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    select {
        border-radius: 0;
        outline-style:none;
        box-shadow:none;
        border-color:transparent;
    }
</style>