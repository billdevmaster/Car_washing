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
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>