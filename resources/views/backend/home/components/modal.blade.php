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
                <input type="hidden" name="location_id" value="{{ $location_id }}">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="start_time">Start Time</label>
                            <input type="text" id="start_time" class="form-control flatpickr-date-time" placeholder="YYYY-MM-DD HH:MM" value='@if ($order != null)
                                {{ $order->date . " " . $order->time }}
                            @endif' name="datetime" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Duration</label>
                            <select class="form-control mb-1" name="duration">
                                <option value="30" @if ($order != null && $order->duration == 30)
                                    selected
                                @endif>30</option>
                                <option value="60" @if ($order != null && $order->duration == 60)
                                    selected
                                @endif>60</option>
                                <option value="90" @if ($order != null && $order->duration == 90)
                                    selected
                                @endif>90</option>
                                <option value="120" @if ($order != null && $order->duration == 120)
                                    selected
                                @endif>120</option>
                                <option value="150" @if ($order != null && $order->duration == 150)
                                    selected
                                @endif>150</option>
                                <option value="180" @if ($order != null && $order->duration == 180)
                                    selected
                                @endif>180</option>
                                <option value="210" @if ($order != null && $order->duration == 210)
                                    selected
                                @endif>210</option>
                                <option value="240" @if ($order != null && $order->duration == 240)
                                    selected
                                @endif>240</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selectDefault">Vechiles</label>
                                <select class="form-control mb-1" id="icon" name="vehicle_id">
                                    @foreach ($location_vehicles as $vehicle)
                                        <option value={{ $vehicle->id }} @if ($order != null && $order->vehicle_id == $vehicle->id)
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
                                        <option value={{ $service->id }} @if ($order != null && in_array($service->id, explode(",", $order->service_id)))
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
                                        <option value={{ $pesubox->id }} @if ($order != null && $order->pesubox_id == $pesubox->id)
                                            selected
                                        @endif >{{ $pesubox->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="@if ($order != null) {{ $order->first_name }} @endif " />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="@if ($order != null) {{ $order->last_name }} @endif" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Company Name</label>
                            <input type="text" class="form-control" name="company_name" value="@if ($order != null) {{ $order->company_name }} @endif" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Email</label>
                            <input type="text" class="form-control" name="email" value="@if ($order != null) {{ $order->email }} @endif" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Phone</label>
                            <input type="text" class="form-control" name="phone" value="@if ($order != null) {{ $order->phone }} @endif" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Vehicle Make model</label>
                            <input type="text" class="form-control" name="model" value="@if ($order != null) {{ $order->model }} @endif" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="start_time">Message</label>
                            <textarea type="text" class="form-control" name="message">@if ($order != null) {{ $order->message }} @endif</textarea>
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
