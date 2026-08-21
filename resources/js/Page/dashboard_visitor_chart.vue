<template>
    <div class="card">
        <div class="card-header">
            <bread-crumb></bread-crumb>
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="mb-0">Visitor Chart</h5>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary" @click="go_export_history">
                        <i class="fa fa-history"></i>
                        Export History
                    </button>
                    <button type="button" class="btn btn-primary" @click="open_export_modal">
                        <i class="fa fa-download"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="visitorChartStatus" class="form-label">Status</label>
                        <v-select id="visitorChartStatus" class="form-control" placeholder="Select Status"
                            :options="list_status" label="label" :reduce="option => option.value" v-model="status"
                            :clearable="false" @option:selected="on_status_select"></v-select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="visitorChartExhibitions" class="form-label">Exhibitions</label>
                        <v-select id="visitorChartExhibitions" class="form-control" placeholder="All Exhibitions"
                            :options="list_exhibitions" label="label" :reduce="option => option.value"
                            v-model="exhibition_ids" multiple :close-on-select="false"></v-select>
                        <small class="text-muted">Leave empty to include all exhibitions.</small>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="visitorChartDateRange" class="form-label">Registration Date</label>
                        <VueDatePicker id="visitorChartDateRange" v-model="date_range" :range="{ partialRange: false }"
                            model-type="yyyy-MM-dd" format="yyyy-MM-dd" :enable-time-picker="false" :dark="is_dark"
                            auto-apply clearable placeholder="Select date range">
                        </VueDatePicker>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" @click="reset_filters">
                    <i class="fa fa-refresh"></i>
                    Reset Filter
                </button>
                <button type="button" class="btn btn-primary" @click="apply_filters">
                    <i class="fa fa-filter"></i>
                    Apply Filter
                </button>
            </div>
        </div>
        <div class="card-body border-top">
            <apex-chart type="bar" :height="chart_height" :options="target_by_exhibitor.chartOptions"
                :series="target_by_exhibitor.series"></apex-chart>
        </div>
        <div class="card-body border-top">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Visitor List</h5>
                    <p class="text-muted mb-0">Visitor data follows the selected chart filters.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered w-100" id="tableVisitorChart" v-if="!loading">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Country</th>
                            <th>Email</th>
                            <th>Exhibition</th>
                            <th>Sub Exhibition</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <Teleport to="body">
        <div class="modal fade" id="modalExportVisitor" tabindex="-1" aria-labelledby="modalExportVisitorLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="modalExportVisitorLabel">Export Visitor Data</h5>
                            <p class="text-muted mb-0">Select the fields to include in the export file.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            :disabled="export_submitting"></button>
                    </div>
                    <div class="modal-body">
                        <div class="border rounded p-3 mb-4">
                            <h6 class="mb-3">Active Filters</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Status</small>
                                    <strong>{{ applied_status_label }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Exhibitions</small>
                                    <strong>{{ applied_exhibitions_label }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Registration Date</small>
                                    <strong>{{ applied_date_label }}</strong>
                                </div>
                            </div>
                        </div>
                        <div v-if="export_fields_loading" class="text-center py-5" role="status">
                            <span class="spinner-border text-primary" aria-hidden="true"></span>
                            <span class="visually-hidden">Loading export fields...</span>
                        </div>
                        <template v-else>
                            <div v-if="export_fields.length" class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                <div class="btn-group" role="group" aria-label="Export field selection">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        :disabled="all_export_fields_selected" @click="select_all_export_fields">
                                        Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        :disabled="!selected_export_fields.length" @click="clear_all_export_fields">
                                        Clear All
                                    </button>
                                </div>
                                <span class="text-muted">
                                    {{ selected_export_fields.length }} of {{ export_fields.length }} selected
                                </span>
                            </div>
                            <div v-if="export_fields.length" class="row g-2">
                                <div class="col-md-6" v-for="(field, index) in export_fields" :key="field.value">
                                    <div class="form-check border rounded p-3 ps-5 h-100">
                                        <input class="form-check-input" type="checkbox"
                                            :id="'exportVisitorField' + index" :value="field.value"
                                            v-model="selected_export_fields">
                                        <label class="form-check-label" :for="'exportVisitorField' + index">
                                            {{ field.label }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="empty-state">
                                <i class="fa fa-file-text-o fa-2x mb-3" aria-hidden="true"></i>
                                <p class="mb-0">No export fields are available.</p>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            :disabled="export_submitting">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary"
                            :disabled="export_fields_loading || export_submitting || !selected_export_fields.length"
                            @click="request_export">
                            <span v-if="export_submitting" class="spinner-border spinner-border-sm me-2"
                                aria-hidden="true"></span>
                            <i v-else class="fa fa-download me-1"></i>
                            Queue Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import ApexChart from 'vue3-apexcharts'

export default {
    components: {
        apexChart: ApexChart
    },
    data() {
        return {
            loading: true,
            loading_requests: 0,
            is_destroyed: false,
            theme_observer: null,
            is_dark: document.documentElement.getAttribute('data-bs-theme') === 'dark',
            table_visitor: null,
            chart_request_id: 0,
            list_exhibitions: [],
            exhibition_ids: [],
            date_range: null,
            status: "1",
            applied_filters: {
                status: "1",
                exhibition_ids: [],
                exhibition_labels: [],
                start_date: null,
                end_date: null,
            },
            list_status: [
                {
                    label: "All",
                    value: "all"
                },
                {
                    label: "Enable",
                    value: "1"
                },
                {
                    label: "Disable",
                    value: "0"
                },
            ],
            chart_height: 380,
            target_by_exhibitor: {
                series: [],
                chartOptions: {
                    chart: {
                        id: 'target-visitor-by-exhibitor',
                        type: 'bar',
                        toolbar: {
                            tools: {
                                download: false
                            }
                        }
                    },
                    title: {
                        text: 'Target Visitor by Exhibitor',
                        align: 'left',
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 5,
                            borderRadiusApplication: 'end',
                            barHeight: '70%',
                        },
                    },
                    colors: ['#4f46e5', '#16a34a'],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['transparent'],
                    },
                    xaxis: {
                        categories: [],
                        title: {
                            text: 'Visitors',
                        },
                        min: 0,
                    },
                    yaxis: {
                        labels: {
                            maxWidth: 240,
                        },
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'right',
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (val) {
                                return val + ' visitors';
                            },
                        },
                    },
                    grid: {
                        borderColor: '#e2e8f0',
                    },
                    noData: {
                        text: 'No visitor data found for the selected filters.',
                    },
                },
            },
            export_fields: [],
            selected_export_fields: [],
            export_fields_loading: false,
            export_submitting: false,
        }
    },
    computed: {
        all_export_fields_selected() {
            return this.export_fields.length > 0
                && this.selected_export_fields.length === this.export_fields.length;
        },
        applied_status_label() {
            const status = this.list_status.find(item => item.value === this.applied_filters.status);
            return status ? status.label : 'All';
        },
        applied_exhibitions_label() {
            return this.applied_filters.exhibition_labels.length
                ? this.applied_filters.exhibition_labels.join(', ')
                : 'All Exhibitions';
        },
        applied_date_label() {
            if (!this.applied_filters.start_date || !this.applied_filters.end_date) {
                return 'All Registration Dates';
            }

            return this.applied_filters.start_date + ' to ' + this.applied_filters.end_date;
        }
    },
    methods: {
        show_loader() {
            this.loading_requests += 1;
            this.globalLoader.show = true;
        },
        hide_loader() {
            this.loading_requests = Math.max(0, this.loading_requests - 1);
            if (this.loading_requests === 0) {
                this.globalLoader.show = false;
            }
        },
        get_selected_filter_payload() {
            const date_range = Array.isArray(this.date_range) ? this.date_range : [];
            const exhibition_ids = Array.isArray(this.exhibition_ids) ? this.exhibition_ids : [];

            return {
                status: this.status,
                exhibition_ids: exhibition_ids.map(item => Number(item)).filter(item => Number.isFinite(item)),
                start_date: date_range[0] || null,
                end_date: date_range[1] || null,
            };
        },
        get_filter_payload() {
            return {
                status: this.applied_filters.status,
                exhibition_ids: [...this.applied_filters.exhibition_ids],
                start_date: this.applied_filters.start_date,
                end_date: this.applied_filters.end_date,
            };
        },
        set_applied_filters() {
            const selected_filters = this.get_selected_filter_payload();
            const exhibition_labels = this.list_exhibitions
                .filter(item => selected_filters.exhibition_ids.includes(Number(item.value)))
                .map(item => item.label);

            this.applied_filters = {
                ...selected_filters,
                exhibition_labels: exhibition_labels,
            };
        },
        on_status_select() {
            this.exhibition_ids = [];
            this.get_exhibitions();
        },
        apply_filters() {
            if (this.is_destroyed) {
                return;
            }

            const selected_filters = this.get_selected_filter_payload();
            if ((selected_filters.start_date && !selected_filters.end_date)
                || (!selected_filters.start_date && selected_filters.end_date)) {
                swalNotif.info("Please select a complete registration date range!");
                return;
            }

            this.set_applied_filters();
            this.get_chart();
            this.refresh_table();
        },
        reset_filters() {
            this.status = "1";
            this.exhibition_ids = [];
            this.date_range = null;
            this.set_applied_filters();
            this.get_exhibitions();
            this.get_chart();
            this.refresh_table();
        },
        clear_chart() {
            this.target_by_exhibitor.series = [];
            this.target_by_exhibitor.chartOptions = {
                ...this.target_by_exhibitor.chartOptions,
                xaxis: {
                    ...this.target_by_exhibitor.chartOptions.xaxis,
                    categories: [],
                },
            };
        },
        get_exhibitions() {
            const vm = this;
            this.list_exhibitions = [];
            this.show_loader();

            axios.post("/api/v1/web/exhibition/all/get", {
                status: vm.status
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (vm.is_destroyed) {
                    return;
                }

                if (res.data.status == 1) {
                    vm.list_exhibitions = res.data.data.map(item => ({
                        label: item.name,
                        value: item.id
                    }));
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                if (!vm.is_destroyed) {
                    swalNotif.error("Error Get Exhibitions!");
                }
            }).finally(function () {
                vm.hide_loader();
            })
        },
        get_chart() {
            const vm = this;
            const request_id = ++this.chart_request_id;
            this.show_loader();

            axios.post("/api/v1/web/dashboard/visitor/chart/get", this.get_filter_payload(), {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (vm.is_destroyed || request_id !== vm.chart_request_id) {
                    return;
                }

                if (res.data.status == 1) {
                    const chart_data = Array.isArray(res.data.data) ? res.data.data : [];
                    vm.chart_height = Math.max(380, chart_data.length * 72);
                    vm.target_by_exhibitor.series = chart_data.length ? [
                        {
                            name: 'Total Registration',
                            data: chart_data.map(item => Number(item.total_registration) || 0),
                        },
                        {
                            name: 'Total Printed',
                            data: chart_data.map(item => Number(item.total_printed) || 0),
                        }
                    ] : [];
                    vm.target_by_exhibitor.chartOptions = {
                        ...vm.target_by_exhibitor.chartOptions,
                        xaxis: {
                            ...vm.target_by_exhibitor.chartOptions.xaxis,
                            categories: chart_data.map(item => item.exhibition_name),
                        },
                    };
                } else {
                    vm.clear_chart();
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                if (!vm.is_destroyed && request_id === vm.chart_request_id) {
                    vm.clear_chart();
                    swalNotif.error("Error Get Visitor Chart!");
                }
            }).finally(function () {
                vm.hide_loader();
            });
        },
        get_visitor() {
            if (typeof $ === 'undefined' || !$.fn.DataTable) {
                swalNotif.error("Visitor table is not available!");
                return;
            }

            this.destroy_table();

            const vm = this;
            const text_renderer = $.fn.dataTable.render.text();

            this.table_visitor = $("#tableVisitorChart").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/v1/web/dashboard/visitor/chart/list/get",
                        headers: {
                            token: localStorage.getItem('token')
                        },
                        data: function (d) {
                            Object.assign(d, vm.get_filter_payload());
                        },
                        error: function () {
                            if (!vm.is_destroyed) {
                                swalNotif.error("Error Get Visitors!");
                            }
                        }
                    },
                    fixedHeader: {
                        header: true,
                    },
                    scrollY: '60vh',
                    scrollCollapse: true,
                    scrollX: true,
                    pageLength: 25,
                    order: [[7, 'desc']],
                    "columnDefs": [
                        {
                            "width": "2%",
                            "targets": 0
                        }
                    ],
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'visitor_name',
                            name: 'visitor_name',
                            render: text_renderer
                        },
                        {
                            data: 'company',
                            name: 'company',
                            render: text_renderer
                        },
                        {
                            data: 'country',
                            name: 'country',
                            render: text_renderer
                        },
                        {
                            data: 'email',
                            name: 'email',
                            render: text_renderer
                        },
                        {
                            data: 'exhibition_name',
                            name: 'exhibition_name',
                            render: text_renderer
                        },
                        {
                            data: 'sub_exhibition_name',
                            name: 'sub_exhibition_name',
                            render: text_renderer
                        },
                        {
                            data: 'registration_date',
                            name: 'registration_date',
                            render: text_renderer
                        }
                    ]
                }
            );
        },
        refresh_table() {
            if (typeof $ === 'undefined' || !$.fn.dataTable
                || !$.fn.dataTable.isDataTable("#tableVisitorChart") || !this.table_visitor) {
                return;
            }

            this.table_visitor.ajax.reload(null, true);
        },
        destroy_table() {
            if (typeof $ !== 'undefined' && $.fn.dataTable
                && $.fn.dataTable.isDataTable("#tableVisitorChart")) {
                $("#tableVisitorChart").DataTable().destroy();
            }

            this.table_visitor = null;
        },
        get_export_fields() {
            const vm = this;
            this.export_fields_loading = true;
            this.export_fields = [];
            this.selected_export_fields = [];

            axios.get("/api/v1/web/dashboard/visitor/export/fields/get", {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (vm.is_destroyed) {
                    return;
                }

                if (res.data.status == 1) {
                    const fields = Array.isArray(res.data.data) ? res.data.data : [];
                    const default_fields = [
                        'name',
                        'company',
                        'country',
                        'email',
                        'register_date',
                        'is_printed',
                    ];
                    vm.export_fields = fields.map(item => ({
                        value: item.value,
                        label: item.label
                    }));
                    vm.selected_export_fields = vm.export_fields
                        .filter(item => default_fields.includes(item.value))
                        .map(item => item.value);
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                if (!vm.is_destroyed) {
                    swalNotif.error("Error Get Export Fields!");
                }
            }).finally(function () {
                vm.export_fields_loading = false;
            });
        },
        open_export_modal() {
            const modal_element = document.getElementById("modalExportVisitor");
            window.bootstrap.Modal.getOrCreateInstance(modal_element, {
                backdrop: 'static',
                keyboard: false
            }).show();
            this.get_export_fields();
        },
        select_all_export_fields() {
            this.selected_export_fields = this.export_fields.map(item => item.value);
        },
        clear_all_export_fields() {
            this.selected_export_fields = [];
        },
        request_export() {
            if (!this.selected_export_fields.length) {
                swalNotif.info("Please select at least one export field!");
                return;
            }

            const vm = this;
            this.export_submitting = true;

            axios.post("/api/v1/web/dashboard/visitor/export/request", {
                ...this.get_filter_payload(),
                fields: this.selected_export_fields,
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (vm.is_destroyed) {
                    return;
                }

                if (res.data.status == 1) {
                    const modal_element = document.getElementById("modalExportVisitor");
                    const modal = window.bootstrap.Modal.getInstance(modal_element);
                    if (modal) {
                        modal.hide();
                    }
                    vm.selected_export_fields = [];
                    swalNotif.success(res.data.message);
                } else {
                    swalNotif.error(res.data.message);
                }
            }).catch(res => {
                if (!vm.is_destroyed) {
                    swalNotif.error("Error Request Export!");
                }
            }).finally(function () {
                if (!vm.is_destroyed) {
                    vm.export_submitting = false;
                }
            });
        },
        go_export_history() {
            this.$router.push("/user/dashboard/visitor/report/");
        },
        sync_theme() {
            const selected_theme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            const root_style = window.getComputedStyle(document.documentElement);
            const text_color = root_style.getPropertyValue('--admin-text').trim()
                || (selected_theme === 'dark' ? '#e5e7eb' : '#172033');
            const border_color = root_style.getPropertyValue('--admin-border').trim()
                || (selected_theme === 'dark' ? '#334155' : '#e2e8f0');

            this.is_dark = selected_theme === 'dark';
            this.target_by_exhibitor.chartOptions = {
                ...this.target_by_exhibitor.chartOptions,
                chart: {
                    ...this.target_by_exhibitor.chartOptions.chart,
                    foreColor: text_color,
                },
                theme: {
                    mode: selected_theme,
                },
                tooltip: {
                    ...this.target_by_exhibitor.chartOptions.tooltip,
                    theme: selected_theme,
                },
                grid: {
                    ...this.target_by_exhibitor.chartOptions.grid,
                    borderColor: border_color,
                },
            };
        },
        observe_theme() {
            this.sync_theme();
            this.theme_observer = new MutationObserver(() => {
                this.sync_theme();
            });
            this.theme_observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });
        },
        dispose_export_modal() {
            const modal_element = document.getElementById("modalExportVisitor");
            if (!modal_element || !window.bootstrap) {
                return;
            }

            const modal = window.bootstrap.Modal.getInstance(modal_element);
            if (modal) {
                modal.hide();
                modal.dispose();
            }
        }
    },
    mounted() {
        const vm = this;
        this.loading = false;
        this.observe_theme();

        this.$nextTick(() => {
            vm.get_visitor();
            vm.get_chart();
            vm.get_exhibitions();
        });
    },
    beforeUnmount() {
        this.is_destroyed = true;
        this.chart_request_id += 1;
        this.destroy_table();
        this.dispose_export_modal();

        if (this.theme_observer) {
            this.theme_observer.disconnect();
            this.theme_observer = null;
        }

        this.loading_requests = 0;
        this.globalLoader.show = false;
    }
}
</script>
