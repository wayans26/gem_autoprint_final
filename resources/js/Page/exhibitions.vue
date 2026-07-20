<template>
    <div class="card">
        <div class="card-header">
            <h5>Exhibitions <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalAddExhibitions">Add Exibition</button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="table_container">
                <table class="table table-bordered" style="width: 100%" id="tableExhibitions" v-if="!loading">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Form</th>
                            <th>Host</th>
                            <th>Path</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddExhibitions">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Exhibition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" @submit="add_exhibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Code</label>
                            <input type="text" v-model="code" class="form-control" id="input-1"
                                placeholder="INAPA 2026">
                        </div>
                        <div class="form-group">
                            <label for="input-1">NAME</label>
                            <input type="text" v-model="name" class="form-control" id="input-1" placeholder="INAPA">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Full Name</label>
                            <input type="text" v-model="full_name" class="form-control" id="input-1"
                                placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Location</label>
                            <input type="text" v-model="location" class="form-control" id="input-1"
                                placeholder="Location">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Date</label>
                            <input type="text" v-model="date" class="form-control" id="input-1" placeholder="Date">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Team</label>
                            <input type="text" v-model="team" class="form-control" id="input-1" placeholder="Team">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Template Form</label>
                            <v-select class="form-control" placeholder="Select Form" :options="listForm" label="label"
                                :reduce="option => option.value" v-model="page"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Host</label>
                            <input type="text" v-model="host" class="form-control" id="input-1"
                                placeholder="inapa.reg-gemindonesia.net">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Path </label>
                            <input type="text" v-model="path" class="form-control" id="input-1"
                                placeholder="Path of Exhibitions like /bushworld -> bushworld">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="file_banner($event)">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="input-1">All Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="all_banner($event)">
                        </div>
                        <quill-editor v-model:content="opening_hours" contentType="html" theme="snow"></quill-editor>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditExhibitions">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Exhibition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" @submit="add_exhibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Code</label>
                            <input type="text" v-model="code" class="form-control" id="input-1"
                                placeholder="INAPA 2026">
                        </div>
                        <div class="form-group">
                            <label for="input-1">NAME</label>
                            <input type="text" v-model="name" class="form-control" id="input-1" placeholder="INAPA">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Full Name</label>
                            <input type="text" v-model="full_name" class="form-control" id="input-1"
                                placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Location</label>
                            <input type="text" v-model="location" class="form-control" id="input-1"
                                placeholder="Location">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Date</label>
                            <input type="text" v-model="date" class="form-control" id="input-1" placeholder="Date">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Team</label>
                            <input type="text" v-model="team" class="form-control" id="input-1" placeholder="Team">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Template Form</label>
                            <v-select class="form-control" placeholder="Select Form" :options="listForm" label="label"
                                :reduce="option => option.value" v-model="page"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Host</label>
                            <input type="text" v-model="host" class="form-control" id="input-1"
                                placeholder="inapa.reg-gemindonesia.net">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Path </label>
                            <input type="text" v-model="path" class="form-control" id="input-1"
                                placeholder="Path of Exhibitions like /bushworld -> bushworld">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="file_banner($event)">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="input-1">All Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="all_banner($event)">
                        </div>
                        <quill-editor v-model:content="opening_hours" contentType="html" theme="snow"></quill-editor>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import Swal from 'sweetalert2';
import listForm from '../Utils/listForm.js';

export default {
    data() {
        return {
            disabled: false,
            loading: true,
            tableExhibitions: null,
            code: "",
            banner_file: "",
            all_banner_file: "",
            name: "",
            full_name: "SMART AGRI-TECHNOLOGY, AGROCHEMICAL, PALM & SUGAR PLANTATION, AND FOOD MANUFACTURING",
            location: "JIExpo Kemayoran",
            date: "28 - 30 July 2026",
            team: "GEM Indonesia Team",
            opening_hours: "<p>28 July 2026 : 10.00 am - 6.00 pm</p><p>29 July 2026 : 10.00 am - 6.00 pm</p><p>30 July 2026 : 10.00 am - 4.30 pm</p>",
            host: "",
            page: "",
            path: "",
            update: {
                id: "",
                code: "",
                banner_file: "",
                all_banner_file: "",
                name: "",
                full_name: "SMART AGRI-TECHNOLOGY, AGROCHEMICAL, PALM & SUGAR PLANTATION, AND FOOD MANUFACTURING",
                location: "JIExpo Kemayoran",
                date: "28 - 30 July 2026",
                team: "GEM Indonesia Team",
                opening_hours: "<p>28 July 2026 : 10.00 am - 6.00 pm</p><p>29 July 2026 : 10.00 am - 6.00 pm</p><p>30 July 2026 : 10.00 am - 4.30 pm</p>",
                host: "",
                page: "",
                path: "",
            },
            listForm,
        }
    },
    methods: {
        get_exhibitions() {
            const vm = this;
            this.tableExhibitions = $("#tableExhibitions").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        type: "GET",
                        url: "/api/v1/web/exhibitions/get",
                        headers: {
                            token: localStorage.getItem('token')
                        }
                    },
                    pageLength: 25,
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    }, {
                        "width": "2%",
                        "targets": 1
                    }],
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'code',
                        name: 'code'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'page',
                        name: 'page',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'host',
                        name: 'host',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'path',
                        name: 'path',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    ]
                }
            );
        },
        refresh_table() {
            const vm = this;
            vm.globalLoader.show = true;
            this.tableExhibitions.ajax.reload(() => {
                vm.globalLoader.show = false;
            });
        },
        file_banner(e) {
            this.banner_file = e.target.files[0];
        },
        all_banner(e) {
            this.all_banner_file = e.target.files[0];
        },
        init() {
            this.code = "";
            this.name = "";
            this.banner_file = "";
            this.all_banner_file = "";
        },
        add_exhibition(e) {
            e.preventDefault();
            const vm = this;

            let frmData = new FormData();
            frmData.append("code", vm.code);
            frmData.append("name", vm.name);
            frmData.append("full_name", vm.full_name);
            frmData.append("location", vm.location);
            frmData.append("date", vm.date);
            frmData.append("team", vm.team);
            frmData.append("banner_file", vm.banner_file);
            frmData.append("all_banner_file", vm.all_banner_file);
            frmData.append("form", vm.page);
            frmData.append("host", vm.host);
            frmData.append("path", vm.path);
            frmData.append("opening_hours", vm.opening_hours);

            $.ajax({
                url: "/api/v1/web/exhibitions/add",
                type: "post",
                headers: {
                    token: localStorage.getItem('token'),
                },
                data: frmData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status === 1) {
                        vm.$swal({
                            icon: "success",
                            title: "Success",
                            text: data.message
                        });
                        vm.refresh_table();
                        vm.init();
                        $("#modalAddExhibitions").modal("hide");
                    }
                    else {
                        vm.$swal({
                            icon: "info",
                            title: "Information",
                            text: data.message
                        });
                    }
                },
                error: function (err) {
                    vm.$swal({
                        icon: "error",
                        title: "Error",
                        text: "Terjadi Kesalahan Pada Server",
                    });
                }
            });
        },
    },
    mounted() {
        const vm = this;
        this.loading = false;
        setTimeout(() => {
            vm.get_exhibitions();

            $("#tableExhibitions").on('click', '.btnAdd', function () {
                const id = this.id;
                vm.iduser = id;
                $("#addExhibitionsToUser").modal({ backdrop: 'static', keyboard: false });
            });
            $("#tableExhibitions").on('click', '.btnExhibitions', function () {
                const id = this.id;
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    text: "This Action Will Delete Assign Exhibitions!",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    showCancelButton: true,
                    didOpen: () => {
                        Swal.showLoading();
                        setTimeout(() => { Swal.hideLoading() }, 500)
                    }
                }).then((result) => {
                    $(".confirm").attr('disabled', 'disabled');
                    if (result.isConfirmed) {
                        vm.deleteAssignExhibitions(id);
                    }
                });
            });

        }, 1);
    },
}
</script>
