import DashboardPage from './pages/DashboardPage'
import TaskFormPage from './pages/TaskFormPage'
import ProjectTasks from './pages/ProjectTasks'

export default [
    {
        path: '/',
        component: DashboardPage,
    },
    {
        path: '/task/:task_id',
        component: TaskFormPage,
    },
    {
        path: '/project/:project_id/s=:filter_status',
        name: 'project',
        component: ProjectTasks,
    },
    {
        path: '/project/:project_id/task/:task_id',
        name: 'project_task',
        component: TaskFormPage,
    },
]