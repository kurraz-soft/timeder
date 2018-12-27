import moment from 'moment';

export default {
    formatDate(date) {
        const d = new Date(moment(date).toDate());
        return d.toLocaleDateString('ru-RU');
    }
}