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
                            <label for="start_time">Nimi</label>
                            <input type="text" class="form-control" name="driver" value="@if ($order != null) {{ $order->driver }} @endif" />
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
                            <label for="start_time">Number</label>
                            <input type="text" class="form-control" name="number" value="@if ($order != null) {{ $order->number }} @endif" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Vehicle Make</label>
                            <select class="form-control mb-1" id="mark" name="mark_id">
                                @foreach ($location_marks as $mark)
                                    <option value={{ $mark->id }} @if ($order != null && $order->mark_id == $mark->id)
                                        selected
                                    @endif >{{ $mark->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_time">Vehicle Make model</label>
                            <select class="form-control mb-1" id="model" name="model_id">
                                @foreach ($location_mark_models as $model)
                                    <option value={{ $model->id }} @if ($order != null && $order->model_id == $model->id)
                                        selected
                                    @endif >{{ $model->model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="start_time">Message</label>
                            <textarea type="text" class="form-control" name="summary">@if ($order != null) {{ $order->summary }} @endif</textarea>
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
            if (formdata.get("service_id")) {

            }
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

        $("#mark").change(function() {
            $.ajax({
                type: "post",
                url: appUrl + '/admin/getModel',
                data: {mark_id: $(this).val()},
                success: (res) => {
                    console.log(res)
                    $("#model").html(res);
                },
                error: (err) => {
                    console.log(err);
                }
            });
        })
    })
</script>
