@extends('layouts.backend.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Vehicles</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item active">vehicles
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- Responsive Datatable -->
        <section id="responsive-datatable" class="col-12">
            <div class="row" class="col-12">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Responsive Datatable</h4>
                            <Button class="btn btn-primary waves-effect waves-float waves-light" data-toggle="modal" data-target="#defaultSize">Add New Vehicle</Button>
                        </div>
                        <div class="card-datatable">
                            <table class="dt-responsive table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Post</th>
                                        <th>City</th>
                                        <th>Date</th>
                                        <th>Salary</th>
                                        <th>Age</th>
                                        <th>Experience</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Post</th>
                                        <th>City</th>
                                        <th>Date</th>
                                        <th>Salary</th>
                                        <th>Age</th>
                                        <th>Experience</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Responsive Datatable -->
    </div>
</div>
<div class="modal fade text-left" id="defaultSize" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Add New Vehicle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="defaultInput">Vehicle Type</label>
                    <input id="defaultInput" class="form-control" type="text" placeholder="Normal Input" />
                </div>
                <div class="form-group">
                    <label for="selectDefault">Icon</label>
                    <select class="form-control mb-1" id="selectDefault">
                        @foreach (config('constants.vehicle_icons') as $icon)
                        <option >{{ $icon }}</option>
                        @endforeach
                    </select>
                    <span class="cbs-vehicle-icon cbs-vehicle-icon-suv"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveVehicle()">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection