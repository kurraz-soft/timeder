<template>
    <div>
        <div v-bind:class="{'d-none': !is_btn_mode}" v-on:click="handleSwitchModeClick">
            <a href="#"><slot></slot></a>
        </div>
        <div v-bind:class="{'d-none': is_btn_mode}" class="input-group" :style="{width: currentInputWidth + 'px'}">
            <input autocomplete="off" class="form-control" :name="name" :value="value" v-bind:change="handleInputChange" />
            <div class="input-group-append">
                <span class="input-group-text"><a href="#" v-on:click="handleSwitchModeClick">[X]</a></span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "input-button",
        data() {
            return {
                is_btn_mode: true,
            }
        },
        computed: {
            currentInputWidth: function () {
                return this.width;
            }
        },
        methods: {
            handleSwitchModeClick: function (e) {
                e.preventDefault();

                this.switchMode();
            },
            switchMode() {
                this.is_btn_mode = !this.is_btn_mode;
            },
            handleInputChange: function (e) {
                this.onChange(e);
            },
        },
        props: {
            "name": String,
            "value": String,
            "width": {
                default: 200,
                type: Number,
            },
            "onChange": Function,
        },
        mounted() {

        }
    }
</script>

<style scoped>
    a {
        line-height: 16px;
    }
    input {
        font-size: 16px;
    }
</style>