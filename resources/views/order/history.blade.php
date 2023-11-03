{{-- Extends Layout Auth --}}
@extends('layouts.app')
@section('title', 'Order History')

{{-- Push ke Stack --}}
@push('extra-lib-css')
{{-- DataTables --}}
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@push('extra-lib-js')
{{-- DataTables  & Plugins --}}
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endpush
@push('extra-css')
@endpush
@push('extra-js')
<script>
	$.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
	$(document).ready(function () {
		"use strict";
		window['tbl'] = $("#orderTable").DataTable({
			paging: true,
			lengthChange: false,
			searching: !false,
			ordering: true,
			info: true,
			autoWidth: false,
			responsive: true,
			buttons: ["copy", "excel"],
			dom: 'Bfrtip',
			serverSide: true,
      ajax: {
          url: '{{ url('Request/Order/List-orders') }}',
          data: function(d) {
              return $.extend({}, d, {
                  /* parameter tambahan */
              });
          },
      },
      columns: [{
              data: "DT_RowIndex",
              name: "DT_RowIndex",
              "defaultContent": "-",
              orderable: false,
              searchable: false
          },
          {
              data: "order_date",
              name: "order_date",
              "defaultContent": "-"
          },
          {
              data: "total_price",
              name: "total_price",
              "defaultContent": "-"
          },
          {
              data: "order_status",
              name: "order_status",
              "defaultContent": "-"
          },
          {
              data: "tools",
              name: "tools",
              orderable: false,
              searchable: false
          },
      ]
		});
		window['tbl'].buttons().container().appendTo('#orderTable_wrapper .col-md-6:eq(0)');
	});

	function f_view_order(order_id) {
		$.ajax({
	      type: "get",
	      url: '{{ url('Request/Order/Detail') }}',
	      data: {
	          order_id: order_id
	      },
	  }).done(function(data, textStatus, jqXHR) {
	      var tHtml = "", fHtml = "";
	      var details = data.order_details;
	      if (details.length == 0) {
	      	tHtml = '<tr> <td colspan="4" rowspan="2" class="text-center">Nothing here</td> </tr>';
	      	fHtml = '<tr><td colspan="1" rowspan="2">Total</td><td colspan="2" rowspan="2">Rp. '+parseInt(0).toLocaleString('id-ID')+'</td></tr>'
	      }
	      for (var i = 0; i < details.length; i++) {
	      	tHtml += '<tr>';
	      	tHtml += '<td>'+details[i]['product']['name']+'</td>';
	      	tHtml += '<td>'+details[i]['qty']+'</td>';
	      	tHtml += '<td>Rp. '+details[i]['current_price'].toLocaleString('id-ID')+'</td>';
	      	tHtml += '</tr>';
	      }
	      fHtml = '<tr><td colspan="2" rowspan="2">Total</td><td colspan="1" rowspan="2">Rp. '+parseInt(data.total_price).toLocaleString('id-ID')+'</td></tr>;'
	      $('#orderDetailsTable tbody').html(tHtml);
	      $('#orderDetailsTable tfoot').html(fHtml);
	      $('#orderDate').html(data.ordered_at)
	      $('#modal-view-order').modal('show');
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {});
	}

	$('#modal-view-order').on('hidden.bs.modal', function(e) {
	  $('#orderDetailsTable tbody').html('');
    $('#orderDetailsTable tfoot').html('');
	});
</script>
@endpush

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Orders</h1>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">List Order</h3>
					</div>              
					<div class="card-body">
						<table id="orderTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No</th>
									<th>Date</th>
									<th>Total Payment</th>
									<th>Status</th>
									<th>Tools</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th>No</th>
									<th>Date</th>
									<th>Total Payment</th>
									<th>Status</th>
									<th>Tools</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

{{-- Modal --}}
<div class="modal fade" id="modal-view-order" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{{-- Ordered at --}} <span id="orderDate"></span></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table" id="orderDetailsTable">
					<thead>
						<tr>
							<th>Product Name</th>
							<th style="width: 15%">Qty</th>
							<th style="width: 30%">Price</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot class="bg bg-success">
						<tr>
							<td colspan="2" rowspan="2">Total</td>
							<td colspan="1" rowspan="2">Rp. 0.00</td>
						</tr>

					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@stop