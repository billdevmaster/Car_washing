    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Add New Vehicle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="order-form">
                @csrf
                <input type="hidden" name="id" value={{ $id }}>
                <input type="hidden" name="location_id" value={{ $order->location_id }}>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="start_time">Start Time</label>
                            <input type="text" id="start_time" class="form-control flatpickr-date-time" placeholder="YYYY-MM-DD HH:MM" value="{{ $order->date . " " . $order->time }}" name="datetime" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Duration</label>
                            <input type="text" class="form-control" value="{{ $order->duration }}" name="duration"/>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selectDefault">Vechiles</label>
                                <select class="form-control mb-1" id="icon" name="vehicle_id">
                                    @foreach ($location_vehicles as $vehicle)
                                        <option value={{ $vehicle->id }} @if ($order->vehicle_id == $vehicle->id)
                                            selected
                                        @endif >{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Services</label>
                            <div class="form-group">
                                <select class="select2 form-control" multiple="multiple" id="default-select-multi" name="service_id[]">
                                    @foreach ($location_services as $service)
                                        <option value={{ $service->id }} @if (in_array($service->id, explode(",", $order->service_id)))
                                            selected
                                        @endif >{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selectDefault">Pesubox</label>
                                <select class="form-control mb-1" id="icon" name="pesubox_id">
                                    @foreach ($location_pesuboxs as $pesubox)
                                        <option value={{ $pesubox->id }} @if ($order->pesubox_id == $pesubox->id)
                                            selected
                                        @endif >{{ $pesubox->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="{{ $order->first_name }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $order->last_name }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Company Name</label>
                            <input type="text" class="form-control" name="company_name" value="{{ $order->company_name }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $order->email }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ $order->phone }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Vehicle Make model</label>
                            <input type="text" class="form-control" name="model" value="{{ $order->model }}" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="start_time">Message</label>
                            <textarea type="text" class="form-control" name="message">{{ $order->message }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<script>
    $(function() {
        $("#start_time").flatpickr({
            enableTime: true
        });

        $('.select2').select2();

        $(".order-form #submit").click(function() {
            var formdata = new FormData($(".order-form")[0]);
            console.log(formdata.get("service_id"));
            $.ajax({
                type: "post",
                url: appUrl + '/admin/updateOrder',
                data: formdata,
                dataType:"JSON",
                processData: false,
                contentType: false,
                cache: false,
                success: (res) => {
                    if (res.success) {
                        window.location.reload();
                    }
                },
                error: (err) => {
                    console.log(err);
                }
            });
        })
    })
</script>
