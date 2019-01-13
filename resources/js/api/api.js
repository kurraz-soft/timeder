import axios from 'axios';

export async function apiProjectList(month) {
    return (await axios.get(API_URLS.projects.list.replace('_month_',month))).data;
}

export async function apiProjectDetail(id) {
    return (await axios.get(API_URLS.projects.detail.replace('#id#',id))).data;
}

export async function apiProjectNew(data) {
    return (await axios.post(API_URLS.projects.store, data)).data;
}

export async function apiProjectUpdate(id, data) {
    return (await axios.post(API_URLS.projects.update.replace('#id#', id), data, { headers: {'Content-Type': 'multipart/form-data' }})).data;
}

export async function apiTaskNew(data) {
    return (await axios.post(API_URLS.tasks.store, data)).data;
}

export async function apiUserList() {
    return (await axios.get(API_URLS.users.list)).data;
}

export async function apiLoadTask(id) {
    return (await axios.get(API_URLS.tasks.detail.replace('#id#', id))).data;
}

export async function apiDeleteTask(id) {
    return (await axios.delete(API_URLS.tasks.delete.replace('#id#', id))).data;
}

export async function apiDeleteFile(id) {
    return (await axios.delete(API_URLS.files.delete.replace('#id#', id))).data;
}