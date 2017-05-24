<style scoped>
    @import 'styles/common.css';
</style>
<template>
    <div>
        <router-view :place="place"></router-view>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                place:{}
            };
        },
        mounted() {

        },
        beforeDestroy() {

        },
        methods: {

        }
    };
</script>