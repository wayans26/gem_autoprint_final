<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="PT Global Expo Management" />
    <title>PT Global Expo Management</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">

    <script src="{{ asset('rukada/js/jquery.min.js') }}"></script>
    <script src="{{ asset('rukada/js/popper.min.js') }}"></script>
    <script src="{{ asset('rukada/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/bootstrap-datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/bootstrap-datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/alerts-boxes/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/alerts-boxes/js/sweet-alert-script.js') }}"></script>
    <script src="{{ asset('rukada/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/material-datepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/material-datepicker/js/bootstrap-material-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('rukada/plugins/material-datepicker/js/ja.js') }}"></script>

    {{-- G Tag --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8RZR4YTC1K"></script>

    {{-- Css --}}
    <link href="{{ asset('rukada/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/css/sidebar-menu.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/css/app-style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('rukada/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('rukada/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css">
    <!--material datepicker css-->
    <link rel="stylesheet"
        href="{{ asset('rukada/plugins/material-datepicker/css/bootstrap-material-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!--Bootstrap Datepicker-->
    <link href="{{ asset('rukada/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">

    <!--Switchery-->
    <link href="{{ asset('rukada/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rukada/plugins/bootstrap-switch/bootstrap-switch.min.css') }}" rel="stylesheet">

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .disabledbutton {
            pointer-events: none;
            opacity: 0.4;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .separator {
            display: flex;
            align-items: center;
            text-align: center;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #000;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }
    </style>

    <style lang="scss">
        .v-select,
        .vs__dropdown-toggle {
            height: auto !important;
        }
    </style>

</head>

<body>
    <div id="app">
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>
    </div>
    @vite('resources/js/Form/Master/index_reguler.js')
    <script src="{{ asset('rukada/plugins/simplebar/js/simplebar.js') }}"></script>
    <script src="{{ asset('rukada/js/waves.js') }}"></script>
    <script src="{{ asset('rukada/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('rukada/js/app-script.js') }}"></script>
    <!--Switchery Js-->
    <script src="{{ asset('rukada/plugins/switchery/js/switchery.min.js') }}"></script>

    <!--Bootstrap Switch Buttons-->
    <script src="{{ asset('rukada/plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
</body>

</html>
