<!-- Responsive Datatable -->
<section id="vehicleType-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Teenused</h4>
                    <Button class="btn btn-primary waves-effect waves-float waves-light" id="add_new_service" data-toggle="modal" data-target="#service_modal">Lisa teenus</Button>
                </div>
                <div class="card-datatable col-12">
                    <table class="table datatables-ajax" id="service_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nimetus</th>
                                <th>Kirjeldus</th>
                                <th>Kestvus</th>
                                <th>Hind</th>
                                <th>Tegevused</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Responsive Datatable -->
<script>
    $(function() {
        $("#add_new_service").click(function() {
            $("#service_modal #id").val(0);
            $("#service_modal #name").val("");
            $("#service_modal #description").val("");
            $("#service_modal #duration").val("");
            $("#service_modal #price").val("");
        })
    })
</script>