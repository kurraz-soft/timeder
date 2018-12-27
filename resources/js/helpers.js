export default {
    formatDate(date) {
        const d = new Date(Date.parse(date));
        return d.toLocaleDateString('ru-RU');
    }
}