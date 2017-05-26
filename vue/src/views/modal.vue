<template>
    <div>
        <Modal v-model="modal1" title="选择城市" @on-ok="ok" @on-cancel="cancel"
               :closable="closable" :mask-closable="closable">
            <selector @change="update"></selector>
            <div slot="footer">
                <Button type="info" size="large" long
                        @click="ok" :disabled="isInvalid">确定
                </Button>
            </div>
        </Modal>
    </div>
</template>
<script>
    import selector from './selector.vue'
    export default {
        data () {
            return {
                modal1: false,
                closable: true,
                isInvalid: true,
            }
        },
        methods: {
            ok () {
                this.modal1 = false
                this.$emit('location-ok', this.selected)
            },
            cancel () {
                this.$Message.info('取消');
            },
            load(){
                this.modal1 = true
            },
            update (value) {
                console.log(value)
                this.selected = value
                this.isInvalid = false
            }
        },
        created: function () {
            this.modal1 = !this.place.name
            this.closable = !!this.place.name
        },
        components: {
            selector
        },
        props: ['place'],
    }
</script>
