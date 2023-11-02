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
			searching: false,
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
	      console.log(data);
	      $('#modal-view-product').modal('show');
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {});
	}

	$('#modal-view-order').on('hidden.bs.modal', function(e) {
	  // do nothing
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
				<h4 class="modal-title">Order Detail</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table">
					<thead>
						<tr>
							<th style="width: 10px">#</th>
							<th>Task</th>
							<th>Progress</th>
							<th style="width: 40px">Label</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1.</td>
							<td>Update software</td>
							<td>
								<div class="progress progress-xs">
									<div class="progress-bar progress-bar-danger" style="width: 55%"></div>
								</div>
							</td>
							<td><span class="badge bg-danger">55%</span></td>
						</tr>
						<tr>
							<td>2.</td>
							<td>Clean database</td>
							<td>
								<div class="progress progress-xs">
									<div class="progress-bar bg-warning" style="width: 70%"></div>
								</div>
							</td>
							<td><span class="badge bg-warning">70%</span></td>
						</tr>
						<tr>
							<td>3.</td>
							<td>Cron job running</td>
							<td>
								<div class="progress progress-xs progress-striped active">
									<div class="progress-bar bg-primary" style="width: 30%"></div>
								</div>
							</td>
							<td><span class="badge bg-primary">30%</span></td>
						</tr>
						<tr>
							<td>4.</td>
							<td>Fix and squish bugs</td>
							<td>
								<div class="progress progress-xs progress-striped active">
									<div class="progress-bar bg-success" style="width: 90%"></div>
								</div>
							</td>
							<td><span class="badge bg-success">90%</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@stop