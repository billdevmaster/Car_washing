(function (window, undefined) {
  'use strict';

  const router = {
    getVehicle: "/admin/vehicles/get_list",
  }

  var vehicle_type_table = $('#vehicle_table');
  if (vehicle_type_table.length) {
    vehicle_type_table.DataTable({
      processing: true,
      serverSide: true,
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search..',
        paginate: {
            // remove previous & next text from pagination
            previous: '&nbsp;',
            next: '&nbsp;'
        }
      },
      order:[3,'desc'],
      ajax: router.getVehicle,
      "lengthMenu": [[10, 50, 200, 1000000000], [10, 50, 200, "All"]],
      "pageLength": 10,
      columns: [
        { data: 'id', name: 'id', "visible": false },
        { data: 'name', name: 'email' },
        { data: 'icon', name: 'icon' },
      ],
      columnDefs: [
        {
          className: 'control',
          orderable: false,
          responsivePriority: 2,
          targets: 0
        },
        {
          // Actions
          targets: 5,
          title: 'status',
          orderable: false,
          render: function (data, type, full, meta) {
              return (
                  "<a>"+full['status']+"</a><br>\
                   <a>"+full['view']+"</a>"
              );
          },
        }
      ],
    });
  }

})(window);
