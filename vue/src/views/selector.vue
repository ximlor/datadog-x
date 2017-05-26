<template>
    <div>
        <Cascader :data="data" v-model="value1" v-on:on-change="change" :load-data="loadData" size="large"
                  trigger="hover" disabled></Cascader>
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
                console.log(value, selectedData)
                this.$emit('change', value, selectedData)
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
                        let pois = result.poiList.pois
                        for (let i = 0; i < pois.length; i++) {
                            item.children.push({
                                value: pois[i]['location'],
                                label: pois[i]['name'],
                            })
                        }
                        item.loading = false;
                        callback();
                    })
                })
            }
        },
        created: function () {
            this.$http.get('http://localhost:80/api/map/district/cities').then(response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
            })
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
