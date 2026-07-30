<template>
    <div class="card">
        <div class="card-header">
            <bread-crumb></bread-crumb>
            <h5>Visitor Chart</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="input-1">Status</label>
                        <v-select class="form-control" placeholder="Select Status" :options="list_status" label="label"
                            :reduce="option => option.value" v-model="status" :clearable="false"
                            @option:selected="get_exhibitions"></v-select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="input-1">Exhibitions</label>
                        <v-select class="form-control" placeholder="Select an Exhibitions" :options="list_exhibitions"
                            label="label" :reduce="option => option.value" v-model="exhibition_id"
                            @option:selected="get_sub_exhibitions" :clearable="false"></v-select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="input-1">Sub Exhibitions</label>
                        <v-select class="form-control" placeholder="Select an Sub Exhibitions"
                            :options="list_sub_exhibitions" label="label" :reduce="option => option.value"
                            v-model="sub_exhibition_id" :clearable="false"></v-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <apex-chart type="bar" height="350" :options="target_by_exhibition.chartOptions"
                :series="target_by_exhibition.series"></apex-chart>
            <apex-chart type="pie" width="480" :options="visitor_insight.chartOptions"
                :series="visitor_insight.series"></apex-chart>
            <apex-chart type="bar" height="350" :options="visitor_check_in.chartOptions"
                :series="visitor_check_in.series"></apex-chart>
        </div>
    </div>

</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import Swal from 'sweetalert2';
import ApexChart from 'vue3-apexcharts'

export default {
    components: {
        apexChart: ApexChart
    },
    data() {
        return {
            loading: true,
            list_exhibitions: [],
            exhibition_id: "all",
            list_sub_exhibitions: [],
            sub_exhibition_id: "all",
            status: "all",
            list_status: [
                {
                    label: "All",
                    value: "all"
                },
                {
                    label: "Enable",
                    value: "1"
                },
                {
                    label: "Disable",
                    value: "0"
                },
            ],
            target_by_exhibition: {
                series: [
                    {
                        name: 'Actual',
                        data: [
                            {
                                x: 'North',
                                y: 12,
                                goals: [
                                    {
                                        name: 'Expected',
                                        value: 14,
                                        strokeWidth: 5,
                                        strokeHeight: 50,
                                        strokeColor: '#775DD0',
                                    },
                                ],
                            },
                            {
                                x: 'South',
                                y: 44,
                                goals: [
                                    {
                                        name: 'Expected',
                                        value: 54,
                                        strokeWidth: 5,
                                        strokeHeight: 50,
                                        strokeColor: '#775DD0',
                                    },
                                ],
                            },
                        ],
                    },
                ],
                chartOptions: {
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            tools: {
                                download: false
                            }
                        }
                    },
                    title: {
                        text: 'Target Visitor By Exhibition',
                        align: 'left',
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                        },
                    },
                    colors: ['#00E396'],
                    dataLabels: {
                        formatter: function (val, opt) {
                            const goals =
                                opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex]
                                    .goals


                            if (goals && goals.length) {

                                return `${val} / ${goals[0].value}`
                            }
                            return val
                        },
                    },
                    legend: {
                        show: true,
                        showForSingleSeries: true,
                        customLegendItems: ['Actual', 'Expected'],
                        markers: {
                            fillColors: ['#00E396', '#775DD0'],
                        },
                    },
                },

            },
            visitor_insight: {
                series: [42, 23, 15, 12, 8],
                chartOptions: {
                    chart: {
                        width: 480,
                        type: 'pie',
                        toolbar: {
                            tools: {
                                download: false
                            }
                        }
                    },
                    labels: ['Organic Search', 'Direct', 'Social', 'Referral', 'Email'],
                    title: {
                        text: 'Visitors Insight By Date',
                        align: 'center',
                    },
                    legend: {
                        show: false,
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                external: {
                                    show: true,
                                },
                            },
                        },
                    },
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 320,
                                },
                            },
                        },
                    ],
                },
            },
            visitor_check_in: {
                series: [
                    {
                        name: 'Net Profit',
                        data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
                    },
                    {
                        name: 'Revenue',
                        data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
                    },
                    {
                        name: 'Free Cash Flow',
                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
                    },
                ],
                chartOptions: {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            tools: {
                                download: false
                            }
                        }
                    },
                    title: {
                        text: 'Visitors Checkin By Exhibition',
                        align: 'center',
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 5,
                            borderRadiusApplication: 'end',
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent'],
                    },
                    xaxis: {
                        categories: [
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                        ],
                    },
                    yaxis: {
                        title: {
                            text: '$ (thousands)',
                        },
                    },
                    fill: {
                        opacity: 1,
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return '$ ' + val + ' thousands'
                            },
                        },
                    },
                },
            }
        }
    },
    methods: {
        get_exhibitions() {
            const vm = this;
            this.globalLoader.show = true;
            axios.post("/api/v1/web/exhibition/all/get", {
                status: vm.status
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.exhibition_id = "all";
                    vm.list_exhibitions = [
                        {
                            label: "All",
                            value: "all"
                        },
                        ...res.data.data.map(item => ({
                            label: item.name,
                            value: item.id
                        }))
                    ]
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Get Exhibitions!");

            }).finally(function () {
                vm.globalLoader.show = false;
            })
        },
        get_sub_exhibitions() {
            const vm = this;
            this.globalLoader.show = true;
            axios.post("/api/v1/web/exhibition/sub/all/get", {
                exhibition_id: vm.exhibition_id
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.sub_exhibition_id = "all";
                    vm.list_sub_exhibitions = [
                        {
                            label: "All",
                            value: "all"
                        },
                        ...res.data.data.map(item => ({
                            label: item.name,
                            value: item.id
                        }))
                    ];

                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Sub Exhibitions!");

            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
    },
    mounted() {
        const vm = this;
        vm.loading = false;
        setTimeout(() => {
            vm.get_exhibitions();
            vm.get_sub_exhibitions();
        }, 1);

    }
}
</script>
