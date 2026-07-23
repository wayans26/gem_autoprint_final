<template>
    <div id="wrapper">
        <div class="loader-container" v-if="loading">
            <div class="loader"></div>
        </div>
        <div class="card mx-auto" style="max-width: 80%;">
            <img class="card-img-top" :src="'/registration/banner/exhibitions/' + exhibition_detail.banner_file"
                style="max-width: 100%;" alt="" srcset="">
            <Form>
                <div class="card-body">
                    <div class="form-group">
                        <label for="input-1">Exhibition</label>
                        <v-select placeholder="Select a Exhibition" :options="list_exhibitions" label="label"
                            :reduce="option => option.value" v-model="exhibition_id" :clearable="false"
                            @option:selected="on_exhibitions_select"></v-select>
                    </div>
                    <div class="form-group" v-show="exhibition_id">
                        <label for="input-1">Sub Exhibition</label>
                        <v-select placeholder="Select a Sub Exhibition" :options="list_sub_exhibitions" label="label"
                            :reduce="option => option.value" v-model="sub_exhibition_id" :clearable="false"></v-select>
                    </div>
                    <div v-show="sub_exhibition_id">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Title *</label>
                                    <v-select placeholder="Select a Title" :options="form_data.title" label="label"
                                        :reduce="option => option.value" v-model="form.title"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Name</label>
                                    <div class="position-relative ">
                                        <Field name="name" class="form-control" placeholder="Name"
                                            v-model="form.name" />
                                        <ErrorMessage style="color: red;" name="name" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Company</label>
                                    <div class="position-relative ">
                                        <Field name="company" class="form-control" placeholder="Company"
                                            v-model="form.company" />
                                        <ErrorMessage style="color: red;" name="company" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Job Title</label>
                                    <div class="position-relative ">
                                        <Field name="job_title" class="form-control" placeholder="Job Title"
                                            v-model="form.job_title" />
                                        <ErrorMessage style="color: red;" name="job_title" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <h5>Press continue to complete registration</h5>
                    <p>If you are ready to complete registration, please click the continue button below.</p>
                    <button type="submit" :disabled="loading" class="
                                btn btn-primary
                                shadow-primary
                                waves-effect waves-light
                            ">
                        <i :class="{
                            'fa fa-spinner fa-spin': loading,
                            'fa fa-sign-in': !loading,
                        }"></i>
                        Register
                    </button>
                </div>
            </Form>
        </div>
    </div>

    <div class="modal fade" id="modalInfo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registration Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Thank you for your registration.</h5>
                    <p>An email has been sent to you.
                        Please print or show the confirmation email to registration desk to redeem a visitor's
                        badge.
                        Please check your inbox / spam folder to received the confirmation email.</p>
                    <p style="color: red;">If you have not receive the confirmation letter, please contact our staff
                        :</p>

                    <a href="mailto:auto.info@gem-indonesia.net">auto.info@gem-indonesia.net</a>
                    <br><br>
                    <p style="color: red;">We look forward to welcoming you at NAMA_EVENT</p>
                    <p>GEM Indonesia Team</p>
                </div>
                <div class="modal-footer">
                    <button @click="refresh_page" type="button" class="btn btn-inverse-primary"><i
                            class="fa fa-times"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalWarning">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <img :src="'/regulation.png'" style="width: 100%;" alt="">
                </div>
                <div class="modal-body">
                    <ol v-if="language">
                        <li>Children under 18 years old are not allowed to enter the exhibition halls.</li>
                        <li>Business attire are mandatory. Short pants and slippers/sandals are not allowed to enter
                            the exhibition halls.</li>
                        <li>No firearms or weapons are allowed to enter the exhibition halls.</li>
                        <li>Visitors are not allowed to take photographs/video recording of the products in display
                            without any permission from the exhibitors.</li>
                    </ol>
                    <ol v-else>
                        <li>Anak-anak di bawah 18 tahun tidak diperbolehkan masuk ke dalam exhibition hall.</li>
                        <li>Seluruh pengunjung diharuskan berpakaian rapi/formal. Celana pendek dan sandal tidak
                            diperbolehkan.</li>
                        <li>Dilarang membawa senjata api dan senjata tajam ke dalam exhibition hall.</li>
                        <li>Pengunjung tidak diperbolehkan mengambil gambar atau video produk display tanpa seizin
                            exhibitor yang bersangkutan.</li>
                    </ol>
                    <br>
                    <input type="checkbox" id="language" data-on-color="danger" data-off-color="success"
                        data-on-text="English" data-off-text="Indonesia" v-model="language">
                </div>
                <div style="width: 100%;text-align: center;">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><i class="zmdi zmdi-check"></i>
                        Agree</button>
                    <br><br>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from "axios";
import dialphone from "../../Utils/dialphone.js";
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';

export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            temp_id_exhibition: "",
            language: true,
            loading: false,
            exhibition_detail: {
                banner_file: '',
                date: '',
                full_name: '',
                name: '',
                location: '',
                team: '',
            },
            list_exhibitions: [],
            exhibition_id: "",
            list_sub_exhibitions: [],
            sub_exhibition_id: "",
            form: {
                title: "",
                name: "",
                company: "",
                job_title: "",
            },
            form_data: {
                title: [
                    {
                        value: "Mr",
                        label: "Mr",
                    },
                    {
                        value: "Ms",
                        label: "Ms",
                    },
                    {
                        value: "Mrs",
                        label: "Mrs",
                    },
                    {
                        value: "Prof",
                        label: "Prof",
                    },
                    {
                        value: "Dr",
                        label: "Dr",
                    },
                ]
            }

        };
    },
    methods: {
        register() {
            const vm = this;

        },
        get_exhibitions() {
            this.loading = true;
            const vm = this;
            axios.get("api/v1/web/registration/exhibition/get").then(res => {
                if (res.data.status == 1) {
                    vm.list_exhibitions = res.data.data.exhibition_list.map(item => ({
                        label: item.name,
                        value: item.id,
                        banner_file: item.banner_file
                    }));
                    vm.exhibition_detail = {
                        banner_file: res.data.data.exhibition_detail.all_banner,
                        date: res.data.data.exhibition_detail.date,
                        full_name: res.data.data.exhibition_detail.full_name,
                        name: res.data.data.exhibition_detail.name,
                        location: res.data.data.exhibition_detail.location,
                        team: res.data.data.exhibition_detail.team,
                    }

                }
                else {
                    vm.$swal({
                        icon: "error",
                        title: "Error",
                        text: "Error Get Exhibition",
                    });
                }
            }).catch(res => {
                vm.$swal({
                    icon: "error",
                    title: "Error",
                    text: "Error Get Exhibition",
                });
            }).finally(() => {
                this.loading = false;
            });
        },
        on_exhibitions_select(event) {
            const vm = this;
            this.loading = true;
            this.exhibition_detail.banner_file = event.banner_file;
            this.sub_exhibition_id = "";

            axios.post("api/v1/web/registration/sub/exhibition/get", {
                id: event.value
            }).then(res => {
                this.list_sub_exhibitions = res.data.data.map(item => ({
                    label: item.name,
                    value: item.id,
                    banner_file: item.file_banner
                }));
            }).catch(res => {
                vm.$swal({
                    icon: "error",
                    title: "Error",
                    text: "Error Get Sub Exhibition",
                });
            }).finally(() => {
                this.loading = false;
            });

        },
        change_language() {
            this.language = !this.language;
        },
        refresh_page() {
            this.$router.go();
        },
    },
    mounted() {
        const vm = this;
        this.get_exhibitions();
        $("#language").bootstrapSwitch({
            onSwitchChange: function (event, state) {
                vm.change_language();
            }
        });
        // $("#modalWarning").modal({ backdrop: 'static', keyboard: false });
        $('html, body').animate({ scrollTop: 0 }, 'fast');

    }
};
</script>
