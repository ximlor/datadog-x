<template>
    <div>
        <Cascader :data="data" v-model="value1" @on-change="change" size="large" trigger="hover"
                  :disabled="disabled"></Cascader>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                value1: [],
                data: [],
            }
        },
        methods: {
            change: function (value, selectedData) {
                this.$emit('change', selectedData[selectedData.length - 1])
            },
        },
        created: function () {
            this.disabled = true;
            this.$http.get('http://localhost/api/map/district/cities').then(response => {
                this.data = response.data['districts'];
                this.disabled = false;
            }).catch(error => {
                console.log(error);
            })
        },
    }
</script>
