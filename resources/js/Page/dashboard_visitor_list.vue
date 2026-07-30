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
                            v-model="sub_exhibition_id" :clearable="false" @option:selected="refresh_table"></v-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" style="width: 100%" id="tableVisitor" v-if="!loading">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Exhibition</th>
                        <th>Sub Exhibition</th>
                        <th>Name</th>
                        <th>Is Printed</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            loading: true,
            table_visitor: null,
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
            ]
        }
    },
    methods: {
        get_visitor() {
            const vm = this;
            this.table_visitor = $("#tableVisitor").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/v1/web/dashboard/visitor/list/get",
                        headers: {
                            token: localStorage.getItem('token')
                        },
                        data: function (d) {
                            d.exhibition_id = vm.exhibition_id;
                            d.sub_exhibition_id = vm.sub_exhibition_id;
                            d.status = vm.status;
                        }

                    },
                    fixedHeader: {
                        header: true,
                    },
                    scrollY: '60vh',
                    scrollCollapse: true,
                    scrollX: true,
                    pageLength: 25,
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    },
                    {
                        "width": "2%",
                        "targets": 4
                    }],
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'exhibition_name',
                        name: 'exhibition_name'
                    },
                    {
                        data: 'sub_exhibition_name',
                        name: 'sub_exhibition_name'
                    },
                    {
                        data: 'visitor_name',
                        name: 'visitor_name'
                    },
                    {
                        data: 'is_printed',
                        name: 'is_printed'
                    }
                    ]
                }
            );
        },
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
                    vm.refresh_table();
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
                    vm.refresh_table();

                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Sub Exhibitions!");

            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        refresh_table() {
            const vm = this;
            vm.globalLoader.show = true;
            this.table_visitor.ajax.reload(() => {
                vm.globalLoader.show = false;
            });
        }
    },
    mounted() {
        const vm = this;
        vm.loading = false;
        setTimeout(() => {
            vm.get_exhibitions();
            vm.get_sub_exhibitions();
            vm.get_visitor();
        }, 1);

    }
}
</script>
