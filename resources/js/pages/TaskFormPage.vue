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
            <form class="mt-2" @submit="handleSubmit" enctype="multipart/form-data">
                <input type="hidden" name="id" :value="task.id" />
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label>Проект</label>
                        <select required class="form-control" v-model="task.project_id" name="project_id">
                            <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label>Статус</label>
                        <select name="status" v-model="task.status" class="form-control">
                            <option v-for="status in task_statuses" :value="status.id">{{ status.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Заголовок</label>
                    <input required name="name" type="text" class="form-control" placeholder="Заголовок задачи" v-model="task.name">
                </div>
                <div class="form-group">
                    <label>Рейт</label>
                    <input name="rate" type="text" class="form-control" placeholder="Рейт" v-model="task.rate">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea rows="8" class="form-control" name="description" placeholder="Описание задачи" v-model="task.description"></textarea>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-3 mb-3" v-for="file in task.files">
                        <div class="card">
                            <div class="card-block d-flex justify-content-between align-items-center">
                                <a class="col-9 file-lnk" target="_blank" :href="file.path" :title="file.orig_name">{{ file.orig_name }}</a>
                                <button title="Delete file" class="btn" @click="(e) => handleDeleteFile(e, file.id)"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="file" multiple name="attachments[]"/>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label>Создатель</label>
                        <select required class="form-control" v-model="task.owner_id" name="owner_id">
                            <option v-for="user in users" :value="user.id">{{ user.name }} <{{ user.email }}></option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label>Исполнитель</label>
                        <select required class="form-control" v-model="task.doer_id" name="doer_id">
                            <option v-for="user in users" :value="user.id">{{ user.name }} <{{ user.email }}></option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        name: "task-form-page",
        created() {
            this.clearData();
        },
        computed: {
            users() {
                return this.$store.getters.getUsers;
            },
            projects() {
                return this.$store.getters.getProjects;
            },
            back_url() {

                //return this.$store.getters.getCurrentProject ? `/project/${this.$store.getters.getCurrentProject.id}` : '/'
                return this.$store.getters.getCurrentProject ?
                    {
                        name: 'project',
                        params: {
                            project_id: this.$store.getters.getCurrentProject.id,
                            filter_status: this.$store.getters.defaultFilterStatusId,
                        }
                    }:
                    '/';
            },
            loading() {
                return this.$store.getters.getIsLoading;
            },
            task: {
                get() {
                    if(parseInt(this.$route.params.task_id) && !this.$store.state.taskFormData.filled)
                        return this.$store.getters.getTask(parseInt(this.$route.params.project_id), parseInt(this.$route.params.task_id));
                    else
                        return this.$store.state.taskFormData;
                },
                set(val) {
                    val.filled = true;
                    this.$store.commit('updTaskFormData', val);
                },
            },
            task_statuses() {
                return TASK_STATUSES;
            }
        },
        methods: {
            async handleSubmit(e) {
                e.preventDefault();

                await this.$store.dispatch('newTask', new FormData(e.target));
            },
            clearData() {
                this.$store.commit('clearTaskFormData', this.$route.params.project_id);
            },
            handleDeleteFile(e, id) {

                if(confirm('Are you sure?'))
                    this.$store.dispatch('deleteFile', id);
            }
        },
        watch: {
            '$route': 'clearData',
        }
    }
</script>

<style scoped>
    .card {
        white-space: nowrap;
    }
    .file-lnk {
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card .btn {
        width: 50px;
    }
</style>