<template>
    <div id="wrapper">
        <div class="card mx-auto" style="max-width: 80%;">
            <img v-if="!exhibition_id && temp_id_exhibition" class="card-img-top"
                :src="'https://gateway.reg-gemindonesia.net/banner/exhibitions/all/' + temp_id_exhibition"
                style="max-width: 100%;" alt="" srcset="">
            <img v-else-if="exhibition_id" class="card-img-top"
                :src="'https://gateway.reg-gemindonesia.net/banner/exhibitions/' + exhibition_id"
                style="max-width: 100%;" alt="" srcset="">
            <div class="card-body">
                <Form :validation-schema="validate_role" @submit="register">
                    <div class="form-group" v-if="isSelectExhibitions">
                        <label for="title">*Exhibition</label>
                        <select @change="on_exhibitions_select" v-model="exhibition_id" class="form-control">
                            <option value="" selected disabled>==SELECT EXHIBITIONS==</option>
                            <option v-for="(item, index) in list_exhibitions" :key="index" :value="item.idexhibitions">
                                {{ item.name }}</option>
                        </select>
                    </div>
                    <div v-if="!isSelectExhibitions || exhibition_id">
                        <div class="form-group">
                            <label for="invitationby">*Invited By</label>
                            <div class="position-relative">
                                <Field name="invite" class="form-control" placeholder="Invited By" v-model="invite" />
                                <ErrorMessage style="color: red;" name="invite" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="title">*Title</label>
                                    <Field name="title" as="select" class="form-control" placeholder="Title"
                                        v-model="title">
                                        <option value="" selected disabled>==SELECT TITLE==</option>
                                        <option value="0">Mr</option>
                                        <option value="1">Ms</option>
                                        <option value="2">Mrs</option>
                                        <option value="3">Prof</option>
                                        <option value="4">Dr</option>
                                    </Field>
                                    <ErrorMessage style="color: red;" name="title" />
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label>*Name</label>
                                    <div class="position-relative ">
                                        <Field name="name" class="form-control" placeholder="Name" v-model="name" />
                                        <ErrorMessage style="color: red;" name="name" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Company</label>
                            <div class="position-relative ">
                                <Field name="company" class="form-control" placeholder="Company" v-model="company" />
                                <ErrorMessage style="color: red;" name="company" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Job Title</label>
                            <div class="position-relative ">
                                <Field name="jobtitle" class="form-control" placeholder="Job Title"
                                    v-model="jobtitle" />
                                <ErrorMessage style="color: red;" name="jobtitle" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Address</label>
                            <div class="position-relative ">
                                <Field name="address" class="form-control" placeholder="Address" v-model="address" />
                                <ErrorMessage style="color: red;" name="address" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*City/State</label>
                            <div class="position-relative ">
                                <Field name="city" class="form-control" placeholder="*City/State" v-model="city" />
                                <ErrorMessage style="color: red;" name="city" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Country</label>
                            <div class="position-relative ">
                                <Field name="selectedcountry" as="select" class="form-control" placeholder="Country"
                                    v-model="selectedcountry">
                                    <option value="" selected disabled>==SELECT COUNTRY==</option>
                                    <option v-for="(item, index) in country" :key="index" :value="item">{{ item }}
                                    </option>
                                </Field>
                                <ErrorMessage style="color: red;" name="selectedcountry" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Telephone</label>
                                    <div class="position-relative ">
                                        <Field name="telepon" class="form-control" placeholder="Telephone"
                                            v-model="telepon" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>FAX</label>
                                    <div class="position-relative ">
                                        <Field name="fax" class="form-control" placeholder="Fax" v-model="fax" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Mobile Phone</label>
                            <div class="position-relative ">
                                <Field name="mobilephone" type="number" class="form-control" placeholder="*Mobile Phone"
                                    v-model="mobilephone" />
                                <ErrorMessage style="color: red;" name="mobilephone" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Email</label>
                            <div class="position-relative ">
                                <Field name="email" type="email" class="form-control" placeholder="*Email"
                                    v-model="email" />
                                <ErrorMessage style="color: red;" name="email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Type of Business</label>
                            <div class="position-relative ">
                                <Field name="typeofbusiness" class="form-control" placeholder="*Type of Business"
                                    v-model="typeofbusiness" />
                                <ErrorMessage style="color: red;" name="typeofbusiness" />
                            </div>
                        </div>
                        <br><br>
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
                        <h5>Thank you for your registration and welcome to our VIP Guest.</h5>
                        <p>We are pleased to inform you that your registration code will be sent to your email shortly.
                            Please show your registration code and your VIP invitation at the VIP counter for badge
                            collection.
                            Please check your inbox /spam folder to receive the confirmation email.
                        </p>
                        <p style="color: red;">If you have not received the confirmation letter, please contact our
                            staff
                            :</p>
                        <a href="mailto:auto.info@gem-indonesia.net">auto.info@gem-indonesia.net</a>
                        <br><br>
                        <p style="color: red;">We look forward to welcoming you at {{ keterangan }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import errorMessage from "../General/errorMessage.vue";
import country from "../../Utils/country.js"
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';

export default {
    components: {
        errorMessage,
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            eventName: "VIP AUTOMOTIVE ( 21 - 23 May 2025 )",
            loading: false,
            errors: "",
            isSelectExhibitions: false,
            country,
            selectedcountry: "",
            invite: "",
            title: "",
            name: "",
            company: "",
            jobtitle: "",
            address: "",
            city: "",
            telepon: "",
            fax: "",
            mobilephone: "",
            email: "",
            typeofbusiness: "",
            list_exhibitions: [],
            list_sub_exhibitions: [],
            exhibition_id: "",
            sub_exhibition_id: "",
            temp_id_exhibition: "",
            validate_role: yup.object({
                invite: yup.string().required("INVITATION BY Can't Be Empty!"),
                name: yup.string().required("Name Can't Be Empty!"),
                title: yup.string().required("Title Can't Be Empty!"),
                selectedcountry: yup.string().required("Country Can't Be Empty!"),
                company: yup.string().required("Company Can't Be Empty!"),
                jobtitle: yup.string().required("Job Title Can't Be Empty!"),
                address: yup.string().required("Address Can't Be Empty!"),
                city: yup.string().required("City Can't Be Empty!"),
                mobilephone: yup.string().required("Mobile Phone Can't Be Empty!"),
                email: yup.string().required("Email Can't Be Empty!").email("Invalid Email!"),
                typeofbusiness: yup.string().required("Type Of Business Can't Be Empty!"),
            }),
            keterangan: "",
        };
    },
    methods: {
        register() {
            let vm = this;
            this.loading = true;

            $.ajax({
                url: "/api/vip/registrasi",
                type: "POST",
                data: {
                    selectedcountry: this.selectedcountry,
                    invite: this.invite,
                    title: this.title,
                    name: this.name,
                    company: this.company,
                    jobtitle: this.jobtitle,
                    address: this.address,
                    city: this.city,
                    telepon: this.telepon,
                    fax: this.fax,
                    mobilephone: this.mobilephone,
                    email: this.email,
                    typeofbusiness: this.typeofbusiness,
                    exhibition_id: this.exhibition_id
                },
                complete: (res) => {
                    this.loading = false;
                },
                statusCode: {
                    200: function (data) {
                        if (data.status === 1) {
                            $("#modalInfo").modal('show');
                            vm.selectedcountry = "";
                            vm.invite = "";
                            vm.title = "";
                            vm.name = "";
                            vm.company = "";
                            vm.jobtitle = "";
                            vm.address = "";
                            vm.city = "";
                            vm.telepon = "";
                            vm.fax = "";
                            vm.mobilephone = "";
                            vm.email = "";
                            vm.typeofbusiness = "";
                            vm.exhibition_id = "";
                        } else if (data.status === 422) {
                            //go to verifikasi page
                            vm.$swal({
                                icon: "info",
                                title: "Information",
                                text: "Error Validasi",
                            });
                            vm.errors = data.data;
                        } else {
                            vm.$swal({
                                icon: "info",
                                title: "Information",
                                text: data.message,
                            });
                        }
                    },
                },
            });
        },
        get_exhibitions() {
            const vm = this;
            $.ajax({
                url: "/api/exhibitions/vip/get",
                type: "GET",
                data: {
                },
                success: function (data) {
                    vm.list_exhibitions = data.data;
                    vm.temp_id_exhibition = data.data[0].idexhibitions;
                    vm.keterangan = data.data[0].keterangan;
                    vm.isSelectExhibitions = data.data.length > 1 ? true : false;
                },
                error: function (err) {
                }
            });
        },
        on_exhibitions_select() {
            const vm = this;
            $.ajax({
                url: "/api/subexhibitions/get",
                type: "GET",
                data: {
                    code: vm.exhibition_id
                },
                success: function (data) {

                },
                error: function (err) {
                    if (err.status === 308) {
                        window.location.href = "https://vipgreentech.reg-gemindonesia.net";
                    }
                },
                complete: (res) => {
                    this.loading = false;
                }
            });
        },
    },
    mounted() {
        this.get_exhibitions();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }
};
</script>
