<template>
    <div class="card">
        <div class="card-header">
            <h5>Exhibitions <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalTambahExibition">Tambah Exibition</button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="table_container">
                <table class="table table-bordered" style="width: 100%" id="tableExhibitions" v-if="!loading">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Code</th>
                            <th>Nama</th>
                            <th>Web Own</th>
                            <th>Keterangan</th>
                            <th>Event Name</th>
                            <th>Status</th>
                            <th>Assign Staff</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahExibition">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Exhibition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" @submit="tambah_exibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Kode Exibition</label>
                            <input type="text" v-model="code" class="form-control" id="input-1"
                                placeholder="Kode Exibition">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Nama Exibition</label>
                            <input type="text" v-model="name" class="form-control" id="input-1"
                                placeholder="Nama Exibition">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Web Own</label>
                            <input type="text" v-model="web_own" class="form-control" id="input-1"
                                placeholder="Web OWN">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="onFileChange($event)">
                        </div>
                        <div class="form-group">
                            <label for="input-1">All Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="allBannerChange($event)">
                        </div>
                        <quill-editor v-model:content="opening_hours" contentType="html" theme="snow"></quill-editor>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addExhibitionsToUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Exhibitions To User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Select Exhibitions</label>
                            <v-select class="form-control" placeholder="Select an Sub Exhibitions"
                                :options="list_exhibitions" label="label" :reduce="option => option.value"
                                v-model="selected_exhibitions" :clearable="false"></v-select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="button" @click="assignExhibitionsToUser" class="btn btn-primary"><i :class="{
                            'fa fa-spinner fa-spin': disabled,
                            'fa fa-edit': !disabled,
                        }"></i>
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

export default {
    data() {
        return {
            disabled: false,
            loading: true,
            location_id: this.locationId,
            note: "",
            tableExhibitions: null,
            list_exhibitions: [],
            selected_exhibitions: "",
            iduser: ""
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
                        data: 'web_own',
                        name: 'web_own',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'keterangan',
                        name: 'keterangan',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'event_name',
                        name: 'event_name',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'staff',
                        name: 'staff',
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
        change_show_status(idexhibitions, isShow) {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/show/change", {
                'idexhibitions': idexhibitions,
                'cmd': isShow
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error(err.response.data.message);
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        get_list_exhibitions() {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/list/get", {}, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.list_exhibitions = res.data.data.map(item => ({
                        label: item.name,
                        value: item.idexhibitions
                    }));
                    vm.selected_exhibitions = res.data.data[0].idexhibitions;
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Getting Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        assignExhibitionsToUser() {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/assign/user/add", {
                'iduser': vm.iduser,
                'idexhibitions': vm.selected_exhibitions
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    $("#addExhibitionsToUser").modal('hide');
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Add Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        deleteAssignExhibitions(id) {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/assign/user/delete", {
                'id': id
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Deleting Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        onFileChange(e) {
            this.file = e.target.files[0];
        },
        tambah_exibition(e) {
            e.preventDefault();
            const vm = this;

            let frmData = new FormData();
            frmData.append("code", vm.code);
            frmData.append("name", vm.name);
            frmData.append("tanggal", vm.tanggal);
            frmData.append("web_own", vm.web_own);
            frmData.append("keterangan", vm.keterangan);
            frmData.append("event_name", vm.event_name);
            frmData.append("opening_hours", vm.opening_hours);
            frmData.append("file", vm.file);
            frmData.append("all_banner", vm.all_banner);
            frmData.append("type", vm.type);
            frmData.append("custom_tag", vm.custom_tag);

            $.ajax({
                url: "/api/admin/exibition/add",
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
                        vm.table_exibition.ajax.reload();
                        vm.code = "";
                        vm.name = "";
                        vm.file = "";
                        vm.event_name = "";
                        vm.opening_hours = "";
                        $("#modalTambahExibition").modal("hide");
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
            // vm.get_list_exhibitions();

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
