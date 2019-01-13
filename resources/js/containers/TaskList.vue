<template xmlns:v-touch="http://www.w3.org/1999/xhtml">
    <div class="mt-4 position-relative">
        <div class="spinner" v-if="loading">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div>
            <div class="task-list-item d-flex p-2">
                <div class="col-6 text-center d-flex justify-content-center">
                    <div style="max-width: 200px">
                        <month-select :value="$store.getters.getCurrentMonth" :onChange="handleChangeMonth"></month-select>
                    </div>
                </div>
                <div class="col-6 text-center"><strong>Σ = {{ sum }}</strong></div>
            </div>
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
                        <div class="col-5 col-sm-5 task-list-item__col-name">
                            <div><small class="text-muted" :title="task.created_at">{{ $helpers.formatDate(task.created_at) }} (#{{ task.id }})</small></div>
                            <router-link :to="{name: 'project_task_view', params: {project_id: task.project_id, task_id: task.id}}">{{ task.name}}</router-link>
                        </div>
                        <div class="col-2 col-sm-2 text-success task-list-item__col-rate px-0">{{ task.rate }}</div>
                        <div class="col-5 col-sm-3 pr-0">
                            <select :value="task.status" :class="statusClass(task.status)" @change="(e) => handleStatusChange(e, task)">
                                <option v-for="status in statuses" :value="status.id" :class="status.class">{{ status.name }}</option>
                            </select>
                        </div>
                        <div class="control-block col-sm-2">
                            <div class="row h-100">
                                <router-link class="text-white bg-primary col-6 d-flex align-items-center justify-content-center"
                                             :to="{name: 'project_task', params: {project_id: task.project_id,task_id: task.id}}"
                                >
                                    <i class="fas fa-pencil-alt"></i>
                                </router-link>
                                <a class="text-white col-6 bg-danger d-flex align-items-center justify-content-center"
                                   href="#"
                                   @click="(e) => handleDelete(e, task.id)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MonthSelect from "../components/MonthSelect";

    export default {
        components: {MonthSelect},
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
            handleChangeMonth(month) {
                this.$router.push({
                    name: 'project',
                    params: {
                        project_id: this.$route.params.project_id,
                        filter_status: this.$route.params.filter_status,
                        date: month,
                    }
                });
            }
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
            sum() {

                let sum_hrn = 0;
                let sum_d = 0;

                this.tasks.forEach(item => {
                    if(!item.rate) return;

                    let found = item.rate.match(/(.+)грн/);
                    if(found)
                        sum_hrn += parseInt(found[1]);
                    found = item.rate.match(/(.+)\$/);
                    if(found)
                        sum_d += parseInt(found[1]);
                });

                let result = `${sum_hrn} грн`;

                if(sum_d > 0)
                    result += `, $${sum_d}`;

                return result;
            }
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
        top: -25px;
        animation: spin infinite 4000ms;
    }

    .swipe-row {
        transition: transform 300ms;
    }

    .swipe-row.opened {
        transform: translate(-100px);
    }

    .control-block {
        /*height: 37px;*/
        /*padding: 0;*/
        margin: 0;
        align-self: stretch;
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