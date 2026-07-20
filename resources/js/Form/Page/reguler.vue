<template>
    <div id="wrapper">
        <div class="loader-container" v-if="loading">
            <div class="loader"></div>
        </div>
        <div class="card mx-auto" style="max-width: 80%;">
            <img v-if="!exhibition_id" class="card-img-top"
                :src="'https://gateway.reg-gemindonesia.net/banner/exhibitions/all/' + temp_id_exhibition"
                style="max-width: 100%;" alt="" srcset="">
            <img v-else class="card-img-top"
                :src="'https://gateway.reg-gemindonesia.net/banner/exhibitions/' + exhibition_id"
                style="max-width: 100%;" alt="" srcset="">
            <div class="card-body">
                <Form :validation-schema="validate_role" @submit="register">
                    <div class="form-group">
                        <label for="title">*Exhibition</label>
                        <select @change="on_exhibitions_select" v-model="exhibition_id" class="form-control">
                            <option value="" selected disabled>==SELECT EXHIBITIONS==</option>
                            <option v-for="(item, index) in list_exhibitions" :key="index" :value="item.idexhibitions">
                                {{ item.name }}</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="exhibition_id">
                        <label for="title">*Sub Exhibition</label>
                        <select v-model="sub_exhibition_id" class="form-control" @change="getTypeOfBusiness">
                            <option value="" selected disabled>==SELECT SUB EXHIBITIONS==</option>
                            <option v-for="(item, index) in list_sub_exhibitions" :key="index"
                                :value="item.idsubexhibitions">
                                {{ item.nama }}</option>
                        </select>
                    </div>

                    <div v-if="sub_exhibition_id">
                        <div class="row">
                            <div class="col-lg-6">
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Name</label>
                                    <div class="position-relative ">
                                        <Field name="name" class="form-control" placeholder="Name" v-model="name" />
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
                                            v-model="company" />
                                        <ErrorMessage style="color: red;" name="company" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Job Title</label>
                                    <div class="position-relative ">
                                        <Field name="job_title" class="form-control" placeholder="Job Title"
                                            v-model="job_title" />
                                        <ErrorMessage style="color: red;" name="job_title" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>*Address</label>
                            <div class="position-relative ">
                                <Field name="address" class="form-control" placeholder="Address" v-model="address" />
                                <ErrorMessage style="color: red;" name="address" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*City/State</label>
                                    <div class="position-relative ">
                                        <Field name="city" class="form-control" placeholder="*City/State"
                                            v-model="city" />
                                        <ErrorMessage style="color: red;" name="city" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Country</label>
                                    <div class="position-relative ">
                                        <Field name="selectedcountry" as="select" class="form-control"
                                            placeholder="Country" v-model="selectedcountry">
                                            <option value="" selected disabled>==SELECT COUNTRY==</option>
                                            <option v-for="(item, index) in country" :key="index" :value="item">{{ item
                                            }}
                                            </option>
                                        </Field>
                                        <ErrorMessage style="color: red;" name="selectedcountry" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <div class="position-relative ">
                                        <Field name="telepon" class="form-control" placeholder="Telephone"
                                            v-model="telepon" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <div class="position-relative ">
                                        <Field name="fax" class="form-control" placeholder="Fax" v-model="fax" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>*Mobile Phone</label>
                            <div class="row">
                                <div class="col-lg-3">
                                    <select v-model="phonecode" class="form-control">
                                        <option v-for="(item, index) in dialphone" :key="index" :value="item.dial_code"
                                            :selected="item.code == 'ID'">
                                            {{ item.name + '(' + item.dial_code + ')' }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-9">
                                    <div class="position-relative ">
                                        <Field name="mobilephone" type="number" class="form-control"
                                            placeholder="*Mobile Phone (81xxx) *please fill the phone number properly"
                                            v-model="mobilephone" />
                                        <ErrorMessage style="color: red;" name="mobilephone" />
                                    </div>
                                </div>
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

                        <div v-if="list_type_of_business.length > 0">
                            <div class="form-group">
                                <label>*Type of Business</label>
                                <div class="position-relative ">
                                    <Field name="type_of_business" as="select" class="form-control"
                                        placeholder="Country" v-model="type_of_business">
                                        <option value="" checked disabled>==SELECT TYPE OF BUSINESS==</option>
                                        <option v-for="(item, index) in list_type_of_business" :key="index"
                                            :value="item">{{ item }}</option>
                                    </Field>
                                    <ErrorMessage style="color: red;" name="type_of_business" />
                                </div>
                            </div>

                            <div class="form-group" v-if="type_of_business === 'Others'">
                                <label>*Other Type of Business</label>
                                <div class="position-relative ">
                                    <Field name="other_type_of_business" class="form-control"
                                        placeholder="*Other Type of Business" v-model="other_type_of_business" />
                                    <ErrorMessage style="color: red;" name="other_type_of_business" />
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="form-group">
                                <label>*Type of Business</label>
                                <div class="position-relative ">
                                    <Field name="type_of_business" class="form-control" placeholder="*Type of Business"
                                        v-model="type_of_business" />
                                    <ErrorMessage style="color: red;" name="type_of_business" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Your Job Function</label>
                                    <div class="position-relative ">
                                        <Field name="job_function" as="select" class="form-control"
                                            placeholder="Country" v-model="job_function">
                                            <option value="" checked disabled>Select Job Function</option>
                                            <option v-for="(item, index) in list_job_function" :key="index"
                                                :value="index">{{ item
                                                }}
                                            </option>
                                        </Field>
                                        <ErrorMessage style="color: red;" name="job_function" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="job_function === 11">
                                <div class="form-group">
                                    <label>*Your Job Function</label>
                                    <div class="position-relative ">
                                        <Field name="other_job_function" class="form-control"
                                            placeholder="*Other Job Function" v-model="other_job_function" />
                                        <ErrorMessage style="color: red;" name="other_job_function" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>*Purpose of Visit</label>
                                    <div class="position-relative ">
                                        <Field name="purpose_visit" as="select" class="form-control"
                                            placeholder="Country" v-model="purpose_visit">
                                            <option value="" checked disabled>Select Job Function</option>
                                            <option v-for="(item, index) in list_purpose_visit" :key="index"
                                                :value="index">{{ item
                                                }}
                                            </option>
                                        </Field>
                                        <ErrorMessage style="color: red;" name="purpose_visit" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="purpose_visit === 5">
                                <div class="form-group">
                                    <label>*Purpose of Visit</label>
                                    <div class="position-relative ">
                                        <Field name="other_purpose_visit" class="form-control"
                                            placeholder="*Other Purpose of Visit" v-model="other_purpose_visit" />
                                        <ErrorMessage style="color: red;" name="other_purpose_visit" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Purchasing Roles</label>
                                    <div class="position-relative ">
                                        <Field name="purchasing_role" as="select" class="form-control"
                                            placeholder="Country" v-model="purchasing_role">
                                            <option value="" checked disabled>Select Job Function</option>
                                            <option v-for="(item, index) in list_purchasing_role" :key="index"
                                                :value="index">{{ item
                                                }}
                                            </option>
                                        </Field>
                                        <ErrorMessage style="color: red;" name="purchasing_role" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="purchasing_role === 5">
                                <div class="form-group">
                                    <label>Purchasing Roles</label>
                                    <div class="position-relative ">
                                        <Field name="other_purchasing_role" class="form-control"
                                            placeholder="*Other Purchasing Roles" v-model="other_purchasing_role" />
                                        <ErrorMessage style="color: red;" name="other_purchasing_role" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>How did you find out about this event?</label>
                                    <div class="position-relative ">
                                        <Field name="event_find" as="select" class="form-control" placeholder="Country"
                                            v-model="event_find">
                                            <option value="" checked disabled>Select Job Function</option>
                                            <option v-for="(item, index) in list_event_find" :key="index"
                                                :value="index">{{ item
                                                }}
                                            </option>
                                        </Field>
                                        <ErrorMessage style="color: red;" name="event_find" />

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="event_find === 6">
                                <div class="form-group">
                                    <label>How did you find out about this event?</label>
                                    <div class="position-relative ">
                                        <Field name="other_event_find" class="form-control"
                                            placeholder="*How did you find out about this event?"
                                            v-model="other_event_find" />
                                        <ErrorMessage style="color: red;" name="other_event_find" />
                                    </div>
                                </div>
                            </div>
                        </div>


                        <p>*Would like to receive the invitation for next year event?</p>
                        <div class="icheck-material-primary icheck-inline">
                            <Field v-model="IsReceivedInvitationNext" type="radio" id="IsReceivedInvitationNextYes"
                                name="IsReceivedInvitationNext" value="1" checked>
                            </Field>
                            <label for="IsReceivedInvitationNextYes">Yes</label>
                        </div>
                        <div v-if="IsReceivedInvitationNext === '1'" style="padding-left: 25px;">
                            <div class="icheck-material-primary icheck-inline">
                                <Field v-model="IsReceivedInvitationNextAddressSame" type="radio"
                                    id="IsReceivedInvitationNextAddressSameYes"
                                    name="IsReceivedInvitationNextAddressSame" value="1" checked>
                                </Field>
                                <label for="IsReceivedInvitationNextAddressSameYes">Address same as above (Complete
                                    the above address)</label>
                            </div>
                            <br>
                            <div class="icheck-material-info icheck-inline">
                                <Field v-model="IsReceivedInvitationNextAddressSame" type="radio"
                                    id="IsReceivedInvitationNextAddressSameNo"
                                    name="IsReceivedInvitationNextAddressSame" value="0">
                                </Field>
                                <label for="IsReceivedInvitationNextAddressSameNo">Other Address</label>
                            </div>
                            <div v-if="IsReceivedInvitationNextAddressSame === '0'" class="form-group"
                                style="padding-left: 15px;">
                                <div class="position-relative ">

                                    <Field name="ReceivedInvitationNextAddress" class="form-control"
                                        placeholder="*Other Address" v-model="ReceivedInvitationNextAddress" />
                                    <ErrorMessage style="color: red;" name="ReceivedInvitationNextAddress" />
                                </div>
                            </div>
                        </div>
                        <div class="icheck-material-info icheck-inline">
                            <Field v-model="IsReceivedInvitationNext" type="radio" id="IsReceivedInvitationNextNo"
                                name="IsReceivedInvitationNext" value="0">
                            </Field>

                            <label for="IsReceivedInvitationNextNo">No</label>
                        </div>

                        <br><br>
                        <ErrorMessage style="color: red;" name="IsReceivedInvitationNext" />
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
                        <p style="color: red;">We look forward to welcoming you at {{ eventName }}</p>
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
                        <button type="button" class="btn btn-success" data-dismiss="modal"><i
                                class="zmdi zmdi-check"></i>
                            Agree</button>
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
            eventName: "",
            loading: false,
            errors: "",
            dialphone,
            country: [],
            list_exhibitions: [],
            list_sub_exhibitions: [],
            exhibition_id: "",
            sub_exhibition_id: "",
            verifikasi: true,
            verifikasi_otp: true,
            btn_otp: false,
            btn_otp_count: 60,
            code_otp: "",
            btn_verifikasi: false,
            phonecode: "+62",
            mobilephone: "",
            title: "",
            name: "",
            company: "",
            job_title: "",
            address: "",
            city: "",
            selectedcountry: "Indonesia",
            telepon: "",
            fax: "",
            email: "",
            list_type_of_business: [],
            type_of_business: "",
            other_type_of_business: "",
            list_job_function: [
                "CEO",
                "Director",
                "Manager",
                "Sales & Marketing",
                "Production/Manufacturing",
                "Technical Manager",
                "Design",
                "Consultant",
                "R&D",
                "Technical",
                "Quality Control",
                "Other",
            ],
            job_function: "",
            other_job_function: "",
            list_purpose_visit: [
                "R&D",
                "Sales & Marketing",
                "Looking for Representative",
                "Make Contact",
                "Future Participant",
                "Other",
            ],
            purpose_visit: "",
            other_purpose_visit: "",
            list_purchasing_role: [
                "None",
                "Final Decision",
                "Significant Influence",
                "Initial Recommendations",
                "Research of New Products",
                "Other",
            ],
            purchasing_role: "",
            other_purchasing_role: "",
            list_event_find: [
                "Email Campaign",
                "Website",
                "Invitation",
                "Facebook/Instagram",
                "Association",
                "Magazine",
                "Other"
            ],
            event_find: "",
            other_event_find: "",
            IsReceivedInvitationNext: "1",
            IsReceivedInvitationNextAddressSame: "1",
            ReceivedInvitationNextAddress: "",
            validate_role: yup.object({
                title: yup.string().required("Title Can't Be Empty!"),
                name: yup.string().required("Name Can't Be Empty!"),
                company: yup.string().required("Company Can't Be Empty!"),
                job_title: yup.string().required("Job Title Can't Be Empty!"),
                address: yup.string().required("Address Can't Be Empty!"),
                city: yup.string().required("City Can't Be Empty!"),
                mobilephone: yup.string().required("Mobile Phone Can't Be Empty!"),
                email: yup.string().required("Email Can't Be Empty!").email("Invalid Email"),
                type_of_business: yup.string().required("Type of Business Can't Be Empty!"),
                other_type_of_business: yup.string().when('type_of_business', {
                    is: "Others",
                    then: validate_role => validate_role.required("Other Type of Business Can't Be Empty!"),
                }),
                job_function: yup.number().required("Job Function Can't Be Empty!"),
                other_job_function: yup.string().when('job_function', {
                    is: 11,
                    then: validate_role => validate_role.required("Other Job Function Can't Be Empty!"),
                }),
                purpose_visit: yup.number().required("Purpose Visit Can't Be Empty!"),
                other_purpose_visit: yup.string().when('purpose_visit', {
                    is: 5,
                    then: validate_role => validate_role.required("Other Purpose Visit Can't Be Empty!"),
                }),
                purchasing_role: yup.number().required("Purchasing Role Can't Be Empty!"),
                other_purchasing_role: yup.string().when('purchasing_role', {
                    is: 5,
                    then: validate_role => validate_role.required("Other Purchasing Role Can't Be Empty!"),
                }),
                event_find: yup.number().required("Event Find Can't Be Empty!"),
                other_event_find: yup.string().when('event_find', {
                    is: 6,
                    then: validate_role => validate_role.required("Other Event Find Can't Be Empty!"),
                }),
                ReceivedInvitationNextAddress: yup.string().when('IsReceivedInvitationNextAddressSame', {
                    is: "0",
                    then: validate_role => validate_role.email("Invalid Email").required("Email Can't Be Empty!"),
                }),
            })
        };
    },
    methods: {
        register() {
            const vm = this;
            this.loading = true;
            if (!this.exhibition_id) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Exhibitions!",
                });
                this.loading = false;
                return;
            }
            if (!this.sub_exhibition_id) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Sub Exhibitions!",
                });
                this.loading = false;
                return;
            }
            if (!this.name) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Name Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.mobilephone) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Mobile Phohe Cant be Empty!!",
                });
                this.loading = false;
                return;
            }
            if (!this.company) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Company Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.job_title) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Job Title Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.address) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Address Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.city) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "City Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.selectedcountry) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Country!",
                });
                this.loading = false;
                return;
            }

            if (!this.email) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Email Cant be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.type_of_business) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Type Of Bussines!",
                });
                this.loading = false;
                return;
            }

            if (this.event_find === "") {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Event Find!",
                });
                this.loading = false;
                return;
            }
            if (this.job_function === "") {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Job Function!",
                });
                this.loading = false;
                return;
            }
            if (this.purpose_visit === "") {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Purpose Visit!",
                });
                this.loading = false;
                return;
            }
            if (this.purchasing_role === "") {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Choose Your Purchasing Role!",
                });
                this.loading = false;
                return;
            }

            $.ajax({
                url: "/api/registrasi",
                type: "POST",
                data: {
                    exhibition_id: vm.exhibition_id,
                    sub_exhibition_id: vm.sub_exhibition_id,
                    phonecode: vm.phonecode,
                    mobilephone: vm.mobilephone,
                    title: vm.title,
                    name: vm.name,
                    company: vm.company,
                    job_title: vm.job_title,
                    address: vm.address,
                    city: vm.city,
                    selectedcountry: vm.selectedcountry,
                    telepon: vm.telepon,
                    fax: vm.fax,
                    email: vm.email,
                    type_of_business: vm.list_type_of_business.length > 0 ? (vm.type_of_business == "Other" ? vm.other_type_of_business : vm.type_of_business) : vm.type_of_business,
                    job_function: vm.job_function,
                    other_job_function: vm.other_job_function,
                    purpose_visit: vm.purpose_visit,
                    other_purpose_visit: vm.other_purpose_visit,
                    purchasing_role: vm.purchasing_role,
                    other_purchasing_role: vm.other_purchasing_role,
                    event_find: vm.event_find,
                    other_event_find: vm.other_event_find,
                    IsReceivedInvitationNext: vm.IsReceivedInvitationNext,
                    IsReceivedInvitationNextAddressSame: vm.IsReceivedInvitationNextAddressSame,
                    ReceivedInvitationNextAddress: vm.ReceivedInvitationNextAddress,
                },
                complete: (res) => {
                    this.loading = false;

                },
                statusCode: {
                    200: function (data) {
                        if (data.status === 1) {
                            $("#modalInfo").modal({ backdrop: 'static', keyboard: false });
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
            this.loading = true;
            const vm = this;
            $.ajax({
                url: "/api/exhibitions/get",
                type: "GET",
                data: {
                },
                success: function (data) {
                    vm.list_exhibitions = data.data;
                    vm.temp_id_exhibition = data.data[0].idexhibitions;
                },
                error: function (err) {
                },
                complete: (res) => {
                    this.loading = false;
                }
            });
        },
        on_exhibitions_select() {
            const vm = this;
            const foundObj = vm.list_exhibitions.find(obj => obj.idexhibitions === vm.exhibition_id);
            if (foundObj) {
                vm.eventName = foundObj.event_name;
            }

            this.loading = true;

            this.list_sub_exhibitions = [];
            $.ajax({
                url: "/api/subexhibitions/get",
                type: "GET",
                data: {
                    code: vm.exhibition_id
                },
                success: function (data) {
                    if (data.status === 1) {
                        vm.list_sub_exhibitions = data.data;
                    }
                    else {
                        vm.$swal({
                            icon: "error",
                            title: "Error",
                            text: "Error Get Sub Exhibition",
                        });
                    }
                },
                error: function (err) {
                    if (err.status === 308) {
                        window.location.href = "https://reg-gemindonesia.net/busworld?utm_source=whatsapp&utm_medium=messaging&utm_campaign=busworld_sea_2026";
                    }
                },
                complete: (res) => {
                    this.loading = false;
                }
            });
        },
        change_language() {
            this.language = !this.language;
        },
        refresh_page() {
            this.$router.go();
        },
        getTypeOfBusiness() {
            const foundObj = this.list_sub_exhibitions.find(obj => obj.idsubexhibitions === this.sub_exhibition_id);
            if (foundObj && Object.hasOwn(foundObj, "typeBussiness")) {
                this.list_type_of_business = foundObj.typeBussiness;
            }
            else {
                this.list_type_of_business = [];
            }
        }
    },
    mounted() {
        const vm = this;
        this.get_exhibitions();
        $("#language").bootstrapSwitch({
            onSwitchChange: function (event, state) {
                vm.change_language();
            }
        });
        $("#modalWarning").modal({ backdrop: 'static', keyboard: false });
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        console.log("MASUK");

    }
};
</script>
