<template>
    <div>
        <!--<select @change="handleChange" :value="value">
            <option v-for="variant in variants" :value="variant.value">{{ variant.text }}</option>
        </select>-->
        <vue-monthly-picker @selected="handleChange" inputClass="label" dateFormat="MM-YYYY" :value="momentValue"></vue-monthly-picker>
    </div>
</template>

<script>
    import VueMonthlyPicker from "vue-monthly-picker/src/components/VueMonthlyPicker";
    import moment from 'moment';

    export default {
        components: {VueMonthlyPicker},
        name: "month-select",
        computed: {
            variants()
            {
                return [
                    {text: '01-2019', value: '01-2019'},
                    {text: '12-2018', value: '12-2018'},
                    {text: '11-2018', value: '11-2018'},
                ];
            },
            momentValue()
            {
                return moment(this.value, 'MM-YYYY');
            }
        },
        methods: {
            handleChange(value) {
                this.onChange(moment(value).format('MM-YYYY'));
            }
        },

        props: {
            value: String,
            onChange: Function,
        }
    }
</script>

<style scoped>

</style>