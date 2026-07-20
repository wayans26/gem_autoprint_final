<template>
    <div class="card">
        <div class="card-header">
            <h5>Data Sub Exhibition ( {{ code }} ) <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalTambahSubExibition">Tambah Sub Exhibition</button> </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="table_container">
                <table class="table table-bordered" style="width: 100%" id="tablesubexibition">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>code Sub Exibition</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahSubExibition">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Exhibition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" @submit="tambah_sub_exibition">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Kode Exhibition</label>
                            <input disabled type="text" v-model="code" class="form-control" id="input-1"
                                placeholder="Kode Exibition">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Kode Sub Exhibition</label>
                            <input type="text" v-model="code_sub" class="form-control" id="input-1"
                                placeholder="Kode Exibition">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Nama Sub Exhibition</label>
                            <input type="text" v-model="nama" class="form-control" id="input-1"
                                placeholder="Nama Exibition">
                        </div>
                        <div class="form-group">
                            <label for="input-1">Banner</label>
                            <input type="file" class="form-control" id="input-1" @change="onFileChange($event)">
                        </div>

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
</template>

<script>
export default {
    components: {

    },
    data() {
        return {
            code: this.$route.params.code,
            code_sub: "",
            nama: "",
            file: "",
            table_sub_exibition: ""
        }
    },
    methods: {
        onFileChange(e) {
            this.file = e.target.files[0];
        },
        get_sub_exibition() {
            const vm = this;
            this.table_sub_exibition = $("#tablesubexibition").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/admin/subexibition/get",
                        headers: {
                            token: localStorage.getItem('token')
                        },
                        data: {
                            code: vm.code
                        }
                    },
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    }],
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    }
                    ]
                }
            );
        },

        tambah_sub_exibition(e) {
            e.preventDefault();
            const vm = this;

            let frmData = new FormData();
            frmData.append("code", vm.code);
            frmData.append("code_sub", vm.code_sub);
            frmData.append("name", vm.nama);
            frmData.append("file", vm.file);

            $.ajax({
                url: "/api/admin/subexibition/add",
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
                        vm.table_sub_exibition.ajax.reload();
                        vm.code_sub = "";
                        vm.nama = "";
                        vm.file = "";
                        $("#modalTambahSubExibition").modal("hide");
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
        this.get_sub_exibition();

    }
}
</script>
