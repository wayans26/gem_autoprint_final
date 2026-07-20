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
                <Form @submit="add_exhibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Code</label>
                            <Field name="code" type="text" class="form-control" id="input-1" placeholder="inapa2026"
                                v-model="code"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">NAME</label>
                            <Field name="name" type="text" class="form-control" id="input-1" placeholder="INAPA"
                                v-model="name"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Full Name</label>
                            <Field name="full_name" type="text" class="form-control" id="input-1"
                                placeholder="Full Name" v-model="full_name"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Location</label>
                            <Field name="location" type="text" class="form-control" id="input-1" placeholder="Location"
                                v-model="location"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Date</label>
                            <Field name="date" type="text" class="form-control" id="input-1" placeholder="Date"
                                v-model="date"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Team</label>
                            <Field name="team" type="text" class="form-control" id="input-1" placeholder="Team"
                                v-model="team"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Template Form</label>
                            <v-select class="form-control" placeholder="Select Form" :options="listForm" label="label"
                                :reduce="option => option.value" v-model="page"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Host</label>
                            <Field name="host" type="text" class="form-control" id="input-1"
                                placeholder="inapa.reg-gemindonesia.net" v-model="host"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Path </label>
                            <Field name="path" type="text" class="form-control" id="input-1"
                                placeholder="Path of Exhibitions like /bushworld -> bushworld" v-model="path"></Field>
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
                    <h5 class="modal-title">Edit Exhibition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form @submit="edit_exhibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Code</label>
                            <Field name="update.code" type="text" class="form-control" id="input-1"
                                placeholder="inapa2026" v-model="update.code"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">NAME</label>
                            <Field name="update.name" type="text" class="form-control" id="input-1" placeholder="INAPA"
                                v-model="update.name"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Full Name</label>
                            <Field name="update.full_name" type="text" class="form-control" id="input-1"
                                placeholder="Full Name" v-model="update.full_name"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Location</label>
                            <Field name="update.location" type="text" class="form-control" id="input-1"
                                placeholder="Location" v-model="update.location"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Date</label>
                            <Field name="update.date" type="text" class="form-control" id="input-1" placeholder="Date"
                                v-model="update.date"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Team</label>
                            <Field name="update.team" type="text" class="form-control" id="input-1" placeholder="Team"
                                v-model="update.team"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Template Form</label>
                            <v-select class="form-control" placeholder="Select Form" :options="listForm" label="label"
                                :reduce="option => option.value" v-model="update.page"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Host</label>
                            <Field name="update.host" type="text" class="form-control" id="input-1"
                                placeholder="inapa.reg-gemindonesia.net" v-model="update.host"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Path </label>
                            <Field name="update.path" type="text" class="form-control" id="input-1"
                                placeholder="Path of Exhibitions like /bushworld -> bushworld" v-model="update.path">
                            </Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="update_file_banner($event)">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="input-1">All Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="update_all_banner($event)">
                        </div>
                        <quill-editor v-model:content="opening_hours" contentType="html" theme="snow"></quill-editor>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i>
                            Save</button>
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
import { Form, Field, ErrorMessage } from 'vee-validate';

export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
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
        file_banner(e, filename) {
            this.banner_file = e.target.files[0];
        },
        all_banner(e, filename) {
            this.all_banner_file = e.target.files[0];
        },
        update_file_banner(e, filename) {
            this.update.banner_file = e.target.files[0];
        },
        update_all_banner(e, filename) {
            this.update.all_banner_file = e.target.files[0];
        },
        init() {
            this.code = "";
            this.name = "";
            this.banner_file = null;
            this.all_banner_file = null;
            this.update.banner_file = null;
            this.update.all_banner_file = null;
        },
        add_exhibition() {
            const vm = this;
            this.globalLoader.show = true;

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

            axios.post("/api/v1/web/exhibitions/add", frmData, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status === 1) {
                    vm.$swal({
                        icon: "success",
                        title: "Success",
                        text: res.data.message
                    });
                    vm.refresh_table();
                    vm.init();
                    $("#modalAddExhibitions").modal("hide");
                }
                else {
                    vm.$swal({
                        icon: "info",
                        title: "Information",
                        text: res.data.message
                    });
                }
            }).catch(res => {
                vm.$swal({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi Kesalahan Pada Server",
                });

            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        get_exhibition_byid(id) {
            const vm = this;
            this.globalLoader.show = true;

            axios.post("/api/v1/web/exhibitions/get/id", {
                id: id
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.update.id = res.data.data.id;
                    vm.update.code = res.data.data.code;
                    vm.update.name = res.data.data.name;
                    vm.update.full_name = res.data.data.full_name;
                    vm.update.location = res.data.data.location;
                    vm.update.date = res.data.data.date;
                    vm.update.team = res.data.data.team;
                    vm.update.opening_hours = res.data.data.opening_hours;
                    vm.update.host = res.data.data.host;
                    vm.update.page = res.data.data.page;
                    vm.update.path = res.data.data.path ? res.data.data.path : null;
                    $("#modalEditExhibitions").modal({ backdrop: 'static', keyboard: false });
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Get Exhibtion!");

            }).finally(function () {
                vm.disabled = false;
                vm.globalLoader.show = false;
            });
        },
        edit_exhibition() {
            const vm = this;
            this.globalLoader.show = true;

            let frmData = new FormData();
            frmData.append("id", vm.update.id);
            frmData.append("code", vm.update.code);
            frmData.append("name", vm.update.name);
            frmData.append("full_name", vm.update.full_name);
            frmData.append("location", vm.update.location);
            frmData.append("date", vm.update.date);
            frmData.append("team", vm.update.team);
            frmData.append("banner_file", vm.update.banner_file);
            frmData.append("all_banner_file", vm.update.all_banner_file);
            frmData.append("form", vm.update.page);
            frmData.append("host", vm.update.host);
            frmData.append("path", vm.update.path);
            frmData.append("opening_hours", vm.update.opening_hours);

            axios.post("/api/v1/web/exhibitions/edit", frmData, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status === 1) {
                    vm.$swal({
                        icon: "success",
                        title: "Success",
                        text: res.data.message
                    });
                    vm.refresh_table();
                    vm.init();
                    $("#modalEditExhibitions").modal("hide");
                }
                else {
                    vm.$swal({
                        icon: "info",
                        title: "Information",
                        text: res.data.message
                    });
                }
            }).catch(res => {
                vm.$swal({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi Kesalahan Pada Server",
                });

            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        change_status(id, status) {
            const vm = this;
            this.globalLoader.show = true;

            axios.post("/api/v1/web/exhibitions/status/change", {
                id: id,
                status: status
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    swalNotif.success(res.data.message);
                    vm.refresh_table();
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Change Status!");

            }).finally(function () {
                vm.disabled = false;
                vm.globalLoader.show = false;
            });
        },
    },
    mounted() {
        const vm = this;
        this.loading = false;
        setTimeout(() => {
            vm.get_exhibitions();

            $("#tableExhibitions").on('click', '.btnEdit', function () {
                const id = this.id;
                vm.get_exhibition_byid(id);
            });
            $("#tableExhibitions").on('click', '.btnAdd', function () {
                const id = this.id;
                vm.iduser = id;
                $("#addExhibitionsToUser").modal({ backdrop: 'static', keyboard: false });
            });

            $("#tableExhibitions").on('click', '.btnDisable', function () {
                const id = this.id;
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    text: "This Action Will Be Disable Exhibition!",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    showCancelButton: true,
                    didOpen: () => {
                        Swal.showLoading();
                        setTimeout(() => { Swal.hideLoading() }, 500)
                    }
                }).then((result) => {
                    $(".confirm").attr('disabled', 'disabled');
                    if (result.isConfirmed) {
                        vm.change_status(id, "0");
                    }
                });
            });

            $("#tableExhibitions").on('click', '.btnEnable', function () {
                const id = this.id;
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    text: "This Action Will Be Enable Exhibition!",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    showCancelButton: true,
                    didOpen: () => {
                        Swal.showLoading();
                        setTimeout(() => { Swal.hideLoading() }, 500)
                    }
                }).then((result) => {
                    $(".confirm").attr('disabled', 'disabled');
                    if (result.isConfirmed) {
                        vm.change_status(id, "1");
                    }
                });
            });

        }, 1);
    },
}
</script>
