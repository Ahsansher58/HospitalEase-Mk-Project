/**
 * DataTables Advanced (jquery)
 */

'use strict';

$(function () {
  var dt_ajax_table = $('.datatables-ajax'),
    dt_ajax_hospitals = $('.datatables-hospitals'),
    dt_ajax_table2 = $('.datatables-ajax2'),
    dt_ajax_table3 = $('.datatables-ajax3'),
    dt_ajax_table4 = $('.datatables-ajax4'),
    dt_ajax_table5 = $('.datatables-ajax5'),
    dt_ajax_table6 = $('.datatables-ajax6'),
    dt_ajax_table7 = $('.datatables-ajax7'),
    dt_ajax_table8 = $('.datatables-ajax8'),
    dt_ajax_table9 = $('.datatables-ajax9'),
    dt_ajax_appointments = $('.datatables-appointments'),
    dt_filter_table = $('.dt-column-search'),
    dt_adv_filter_table = $('.dt-advanced-search'),
    dt_responsive_table = $('.dt-responsive'),
    startDateEle = $('.start_date'),
    endDateEle = $('.end_date');

  // Advanced Search Functions Starts
  // --------------------------------------------------------------------

  // Datepicker for advanced filter
  var rangePickr = $('.flatpickr-range'),
    dateFormat = 'MM/DD/YYYY';

  if (rangePickr.length) {
    rangePickr.flatpickr({
      mode: 'range',
      dateFormat: 'm/d/Y',
      orientation: isRtl ? 'auto right' : 'auto left',
      locale: {
        format: dateFormat
      },
      onClose: function (selectedDates, dateStr, instance) {
        var startDate = '',
          endDate = new Date();
        if (selectedDates[0] != undefined) {
          startDate = moment(selectedDates[0]).format('MM/DD/YYYY');
          startDateEle.val(startDate);
        }
        if (selectedDates[1] != undefined) {
          endDate = moment(selectedDates[1]).format('MM/DD/YYYY');
          endDateEle.val(endDate);
        }
        $(rangePickr).trigger('change').trigger('keyup');
      }
    });
  }

  // Filter column wise function
  function filterColumn(i, val) {
    if (i == 5) {
      var startDate = startDateEle.val(),
        endDate = endDateEle.val();
      if (startDate !== '' && endDate !== '') {
        $.fn.dataTableExt.afnFiltering.length = 0; // Reset datatable filter
        dt_adv_filter_table.dataTable().fnDraw(); // Draw table after filter
        filterByDate(i, startDate, endDate); // We call our filter function
      }
      dt_adv_filter_table.dataTable().fnDraw();
    } else {
      dt_adv_filter_table.DataTable().column(i).search(val, false, true).draw();
    }
  }

  // Advance filter function
  // We pass the column location, the start date, and the end date
  $.fn.dataTableExt.afnFiltering.length = 0;
  var filterByDate = function (column, startDate, endDate) {
    // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
      var rowDate = normalizeDate(aData[column]),
        start = normalizeDate(startDate),
        end = normalizeDate(endDate);

      // If our date from the row is between the start and end
      if (start <= rowDate && rowDate <= end) {
        return true;
      } else if (rowDate >= start && end === '' && start !== '') {
        return true;
      } else if (rowDate <= end && start === '' && end !== '') {
        return true;
      } else {
        return false;
      }
    });
  };

  // converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
  var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    var normalized =
      date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
    return normalized;
  };
  // Advanced Search Functions Ends

  // Ajax Sourced Server-side
  // --------------------------------------------------------------------

  if (dt_ajax_hospitals.length) {
    var dt_ajax = dt_ajax_hospitals.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: manage_hospitals_table_json,
        data: function (d) {
          d.search = $('#customSearchInput').val();
          d.status = $('#select2Status').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    $('#customSearchInput').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax.api().ajax.reload();
      }
    });
    $('#select2Status').on('change', function (e) {
      dt_ajax.api().ajax.reload();
    });
  }

  if (dt_ajax_table.length) {
    var dt_ajax = dt_ajax_table.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      ajax: assetsPath + 'json/ajax.php',
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
  }
  if (dt_ajax_table2.length) {
    var dt_ajax = dt_ajax_table2.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: users_table_json,
        data: function (d) {
          d.city = $('#city').val();
          d.state = $('#state').val();
          d.country = $('#country').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    $('#city').on('change', function (e) {
      dt_ajax.api().ajax.reload();
    });
    $('#state').on('change', function (e) {
      let state = $(this).val();
      if (state) {
        $.ajax({
          url: get_cities,
          method: 'GET',
          data: {
            country: $('#country').val(),
            state: state,
          },
          success: function (cities) {
            let citySelect = $('#city');
            $.each(cities, function (index, city) {
              citySelect.append('<option value="' + city + '">' +
                city + '</option>');
            });
            dt_ajax.api().ajax.reload();
          },
        });
      }
    });
    $('#country').on('change', function (e) {
      let country = $(this).val();
      if (country) {
        $.ajax({
          url: get_states,
          method: 'GET',
          data: {
            country: country
          },
          success: function (states) {
            let stateSelect = $('#state');
            $.each(states, function (index, state) {
              stateSelect.append('<option value="' + state + '">' +
                state + '</option>');
            });
            dt_ajax.api().ajax.reload();
          },
        });
      }

    });
  }
  if (dt_ajax_appointments.length) {
    var dt_ajax_appointments = dt_ajax_appointments.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: appointments_table_json,
        data: function (d) {
          d.search = $('#customSearch').val();
          d.date_range = $('#flatpickr-range').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    $('#customSearch').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax_appointments.api().ajax.reload();
      }
    });

    flatpickr("#flatpickr-range", {
      mode: "range",
      dateFormat: "Y-m-d",
      onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
          console.log("Selected dates: ", selectedDates);
          console.log("Date string: ", dateStr);
          dt_ajax_appointments.api().ajax.reload();
        }
      }
    });
  }
  if (dt_ajax_table3.length) {
    var dt_ajax_reviews = dt_ajax_table3.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: reviews_table_json,
        data: function (d) {
          d.date_range = $('#review-flatpickr-range').val();
          d.search = $('#customSearch').val();
          d.status = $('#customStatus').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    flatpickr("#review-flatpickr-range", {
      mode: "range",
      dateFormat: "Y-m-d",
      onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
          dt_ajax_reviews.api().ajax.reload();
        }
      }
    });
    $('#customSearch').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax_table3.api().ajax.reload();
      }
    });
    $('#customStatus').on('change', function (e) {
      dt_ajax_table3.api().ajax.reload();
    });
  }
  if (dt_ajax_table4.length) {
    var dt_ajax = dt_ajax_table4.dataTable({
      processing: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: manage_business_listing_table_json,
        data: function (d) {
          d.search = $('#customSearchInput').val();
          d.business_category_id = $('#business_category_id').val();
          d.select_city = $('#select_city').val();
          d.select_state = $('#select_state').val();
          d.select_country = $('#select_country').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    $('#customSearchInput').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax.api().ajax.reload();
      }
    });
    $('#business_category_id, #select_city, #select_state, #select_country').on('change', function (e) {
      dt_ajax.api().ajax.reload();
    });
  }
  if (dt_ajax_table5.length) {
    var dt_ajax = dt_ajax_table5.dataTable({
      processing: true,
      serverSide: true,
      searching: false,
      info: false,
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      ajax: {
        url: manage_ads_table_json,
        data: function (d) {
          d.search = $('#customSearchInput').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });

    $('#customSearchInput').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax.api().ajax.reload();
      }
    });
  }
  if (dt_ajax_table6.length) {
    var dt_ajax = dt_ajax_table6.dataTable({
      processing: true,
      info: false,
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      ajax: main_category_table_json,
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
  }
  if (dt_ajax_table7.length) {
    var dt_ajax = dt_ajax_table7.dataTable({
      processing: true,
      info: false,
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      ajax: business_category_table_json,
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
  }
  if (dt_ajax_table8.length) {
    var dt_ajax = dt_ajax_table8.dataTable({
      processing: true,
      info: false,
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      ajax: location_master_table_json,
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
  }
  if (dt_ajax_table9.length) {
    var dt_ajax = dt_ajax_table9.dataTable({
      processing: true,
      serverSide: true,
      searching: false,
      info: false,
      order: [[0, 'desc']],
      columnDefs: [
        {
          targets: 0,
          visible: false,
          searchable: false,
        },
      ],
      lengthMenu: [
        [50, 100, 200, 400, 500, -1], // Values for rows to display
        [50, 100, 200, 400, 500, "Display All"] // Labels for dropdown
      ],
      ajax: {
        url: main_subcategory_table_json,
        data: function (d) {
          d.main_cat_id = $('#main_category').val();
          d.sub_cat_id = $('#sub_category').val();
          d.search = $('#search_by_name').val();
        }
      },
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
    $('#search_by_name').on('keypress', function (e) {
      if (e.which == 13) {
        dt_ajax.api().ajax.reload();
      }
    });
    $('#main_category, #sub_category').on('change', function (e) {
      dt_ajax.api().ajax.reload();
    });

  }

  // Column Search
  // --------------------------------------------------------------------

  if (dt_filter_table.length) {
    // Setup - add a text input to each footer cell
    $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');
    $('.dt-column-search thead tr:eq(1) th').each(function (i) {
      var title = $(this).text();
      var $input = $('<input type="text" class="form-control" placeholder="Search ' + title + '" />');

      // Add left and right border styles to the parent element
      $(this).css('border-left', 'none');
      if (i === $('.dt-column-search thead tr:eq(1) th').length - 1) {
        $(this).css('border-right', 'none');
      }

      $(this).html($input);

      $('input', this).on('keyup change', function () {
        if (dt_filter.column(i).search() !== this.value) {
          dt_filter.column(i).search(this.value).draw();
        }
      });
    });

    var dt_filter = dt_filter_table.DataTable({
      ajax: assetsPath + 'json/table-datatable.json',
      columns: [
        { data: 'full_name' },
        { data: 'email' },
        { data: 'post' },
        { data: 'city' },
        { data: 'start_date' },
        { data: 'salary' }
      ],
      orderCellsTop: true,
      dom: '<"row"<"col-sm-12 col-md-12 d-flex justify-content-end"f>>' +
        '<"table-responsive"t>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"p>>' +
        '<"row"<"col-sm-12 col-md-6"i>>'

    });
  }

  // Advanced Search
  // --------------------------------------------------------------------

  // Advanced Filter table
  if (dt_adv_filter_table.length) {
    var dt_adv_filter = dt_adv_filter_table.DataTable({
      dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
      ajax: assetsPath + 'json/table-datatable.json',
      columns: [
        { data: '' },
        { data: 'full_name' },
        { data: 'email' },
        { data: 'post' },
        { data: 'city' },
        { data: 'start_date' },
        { data: 'salary' }
      ],

      columnDefs: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        }
      ],
      orderCellsTop: true,
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  // on key up from input field
  $('input.dt-input').on('keyup', function () {
    filterColumn($(this).attr('data-column'), $(this).val());
  });

  // Responsive Table
  // --------------------------------------------------------------------

  if (dt_responsive_table.length) {
    var dt_responsive = dt_responsive_table.DataTable({
      ajax: assetsPath + 'json/table-datatable.json',
      columns: [
        { data: '' },
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
          targets: 0,
          searchable: false,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // Label
          targets: -1,
          render: function (data, type, full, meta) {
            var $status_number = full['status'];
            var $status = {
              1: { title: 'Current', class: 'bg-label-primary' },
              2: { title: 'Professional', class: ' bg-label-success' },
              3: { title: 'Rejected', class: ' bg-label-danger' },
              4: { title: 'Resigned', class: ' bg-label-warning' },
              5: { title: 'Applied', class: ' bg-label-info' }
            };
            if (typeof $status[$status_number] === 'undefined') {
              return data;
            }
            return (
              '<span class="badge ' + $status[$status_number].class + '">' + $status[$status_number].title + '</span>'
            );
          }
        }
      ],
      // scrollX: true,
      destroy: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 200);
});
