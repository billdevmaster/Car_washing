@extends('layouts.backend.app')

@section('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        @include('backend.clients.components.table');
    </div>
</div>
@include('backend.services.components.modal')
</div>
@endsection

@section('page_vendor_js')
<script src="{{asset('assets/backend/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/backend/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/backend/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/backend/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
<script src="{{asset('assets/backend/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/backend/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
@endsection