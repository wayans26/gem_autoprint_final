<template>
    <div id="wrapper">
        <div class="loader-container" v-if="loading">
            <div class="loader"></div>
        </div>
        <div class="card mx-auto" style="max-width: 80%;">
            <img class="card-img-top" :src="'/registration/banner/exhibitions/' + exhibition_detail.banner_file"
                style="max-width: 100%;" alt="" srcset="">
            <Form @submit="register" :validation-schema="validate_role">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input-1">Exhibition *</label>
                        <v-select placeholder="Select a Exhibition" :options="list_exhibitions" label="label"
                            :reduce="option => option.value" v-model="exhibition_id" :clearable="false"
                            @option:selected="on_exhibitions_select"></v-select>
                    </div>
                    <div class="form-group" v-show="exhibition_id">
                        <label for="input-1">Sub Exhibition *</label>
                        <v-select placeholder="Select a Sub Exhibition" :options="list_sub_exhibitions" label="label"
                            :reduce="option => option.value" v-model="sub_exhibition_id" :clearable="false"></v-select>
                    </div>
                    <div v-show="sub_exhibition_id">
                        <!-- <div> -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Title *</label>
                                    <v-select placeholder="Select a Title" :options="form_data.title" label="label"
                                        :reduce="option => option.value" v-model="form.title"
                                        :clearable="false"></v-select>
                                    <span role="alert" style="color: red;"
                                        v-show="!String(this.form.title ?? '').trim()">Title Can't Be Empty!</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Name *</label>
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
                                    <label>Company *</label>
                                    <div class="position-relative ">
                                        <Field name="company" class="form-control" placeholder="Company"
                                            v-model="form.company" />
                                        <ErrorMessage style="color: red;" name="company" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Job Title *</label>
                                    <div class="position-relative ">
                                        <Field name="job_title" class="form-control" placeholder="Job Title"
                                            v-model="form.job_title" />
                                        <ErrorMessage style="color: red;" name="job_title" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address *</label>
                            <div class="position-relative ">
                                <Field name="address" class="form-control" placeholder="Address"
                                    v-model="form.address" />
                                <ErrorMessage style="color: red;" name="address" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>City/State *</label>
                                    <div class="position-relative ">
                                        <Field name="city" class="form-control" placeholder="*City/State"
                                            v-model="form.city" />
                                        <ErrorMessage style="color: red;" name="city" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Country *</label>
                                    <v-select placeholder="Select a Country" :options="form_data.list_country"
                                        label="label" :reduce="option => option.value" v-model="form.country"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <div class="position-relative ">
                                        <Field name="telepon" class="form-control" placeholder="Telephone"
                                            v-model="form.telepon" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <div class="position-relative ">
                                        <Field name="fax" class="form-control" placeholder="Fax" v-model="form.fax" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mobile Phone *</label>
                            <div class="row">
                                <div class="col-lg-3">
                                    <v-select placeholder="Select a Country" :options="form_data.list_country"
                                        label="label_code" :reduce="option => option.value" v-model="form.dial_code"
                                        :clearable="false"></v-select>
                                </div>
                                <div class="col-lg-9">
                                    <div class="position-relative ">
                                        <Field name="mobilephone" type="number" class="form-control"
                                            placeholder="*Mobile Phone (81xxx) *please fill the phone number properly"
                                            v-model="form.mobile_phone" />
                                        <ErrorMessage style="color: red;" name="mobilephone" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <div class="position-relative ">
                                <Field name="email" type="email" class="form-control" placeholder="*Email"
                                    v-model="form.email" />
                                <ErrorMessage style="color: red;" name="email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Type of Business *</label>
                            <div class="position-relative ">
                                <Field name="type_of_business" class="form-control" placeholder="*Type of Business"
                                    v-model="form.type_of_business" />
                                <ErrorMessage style="color: red;" name="type_of_business" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Job Function *</label>
                                    <v-select placeholder="Select a Job Function" :options="form_data.list_job_function"
                                        label="label" :reduce="option => option.value" v-model="form.job_function"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="form.job_function === 'Other'">
                                <div class="form-group">
                                    <label>Your Other Job Function *</label>
                                    <div class="position-relative ">
                                        <Field name="other_job_function" class="form-control"
                                            placeholder="Other Job Function *" v-model="form.other_job_function" />
                                        <ErrorMessage style="color: red;" name="other_job_function" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Purpose of Visit *</label>
                                    <v-select placeholder="Select a Purpose of Visit"
                                        :options="form_data.list_purpose_visit" label="label"
                                        :reduce="option => option.value" v-model="form.purpose_visit"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="form.purpose_visit === 'Other'">
                                <div class="form-group">
                                    <label>Other Purpose of Visit *</label>
                                    <div class="position-relative ">
                                        <Field name="other_purpose_visit" class="form-control"
                                            placeholder="*Other Purpose of Visit" v-model="form.other_purpose_visit" />
                                        <ErrorMessage style="color: red;" name="other_purpose_visit" />

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">Purchasing Roles *</label>
                                    <v-select name="purchasing_role" placeholder="Select a Purchasing Role"
                                        :options="form_data.list_purchasing_role" label="label"
                                        :reduce="option => option.value" v-model="form.purchasing_role"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="form.purchasing_role === 'Other'">
                                <div class="form-group">
                                    <label>Other Purchasing Roles *</label>
                                    <div class="position-relative ">
                                        <Field name="other_purchasing_role" class="form-control"
                                            placeholder="*Other Purchasing Roles"
                                            v-model="form.other_purchasing_role" />
                                        <ErrorMessage style="color: red;" name="other_purchasing_role" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="input-1">How did you find out about this event? *</label>
                                    <v-select placeholder="Select one of the following"
                                        :options="form_data.list_event_find" label="label"
                                        :reduce="option => option.value" v-model="form.event_find"
                                        :clearable="false"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-6" v-if="form.event_find === 'Other'">
                                <div class="form-group">
                                    <label>Other *</label>
                                    <div class="position-relative ">
                                        <Field name="other_event_find" class="form-control"
                                            placeholder="*How did you find out about this event?"
                                            v-model="form.other_event_find" />
                                        <ErrorMessage style="color: red;" name="other_event_find" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p>*Would like to receive the invitation for next year event?</p>
                        <div class="icheck-material-primary icheck-inline">
                            <Field v-model="form.is_received_invitation_next" type="radio"
                                id="IsReceivedInvitationNextYes" name="IsReceivedInvitationNext" value="1" checked>
                            </Field>
                            <label for="IsReceivedInvitationNextYes">Yes</label>
                        </div>
                        <div v-if="form.is_received_invitation_next === '1'" style="padding-left: 25px;">
                            <div class="icheck-material-primary icheck-inline">
                                <Field v-model="form.is_received_invitation_next_same_address" type="radio"
                                    id="IsReceivedInvitationNextAddressSameYes"
                                    name="IsReceivedInvitationNextAddressSame" value="1" checked>
                                </Field>
                                <label for="IsReceivedInvitationNextAddressSameYes">Address same as above (Complete
                                    the above address)</label>
                            </div>
                            <br>
                            <div class="icheck-material-info icheck-inline">
                                <Field v-model="form.is_received_invitation_next_same_address" type="radio"
                                    id="IsReceivedInvitationNextAddressSameNo"
                                    name="IsReceivedInvitationNextAddressSame" value="0">
                                </Field>
                                <label for="IsReceivedInvitationNextAddressSameNo">Other Address</label>
                            </div>
                            <div v-if="form.is_received_invitation_next_same_address === '0'" class="form-group"
                                style="padding-left: 15px;">
                                <div class="position-relative ">
                                    <Field name="ReceivedInvitationNextAddress" class="form-control"
                                        placeholder="*Other Address" v-model="form.recived_invitation_address" />
                                    <ErrorMessage style="color: red;" name="ReceivedInvitationNextAddress" />
                                </div>
                            </div>
                        </div>
                        <div class="icheck-material-info icheck-inline">
                            <Field v-model="form.is_received_invitation_next" type="radio"
                                id="IsReceivedInvitationNextNo" name="IsReceivedInvitationNext" value="0">
                            </Field>

                            <label for="IsReceivedInvitationNextNo">No</label>
                        </div>
                        <ErrorMessage style="color: red;" name="IsReceivedInvitationNext" />
                    </div>
                </div>
                <div class="card-footer" v-show="sub_exhibition_id">
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
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import swalNotif from "../../Utils/swalNotif";

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
                address: "",
                city: "",
                country: 99,
                dial_code: 99,
                telepon: "",
                fax: "",
                mobile_phone: "",
                email: "",
                type_of_business: "",
                job_function: "",
                other_job_function: "",
                purpose_visit: "",
                other_purpose_visit: "",
                purchasing_role: "",
                other_purchasing_role: "",
                event_find: "",
                other_event_find: "",
                is_received_invitation_next: "1",
                is_received_invitation_next_same_address: "1",
                recived_invitation_address: "",
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
                ],
                list_country: [],
                list_job_function: [
                    {
                        value: "CEO",
                        label: "CEO"
                    },
                    {
                        value: "Director",
                        label: "Director"
                    },
                    {
                        value: "Manager",
                        label: "Manager"
                    },
                    {
                        value: "Sales & Marketing",
                        label: "Sales & Marketing"
                    },
                    {
                        value: "Production/Manufacturing",
                        label: "Production/Manufacturing"
                    },
                    {
                        value: "Technical Manager",
                        label: "Technical Manager"
                    },
                    {
                        value: "Design",
                        label: "Design"
                    },
                    {
                        value: "Consultant",
                        label: "Consultant"
                    },
                    {
                        value: "R&D",
                        label: "R&D"
                    },
                    {
                        value: "Technical",
                        label: "Technical"
                    },
                    {
                        value: "Quality Control",
                        label: "Quality Control"
                    },
                    {
                        value: "Other",
                        label: "Other"
                    }
                ],
                list_purpose_visit: [
                    {
                        value: "R&D",
                        label: "R&D"
                    },
                    {
                        value: "Sales & Marketing",
                        label: "Sales & Marketing"
                    },
                    {
                        value: "Looking for Representative",
                        label: "Looking for Representative"
                    },
                    {
                        value: "Make Contact",
                        label: "Make Contact"
                    },
                    {
                        value: "Future Participant",
                        label: "Future Participant"
                    },
                    {
                        value: "Other",
                        label: "Other"
                    }
                ],
                list_purchasing_role: [
                    {
                        value: "None",
                        label: "None"
                    },
                    {
                        value: "Final Decision",
                        label: "Final Decision"
                    },
                    {
                        value: "Significant Influence",
                        label: "Significant Influence"
                    },
                    {
                        value: "Initial Recommendations",
                        label: "Initial Recommendations"
                    },
                    {
                        value: "Research of New Products",
                        label: "Research of New Products"
                    },
                    {
                        value: "Other",
                        label: "Other"
                    }
                ],
                list_event_find: [
                    {
                        value: "Email Campaign",
                        label: "Email Campaign"
                    },
                    {
                        value: "Website",
                        label: "Website"
                    },
                    {
                        value: "Invitation",
                        label: "Invitation"
                    },
                    {
                        value: "Facebook/Instagram",
                        label: "Facebook/Instagram"
                    },
                    {
                        value: "Association",
                        label: "Association"
                    },
                    {
                        value: "Magazine",
                        label: "Magazine"
                    },
                    {
                        value: "Other",
                        label: "Other"
                    }
                ],
            },
            validate_role: yup.object({
                name: yup.string().required("Name Can't Be Empty!"),
                company: yup.string().required("Company Can't Be Empty!"),
                job_title: yup.string().required("Job Title Can't Be Empty!"),
                address: yup.string().required("Address Can't Be Empty!"),
                city: yup.string().required("City Can't Be Empty!"),
                mobilephone: yup.string().required("Mobile Phone Can't Be Empty!"),
                email: yup.string().required("Email Can't Be Empty!").email("Invalid Email"),
                type_of_business: yup.string().required("Type of Business Can't Be Empty!"),
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
            if (!this.validate_select()) {
                return;
            }
            console.log("REGISTER");

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
        get_country() {
            this.loading = true;
            const vm = this;
            axios.get("/api/v1/web/registration/country/get").then(res => {
                if (res.data.status == 1) {
                    this.form_data.list_country = res.data.data.map(item => ({
                        label: item.country_name,
                        label_code: `(${item.dial_code}) ${item.country_name}`,
                        value: item.id
                    }));
                }
                else {
                    vm.$swal({
                        icon: "error",
                        title: "Error",
                        text: "Error Get Country",
                    });
                }
            }).catch(res => {
                vm.$swal({
                    icon: "error",
                    title: "Error",
                    text: "Error Get Country",
                });
            }).finally(() => {
                this.loading = false;
            });
        },
        validate_select() {
            if (!String(this.form.title ?? '').trim()) {
                return false;
            }
            if (!String(this.form.country ?? '').trim()) {
                swalNotif.info("Country Can't Be Empty!");
                return false;
            }
            if (!String(this.form.dial_code ?? '').trim()) {
                swalNotif.info("Mobile Phone Dial Code Can't Be Empty!");
                return false;
            }
            if (!String(this.form.job_function ?? '').trim()) {
                swalNotif.info("Job Function Can't Be Empty!");
                return false;
            }
            if (this.form.job_function === 'Other') {
                if (!String(this.form.other_job_function ?? '').trim()) {
                    swalNotif.info("Other Job Function Can't Be Empty!");
                    return false;
                }
            }
            if (!String(this.form.purpose_visit ?? '').trim()) {
                swalNotif.info("Purpose Visit Can't Be Empty!");
                return false;
            }
            if (this.form.purpose_visit === 'Other') {
                if (!String(this.form.other_purpose_visit ?? '').trim()) {
                    swalNotif.info("Other Purpose Visit Can't Be Empty!");
                    return false;
                }
            }
            if (!String(this.form.purchasing_role ?? '').trim()) {
                swalNotif.info("Purchasing Role Can't Be Empty!");
                return false;
            }
            if (this.form.purchasing_role === 'Other') {
                if (!String(this.form.other_purchasing_role ?? '').trim()) {
                    swalNotif.info("Other Purchasing Role Can't Be Empty!");
                    return false;
                }
            }
            if (!String(this.form.event_find ?? '').trim()) {
                swalNotif.info("Event Find Can't Be Empty!");
                return false;
            }
            if (this.form.event_find === 'Other') {
                if (!String(this.form.other_event_find ?? '').trim()) {
                    swalNotif.info("Other Event Find Can't Be Empty!");
                    return false;
                }
            }
            return true;
        }
    },
    mounted() {
        const vm = this;
        this.get_exhibitions();
        this.get_country();
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
