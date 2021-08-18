@extends('layouts.backend.app')
{{-- @section('page_vendor')
@endsection --}}

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
            <form action="{{ route('admin.vehicles.save') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="defaultInput">Vehicle Type</label>
                        <input id="vehicle_type" class="form-control" type="text" placeholder="Normal Input" name="type" />
                    </div>
                    <div class="form-group">
                        <label for="selectDefault">Icon</label>
                        <select class="form-control mb-1" id="vehicle_icon" name="icon">
                            @foreach (config('constants.vehicle_icons') as $icon)
                            <option >{{ $icon }}</option>
                            @endforeach
                        </select>
                        <span class="cbs-vehicle-icon cbs-vehicle-icon-suv"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="saveVehicle()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page_script')
<script>
    $(function () {
        var dt_responsive_table = $('.dt-responsive');
        if (dt_responsive_table.length) {
            var dt_responsive = dt_responsive_table.DataTable({
              ajax: assetPath + 'data/table-datatable.json',
              columns: [
                { data: 'responsive_id' },
                { data: 'full_name' },
                { data: 'email' },
                { data: 'post' },
                { data: 'city' },
                { data: 'start_date' },
                { data: 'salary' },
                { data: 'age' },
                { data: 'experience' },
                { data: 'status' }
              ],
              columnDefs: [
                {
                  className: 'control',
                  orderable: false,
                  targets: 0
                },
                {
                  // Label
                  targets: -1,
                  render: function (data, type, full, meta) {
                    var $status_number = full['status'];
                    var $status = {
                      1: { title: 'Current', class: 'badge-light-primary' },
                      2: { title: 'Professional', class: ' badge-light-success' },
                      3: { title: 'Rejected', class: ' badge-light-danger' },
                      4: { title: 'Resigned', class: ' badge-light-warning' },
                      5: { title: 'Applied', class: ' badge-light-info' }
                    };
                    if (typeof $status[$status_number] === 'undefined') {
                      return data;
                    }
                    return (
                      '<span class="badge badge-pill ' +
                      $status[$status_number].class +
                      '">' +
                      $status[$status_number].title +
                      '</span>'
                    );
                  }
                }
              ],
              dom:
                '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
              responsive: {
                details: {
                  display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                      var data = row.data();
                      return 'Details of ' + data['full_name'];
                    }
                  }),
                  type: 'column',
                  renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                  })
                }
              },
              language: {
                paginate: {
                  // remove previous & next text from pagination
                  previous: '&nbsp;',
                  next: '&nbsp;'
                }
              }
            });
        }
    })
</script>
@endsection