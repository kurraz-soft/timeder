<template>
    <div>
        <form method="POST" action="#" id="new-project-form" @submit="handleSubmitNewProject">
            <input-button ref="input" name="name">Новый проект</input-button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "project-new-form",
        methods: {
            async handleSubmitNewProject(e){
                e.preventDefault();

                try{
                    await this.$store.dispatch('newProject', new FormData(e.target));
                    this.$refs.input.switchMode();
                }catch (e)
                {

                }
            }
        },
    }
</script>

<style scoped>

</style>