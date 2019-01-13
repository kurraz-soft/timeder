import DashboardPage from './pages/DashboardPage'
import TaskFormPage from './pages/TaskFormPage'
import ProjectTasks from './pages/ProjectTasks'
import TaskViewPage from './pages/TaskViewPage'

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
        path: '/:date/project/:project_id/s=:filter_status',
        name: 'project',
        component: ProjectTasks,
    },
    {
        path: '/:date/project/:project_id/task/:task_id',
        name: 'project_task',
        component: TaskFormPage,
    },
    {
        path: '/:date/project/:project_id/task_view/:task_id',
        name: 'project_task_view',
        component: TaskViewPage,
    },
]