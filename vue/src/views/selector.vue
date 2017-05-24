<template>
    <div>
        <Cascader :data="data" v-model="value1" v-on:on-change="change" :load-data="loadData" size="large" trigger="hover"></Cascader>
        <p>{{ msg }}</p>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                value1: [],
                data: [{
                    value: 'beijing',
                    label: '北京',
                    children: [
                        {
                            value: 'gugong',
                            label: '故宫'
                        },
                        {
                            value: 'tiantan',
                            label: '天坛'
                        },
                        {
                            value: 'wangfujing',
                            label: '王府井'
                        }
                    ]
                }, {
                    value: 'jiangsu',
                    label: '江苏',
                    children: [
                        {
                            value: 'nanjing',
                            label: '南京',
                            children: [
                                {
                                    value: 'fuzimiao',
                                    label: '夫子庙',
                                }
                            ]
                        },
                        {
                            value: 'suzhou',
                            label: '苏州',
                            children: [
                                {
                                    value: 'zhuozhengyuan',
                                    label: '拙政园',
                                },
                                {
                                    value: 'shizilin',
                                    label: '狮子林',
                                }
                            ]
                        }
                    ],
                }]
            }
        },
        methods: {
            change: function (value, selectedData) {
                console.log(value, selectedData)
                this.$emit('change', value)
            },
            loadData (item, callback) {
                item.loading = true;
                AMap.service('AMap.PlaceSearch', function () {//回调函数
                    //实例化PlaceSearch
                    let placeSearch = new AMap.PlaceSearch({
                        type: '141201',
                        city: item.value
                    });
                    //TODO: 使用placeSearch对象调用关键字搜索的功能
                    placeSearch.search('', function (status, result) {
                        //TODO : 按照自己需求处理查询结果
                        console.log(result)
                        let pois=result.poiList.pois
                        for(let i=0;i<pois.length;i++){
                            item.children.push({
                                value:pois[i]['location'],
                                label:pois[i]['name'],
                            })
                        }
                        item.loading = false;
                        callback();
                    })
                })
            }
        },
        created: function () {
            let that = this
            AMap.service('AMap.DistrictSearch', function () {//回调函数
                //实例化DistrictSearch
                let districtSearch = new AMap.DistrictSearch({
                    level: 'country',
                    subdistrict: 2
                });
                //TODO: 使用districtSearch对象调用行政区查询的功能
                districtSearch.search('中国', function (status, result) {
                    //TODO : 按照自己需求处理查询结果
                    if (status == 'complete') {
                        console.log(result.districtList[0]);
                        that.data = []
                        let provinces = result.districtList[0].districtList
                        for (let i = 0; i < provinces.length; i++) {
                            let item = {
                                'value': provinces[i]['adcode'],
                                'label': provinces[i]['name'],
                            }
                            let cities = provinces[i]['districtList']
                            let children = []
                            if (cities) {
                                for (let m = 0; m < cities.length; m++) {
                                    children.push({
                                        'value': cities[m]['adcode'],
                                        'label': cities[m]['name'],
                                        'children': [],
                                        loading: false
                                    })
                                }
                                item.children = children
                                that.data.push(item)
                            }
                        }
                        console.log(that.data)
                    }
                })
            })

        },
        props: ['msg']
    }
</script>
