<template>
    <select v-model="_value" class="form-control" name="project_id" @change="handleChange">
        <option value="">Все проекты</option>
        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
    </select>
</template>

<script>

    export default {
        name: "project-select",
        computed: {
            projects() {
                return this.$store.getters.getProjects;
            },
            _value: {
                get() {
                    return this.$store.getters.getCurrentProject ? this.$store.getters.getCurrentProject.id : '';
                },
                set(newValue) {
                    this.__value = newValue;
                },
            }
        },
        data() {
            return {
                __value: this.value,
            }
        },
        methods: {
            handleChange(){
                //this.$store.dispatch('changeProject', this.__value);
                if(this.__value)
                    this.$router.push({name: 'project', params: { project_id:  this.__value, filter_status: this.$store.getters.defaultFilterStatusId}});
                else
                    this.$router.push('/');

            }
        },
    }
</script>

<style scoped>
    @media screen and (min-width: 720px){
        select.form-control {
            font-size: 0.8rem;
        }
    }

</style>