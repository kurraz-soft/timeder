export default {
    formatDate(date) {
        const d = new Date(date);
        return d.toLocaleDateString('ru-RU');
    }
}