<style scoped>
    .layout {
        border        : 1px solid #d7dde4;
        background    : #f5f7f9;
        position      : relative;
        border-radius : 4px;
        overflow      : hidden;
    }

    .layout-breadcrumb {
        padding : 10px 15px 0;
    }

    .layout-content {
        min-height    : 600px;
        margin        : 15px;
        overflow      : hidden;
        background    : #fff;
        border-radius : 4px;
    }

    .layout-content-main {
        padding : 10px;
    }

    .layout-copy {
        text-align : center;
        padding    : 10px 0 20px;
        color      : #9ea7b4;
    }

    .layout-menu-left {
        background : #464c5b;
    }

    .layout-header {
        height     : 60px;
        background : #fff;
        box-shadow : 0 1px 1px rgba(0, 0, 0, .1);
    }

    .layout-logo-left {
        width         : 90%;
        height        : 30px;
        background    : #5b6270;
        border-radius : 3px;
        margin        : 15px auto;
    }

    .layout-ceiling-main a {
        color : #9ba7b5;
    }

    .layout-hide-text .layout-text {
        display : none;
    }

    .ivu-col {
        transition : width .2s ease-in-out;
    }
</style>
<template>
    <div class="layout" :class="{'layout-hide-text': spanLeft < 5}">
        <Row type="flex">

            <i-col :span="spanLeft" class="layout-menu-left">
                <Menu active-name="1" theme="dark" width="auto">
                    <Menu-item name="1">
                        <Icon type="ios-navigate" :size="iconSize"></Icon>
                        <span class="layout-text">选项 1</span>
                    </Menu-item>
                    <Menu-item name="2">
                        <Icon type="ios-keypad" :size="iconSize"></Icon>
                        <span class="layout-text">选项 2</span>
                    </Menu-item>
                    <Menu-item name="3">
                        <Icon type="ios-analytics" :size="iconSize"></Icon>
                        <span class="layout-text">选项 3</span>
                    </Menu-item>
                </Menu>
            </i-col>

            <i-col :span="spanRight">
                <div class="layout-header">
                    <i-button type="text" @click="toggleClick">
                        <Icon type="navicon" size="32"></Icon>
                    </i-button>
                    <i-button class="pull-right" type="text" @click="toggleClick">
                        <Icon type="location" size="32"></Icon>
                    </i-button>
                </div>

                <modal :place="place" @location-ok="setLocation"></modal>

                <div class="layout-content">
                    <div class="layout-content-main">

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Plain Page</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            </li>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#">Settings 1</a>
                                                    </li>
                                                    <li><a href="#">Settings 2</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        Add content to the page ...
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="layout-copy">
                    2017 &copy; ZZX
                </div>
            </i-col>
        </Row>
    </div>
</template>
<script>
    import modal from './modal.vue'
    export default {
        data () {
            return {
                spanLeft: 5,
                spanRight: 19,
                place: {},
            }
        },
        computed: {
            iconSize () {
                return this.spanLeft === 5 ? 14 : 24;
            }
        },
        methods: {
            toggleClick () {
                if (this.spanLeft === 5) {
                    this.spanLeft = 2;
                    this.spanRight = 22;
                } else {
                    this.spanLeft = 5;
                    this.spanRight = 19;
                }
            },
            setLocation (location) {
                this.place = location
                console.log(this)
            },
        },
        components: {
            modal
        }
    }
</script>

