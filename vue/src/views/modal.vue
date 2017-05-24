<template>
    <div>
        <!--<Button type="primary" @click="load">更改城市</Button>-->

        <Modal
                v-model="modal1"
                title="选择城市"
                @on-ok="ok"
                @on-cancel="cancel">
            <selector v-on:change="update" msg="abc"></selector>
        </Modal>
    </div>
</template>
<script>
    import selector from './selector.vue'
    export default {
        data () {
            return {
                modal1: false
            }
        },
        methods: {
            ok () {
                this.$Message.info('点击了确定');
            },
            cancel () {
                this.$Message.info('点击了取消');
            },
            load(){
                this.modal1 = true
            },
            update (value) {
                console.log(value)
                this.selected = value[value.length - 1]
            }
        },
        created: function () {
            this.modal1 = !this.place.name
            if (this.modal1) {
                this.$http.get('/').then(response => {
                    console.log(response);
                }).catch(error => {
                    console.log(error);
                })
            }
        },
        components: {
            selector
        },
        props: ['place'],
    }
</script>
