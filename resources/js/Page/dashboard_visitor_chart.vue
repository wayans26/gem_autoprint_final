<template>
    <div class="card">
        <div class="card-header">
            <bread-crumb></bread-crumb>
            <h5>Visitor List</h5>
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
            <h1>Visitors Insight</h1>
            <h1>Visitor Registration</h1>
            <h1>Target Visitor</h1>
            <apex-chart type="line" height="350" :options="chartOptions" :series="series" />
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
            series: [{
                name: 'Sales',
                data: [10, 25, 15, 40]
            }],
            chartOptions: {
                chart: {
                    id: 'sales'
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr']
                }
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
