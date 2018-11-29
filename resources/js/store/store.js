import Vue from 'vue';
import Vuex from 'vuex';

import {
    apiDeleteTask, apiLoadTask, apiProjectDetail, apiProjectList, apiProjectNew, apiTaskNew,
    apiUserList
} from '../api/api';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        projects: [],
        current_project_id: null,
        loadingCnt: 0,
        taskFormData: {},
    },
    getters: {
        getTasks: (state) => {

            let tasks = [];

            state.projects.forEach((item) => {
                tasks = tasks.concat(item.tasks);
            });

            return tasks;
        },
        getProjects: (state) => state.projects,
        getUsers: () => USER_LIST,
        getCurrentProject: (state) => {
            return state.projects.find((item) => {
                if(item.id === state.current_project_id) return item;
            });
        },
        getTask: (state) => {
            return (project_id, task_id) => {
                const project = state.projects.find(project => project.id === project_id);
                if(!project) return null;
                const task = project.tasks.find(task => task.id === task_id);
                if(!task) return state.taskFormData;
                return task;
            };
        },
        getCurrentUser: () => USER,
        getIsLoading: (state) => !!state.loadingCnt,

        getFilterStatusOptions: () => {
            return [
                {
                    id: 'all_but_closed',
                    name: 'Незакрытые',
                },
                {
                    id: 'all',
                    name: 'Все',
                },
            ].concat(TASK_STATUSES);
        },
        defaultFilterStatusId: () => 'all_but_closed',
    },
    actions: {

        async dataUpdate(context) {
            context.commit('incLoadingCnt');
            context.commit('loadProjects', await apiProjectList());
            context.commit('decLoadingCnt');
        },

        async loadProjectList(context) {
            context.commit('incLoadingCnt');
            context.commit('loadProjects', await apiProjectList());
            context.commit('decLoadingCnt');
        },

        async newProject(context, data)
        {
            context.commit('incLoadingCnt');
            try {
                await apiProjectNew(data);
                context.dispatch('loadProjectList');
                context.commit('decLoadingCnt');
            }catch (e)
            {
                alert(e);
            }
        },

        async newTask(context, data)
        {
            context.commit('incLoadingCnt');
            try {
                await apiTaskNew(data);
                context.dispatch('loadProjectList');
                context.commit('decLoadingCnt');
            }catch (e)
            {
                alert(e);
            }
        },

        changeProject(context, project_id) {
            context.commit('incLoadingCnt');
            context.commit('changeProject', project_id);
            context.commit('decLoadingCnt');
        },

        async deleteTask(context, id) {
            await apiDeleteTask(id);
            context.dispatch('loadProjectList');
        },

        async changeTaskStatus(context, payload) {
            const task = payload.task;
            task.status = payload.status;
            await context.dispatch('newTask', task);
        }
    },
    mutations: {
        dataUpdate(state, payload) {
            //mutate data
        },
        loadProjects(state, payload) {
            state.projects = payload;
        },
        changeProject(state, payload) {
            state.current_project_id = payload;
        },
        incLoadingCnt(state) {
            state.loadingCnt++;
        },
        decLoadingCnt(state) {
            state.loadingCnt--;
            if(state.loadingCnt < 0) state.loadingCnt = 0;
        },
        updTaskFormData(state, payload) {
            state.taskFormData = payload;
        },
        clearTaskFormData(state, project_id = null) {
            state.taskFormData = {
                filled: false,
                project_id: project_id,
                doer_id: USER.id,
                owner_id: USER.id,
                status: this.getters.defaultFilterStatusId,
            };
        },
    },
});