<template>
    <div class="card">
        <div class="card-header">
            <h5>Data Sub Exhibition <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalAddSubExhibitions">Tambah Sub Exhibition</button> </h5>
        </div>
        <div class="card-body">
            <router-link :to="{ name: 'exhibitions' }" class="btn btn-info"><i class="fa fa-arrow-left"></i>
                Back</router-link>
            <br><br>
            <div class="table-responsive" id="table_container">
                <table class="table table-bordered" style="width: 100%" id="tableSubExibition">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalAddSubExhibitions" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Sub Exhibition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <Form @submit="add_sub_exhibition">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="input-1">Name</label>
                            <Field name="name" type="text" class="form-control" id="input-1" placeholder="Name"
                                v-model="name"></Field>
                        </div>
                        <div class="mb-3">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="file_banner_change($event)">
                        </div>
                        <br>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditSubExhibitions" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Exhibition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <Form @submit="edit_sub_exhibition">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="input-1">Name</label>
                            <Field name="update.name" type="text" class="form-control" id="input-1" placeholder="Name"
                                v-model="update.name"></Field>
                        </div>
                        <div class="mb-3">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1"
                                @change="update_file_banner_change($event)">
                        </div>
                        <br>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
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
import { Form, Field, ErrorMessage } from 'vee-validate';
export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            code: this.$route.params.code,
            name: "",
            file_banner: "",
            table_sub_exibition: null,
            update: {
                name: "",
                file_banner: "",
                id: ""
            }
        }
    },
    methods: {
        file_banner_change(e) {
            this.file_banner = e.target.files[0];
        },
        update_file_banner_change(e) {
            this.update.file_banner = e.target.files[0];
        },
        get_sub_exibition() {
            const vm = this;
            this.table_sub_exibition = $("#tableSubExibition").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/v1/web/sub/exhibitions/get",
                        headers: {
                            token: localStorage.getItem('token')
                        },
                        data: {
                            id_exhibitions: vm.code
                        }
                    },
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    }, {
                        "width": "2%",
                        "targets": 1
                    },
                    {
                        "width": "2%",
                        "targets": 3
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
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }
                    ]
                }
            );
        },
        refresh_table() {
            const vm = this;
            vm.globalLoader.show = true;
            this.table_sub_exibition.ajax.reload(() => {
                vm.globalLoader.show = false;
            });
        },
        init() {
            this.name = "";
            this.file_banner = null;
            this.update.file_banner = null;
        },
        add_sub_exhibition() {
            const vm = this;
            this.globalLoader.show = true;

            let frmData = new FormData();
            frmData.append("name", vm.name);
            frmData.append("file_banner", vm.file_banner);
            frmData.append("id_exhibition", vm.code);

            axios.post("/api/v1/web/sub/exhibitions/add", frmData, {
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
                    const modalElement = document.getElementById("modalAddSubExhibitions");
                    (window.bootstrap.Modal.getInstance(modalElement) ||
                        window.bootstrap.Modal.getOrCreateInstance(modalElement)).hide();
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
        edit_sub_exhibition() {
            const vm = this;
            this.globalLoader.show = true;

            let frmData = new FormData();
            frmData.append("name", vm.update.name);
            frmData.append("file_banner", vm.update.file_banner);
            frmData.append("id", vm.update.id);

            axios.post("/api/v1/web/sub/exhibitions/edit", frmData, {
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
                    const modalElement = document.getElementById("modalEditSubExhibitions");
                    (window.bootstrap.Modal.getInstance(modalElement) ||
                        window.bootstrap.Modal.getOrCreateInstance(modalElement)).hide();
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
        get_sub_exhibition_byid(id) {
            const vm = this;
            this.globalLoader.show = true;

            axios.post("/api/v1/web/sub/exhibitions/get/id", {
                id: id
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status === 1) {
                    vm.update.id = res.data.data.id;
                    vm.update.name = res.data.data.name;

                    window.bootstrap.Modal.getOrCreateInstance(document.getElementById("modalEditSubExhibitions"), {
                        backdrop: 'static',
                        keyboard: false
                    }).show();
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

            axios.post("/api/v1/web/sub/exhibitions/status/change", {
                id: id,
                status: status
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status === 1) {
                    vm.refresh_table();
                    swalNotif.success(res.data.message);
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
    },
    mounted() {
        const vm = this;
        this.loading = false;
        setTimeout(() => {
            vm.get_sub_exibition();

            $("#tableSubExibition").on('click', '.btnEdit', function () {
                const id = this.id;
                vm.get_sub_exhibition_byid(id);
            });

            $("#tableSubExibition").on('click', '.btnDisable', function () {
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

            $("#tableSubExibition").on('click', '.btnEnable', function () {
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

    }
}
</script>
