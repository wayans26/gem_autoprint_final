<template>
    <div class="card">
        <div class="card-header">
            <bread-crumb></bread-crumb>
            <h5>Barcode Config</h5>
        </div>
        <Form @submit="save_config">
            <div class="card-body">
                <div class="form-group">
                    <label for="input-1">Qr Size *</label>
                    <Field name="qr_size" type="text" class="form-control" id="input-1" placeholder="Qr Size *"
                        v-model="qr_size"></Field>
                </div>
                <div class="form-group">
                    <label for="input-1">Qr Margin *</label>
                    <Field name="qr_margin" type="text" class="form-control" id="input-1" placeholder="Qr Margin *"
                        v-model="qr_margin"></Field>
                </div>
                <div class="form-group">
                    <label for="input-1">Error Correction * (L/M/Q/H)</label>
                    <Field name="error_correction" type="text" class="form-control" id="input-1"
                        placeholder="Error Correction *" v-model="error_correction"></Field>
                </div>
                <div class="form-group">
                    <label for="input-1">Paper Width *</label>
                    <Field name="paper_width" type="text" class="form-control" id="input-1" placeholder="Paper Width *"
                        v-model="paper_width"></Field>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" :disabled="disabled"><i :class="{
                    'fa fa-spinner fa-spin': disabled,
                    'fa fa-edit': !disabled,
                }"></i>
                    Save</button>
                <button type="button" class="btn btn-secondary ml-1" @click="reset_config">
                    Reset Config</button>
            </div>
        </Form>
    </div>

</template>

<script>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import Swal from 'sweetalert2';

export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            disabled: false,
            qr_size: "",
            qr_margin: "",
            error_correction: "",
            paper_width: "",
            paper_height: "",
            qr_position_bottom: "",
            safe_area_bottom: "",
            safe_area_top: "",
            safe_area_right: "",
            safe_area_left: "",
        }
    },
    methods: {
        get_config() {
            const vm = this;
            this.globalLoader.show = true;
            axios.post("/api/v1/web/barcode/config/get", {

            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.qr_size = res.data.data.qr_size.config_value;
                    vm.qr_margin = res.data.data.qr_margin.config_value;
                    vm.error_correction = res.data.data.error_correction.config_value;
                    vm.paper_width = res.data.data.paper_width.config_value;
                    vm.paper_height = res.data.data.paper_height.config_value;
                    vm.qr_position_bottom = res.data.data.qr_position_bottom.config_value;
                    vm.safe_area_bottom = res.data.data.safe_area_bottom.config_value;
                    vm.safe_area_top = res.data.data.safe_area_top.config_value;
                    vm.safe_area_right = res.data.data.safe_area_right.config_value;
                    vm.safe_area_left = res.data.data.safe_area_left.config_value;

                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Get Config!");

            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        reset_config() {
            this.qr_size = "220";
            this.qr_margin = "1";
            this.error_correction = "H";
            this.paper_width = "104.1";
            this.paper_height = "76.2";
            this.qr_position_bottom = "31";
            this.safe_area_bottom = "16";
            this.safe_area_top = "16";
            this.safe_area_right = "4";
            this.safe_area_left = "4";
        },
        save_config() {
            const vm = this;
            this.disabled = true;
            this.globalLoader.show = true;
            axios.post("/api/v1/web/barcode/config/save", {
                qr_size: this.qr_size,
                qr_margin: this.qr_margin,
                error_correction: this.error_correction,
                paper_width: this.paper_width,
                paper_height: this.paper_height,
                qr_position_bottom: this.qr_position_bottom,
                safe_area_bottom: this.safe_area_bottom,
                safe_area_top: this.safe_area_top,
                safe_area_right: this.safe_area_right,
                safe_area_left: this.safe_area_left,
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    swalNotif.success(res.data.message);
                } else {
                    swalNotif.info(res.data.message);
                }
            }).catch(res => {
                swalNotif.error("Error Save Config!");

            }).finally(function () {
                vm.disabled = false;
                vm.globalLoader.show = false;
            });
        },
    },
    mounted() {
        this.get_config();
    }
}
</script>
