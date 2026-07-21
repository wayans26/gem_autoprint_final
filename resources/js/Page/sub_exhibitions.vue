<template>
    <div class="card">
        <div class="card-header">
            <h5>Data Sub Exhibition <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalAddSubExhibitions">Tambah Sub Exhibition</button> </h5>
        </div>
        <div class="card-body">
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



    <div class="modal fade" id="modalAddSubExhibitions">
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
                            <label for="input-1">Name</label>
                            <Field name="name" type="text" class="form-control" id="input-1" placeholder="Name"
                                v-model="name"></Field>
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="file_banner_change($event)">
                        </div>
                        <br>

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
            table_sub_exibition: null
        }
    },
    methods: {
        file_banner_change(e) {
            this.file_banner = e.target.files[0];
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
        add_exhibition() {
            const vm = this;
            this.globalLoader.show = true;

            let frmData = new FormData();
            frmData.append("name", vm.name);

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
    },
    mounted() {
        this.get_sub_exibition();

    }
}
</script>
