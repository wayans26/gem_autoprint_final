<template>
    <div class="card">
        <div class="card-header">
            <bread-crumb></bread-crumb>
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="mb-0">Visitor Report History</h5>
                <div class="d-flex flex-wrap gap-2">
                    <router-link to="/user/dashboard/visitor/chart/" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </router-link>
                    <button type="button" class="btn btn-outline-primary btn-sm" @click="refresh_table">
                        <i class="fa fa-refresh"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert">
                Visitor reports are generated in the background. This list refreshes automatically while a report is
                being processed.
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle w-100" id="tableVisitorReport" v-if="!loading">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Selected Fields</th>
                            <th>Filters</th>
                            <th>Status</th>
                            <th>Completed / Execution</th>
                            <th>Requested At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            table_report: null,
            refresh_timer: null,
        }
    },
    methods: {
        get_report() {
            const vm = this;
            this.table_report = $("#tableVisitorReport").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/v1/web/report/list/get",
                        headers: {
                            token: localStorage.getItem('token')
                        },
                    },
                    fixedHeader: {
                        header: true,
                    },
                    scrollY: '60vh',
                    scrollCollapse: true,
                    order: [[6, 'desc']],
                    scrollX: true,
                    pageLength: 25,
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    }, {
                        "width": "8%",
                        "targets": 7
                    }],
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'file_name',
                        name: 'file_name',
                        render: $.fn.dataTable.render.text()
                    }, {
                        data: 'selected_fields',
                        name: 'selected_fields',
                        orderable: false,
                        searchable: false,
                        render: function (data, type) {
                            const selected_fields = vm.get_selected_fields(data);
                            if (type !== 'display') {
                                return selected_fields.join(', ');
                            }
                            if (selected_fields.length === 0) {
                                return '-';
                            }

                            return '<div class="d-flex flex-wrap gap-1">' + selected_fields.map(function (field) {
                                return '<span class="badge bg-secondary">' + vm.escape_html(field) + '</span>';
                            }).join('') + '</div>';
                        }
                    }, {
                        data: 'filters',
                        name: 'filters',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return vm.escape_html(vm.get_filter_summary(data));
                        }
                    }, {
                        data: 'status_name',
                        name: 'status',
                        render: function (data, type, row) {
                            if (type !== 'display') {
                                return data;
                            }

                            let badge_class = 'bg-warning text-dark';
                            if (row.status === 1) {
                                badge_class = 'bg-success';
                            } else if (row.status === 2) {
                                badge_class = 'bg-danger';
                            }

                            return '<span class="badge ' + badge_class + '">' + vm.escape_html(data) + '</span>';
                        }
                    }, {
                        data: 'execute_time',
                        name: 'execute_time',
                        render: function (data, type, row) {
                            const execute_time = data === null ? '-' : String(data) + ' seconds';
                            const completed_at = row.completed_at ? row.completed_at : '-';

                            if (type !== 'display') {
                                return execute_time + ' ' + completed_at;
                            }

                            return '<div>' + vm.escape_html(execute_time) + '</div>' +
                                '<small class="text-muted">Completed: ' + vm.escape_html(completed_at) + '</small>';
                        }
                    }, {
                        data: 'created_at',
                        name: 'created_at',
                        render: $.fn.dataTable.render.text()
                    }, {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            if (type !== 'display' || !row.can_download) {
                                console.log(type);
                                console.log(row.can_download);
                                return type;
                                return 'AAAAAAAAA';
                            }


                            return '<a class="btn btn-primary btn-sm" href="/report/download/' + Number(data) +
                                '"><i class="fa fa-download"></i> Download</a>';
                        }
                    }]
                }
            );
        },
        get_filter_summary(filters) {
            let selected_filters = filters;

            if (typeof selected_filters === 'string') {
                try {
                    selected_filters = JSON.parse(selected_filters);
                } catch (error) {
                    return '-';
                }
            }
            if (!selected_filters || typeof selected_filters !== 'object') {
                return '-';
            }

            const status_label = selected_filters.status === 'all' ? 'All status' :
                (String(selected_filters.status) === '1' ? 'Enabled' : 'Disabled');
            const exhibition_count = Array.isArray(selected_filters.exhibition_ids) ?
                selected_filters.exhibition_ids.length : 0;
            const exhibition_label = exhibition_count > 0 ? exhibition_count + ' exhibition(s)' : 'All exhibitions';
            const date_label = selected_filters.start_date && selected_filters.end_date ?
                selected_filters.start_date + ' - ' + selected_filters.end_date : 'All dates';

            return status_label + ' | ' + exhibition_label + ' | ' + date_label;
        },
        get_selected_fields(fields) {
            let selected_fields = fields;

            if (typeof selected_fields === 'string') {
                try {
                    selected_fields = JSON.parse(selected_fields);
                } catch (error) {
                    return [];
                }
            }
            if (!Array.isArray(selected_fields)) {
                return [];
            }

            return selected_fields.map(function (field) {
                return String(field).split('_').map(function (word) {
                    if (word.toLowerCase() === 'id') {
                        return 'ID';
                    }

                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
            });
        },
        escape_html(value) {
            const element = document.createElement('div');
            element.textContent = value;
            return element.innerHTML;
        },
        refresh_table() {
            if (this.table_report) {
                this.table_report.ajax.reload(null, false);
            }
        }
    },
    mounted() {
        const vm = this;
        this.loading = false;
        setTimeout(() => {
            vm.get_report();
        }, 1);

        this.refresh_timer = window.setInterval(function () {
            vm.refresh_table();
        }, 10000);
    },
    beforeUnmount() {
        if (this.refresh_timer) {
            window.clearInterval(this.refresh_timer);
        }
        if (this.table_report) {
            this.table_report.destroy();
        }
    }
}
</script>
