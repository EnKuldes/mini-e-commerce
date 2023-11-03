{{-- Extends Layout Auth --}}
@extends('layouts.app')
@section('title', 'Products')

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
		window['tbl'] = $("#productTable").DataTable({
			paging: true,
			lengthChange: !false,
			searching: !false,
			ordering: true,
			info: true,
			autoWidth: false,
			responsive: true,
			buttons: ["copy", "excel", {text: 'Add Product', action: function (e, dt, node, config) {
				$('#modal-create-product').modal('show');
			}}],
			dom: 'Bfrtip',
			serverSide: true,
      ajax: {
          url: '{{ url('Request/Product/List-products') }}',
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
              data: "name",
              name: "name",
              "defaultContent": "-"
          },
          {
              data: "price",
              name: "price",
              "defaultContent": "-"
          },
          {
              data: "created_at",
              name: "created_at",
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
		window['tbl'].buttons().container().appendTo('#productTable_wrapper .col-md-6:eq(0)');
	});

	$('#form-product-create').on('submit', function(e) {
	  e.preventDefault();
	  // pake FormData untuk handling file upload via AJAX
	  var dataForm = new FormData(this);
	  $.ajax({
	      type: "post",
	      url: '{{ url('Request/Product/Save-product') }}',
	      data: dataForm,
	      // untuk handling file upload
	      processData: false,
	      contentType: false,
	      cache: false,
	      enctype: 'multipart/form-data',
	  }).done(function(data, textStatus, jqXHR) {
	      window['toast'].fire({
	          icon: 'success',
	          title: 'Success submit form.'
	      });
	      $('#modal-create-product').modal('hide');
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {
	      window['tbl'].ajax.reload();
	  });
	});

	$('#form-product-edit').on('submit', function(e) {
	  e.preventDefault();
	  // pake FormData untuk handling file upload via AJAX
	  var dataForm = new FormData(this);
	  // tambahin select yg ke disabled
	  dataForm.append('product_id', $('#form-product-edit').find('[name=product_id]').val());
	  $.ajax({
	      type: "post",
	      url: '{{ url('Request/Product/Save-product') }}',
	      data: dataForm,
	      // untuk handling file upload
	      processData: false,
	      contentType: false,
	      cache: false,
	      enctype: 'multipart/form-data',
	  }).done(function(data, textStatus, jqXHR) {
	      window['toast'].fire({
	          icon: 'success',
	          title: 'Success submit form.'
	      });
	      $('#modal-edit-product').modal('hide');
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {
	      window['tbl'].ajax.reload();
	  });
	});

	$('#modal-edit-product').on('hidden.bs.modal', function(e) {
	  reset_form_edit();
	})
	$('#modal-create-product').on('hidden.bs.modal', function(e) {
	  reset_form();
	})

	function reset_form() {
	  $('#form-product-create').trigger('reset');
	  var iFields = ['product_id', 'name', 'description', 'price', 'images'];
	  for (var i = iFields.length - 1; i >= 0; i--) {
	      var el = $('#form-product-create').find('[name=' + iFields[i] + ']');
	      el.val('');
	      if (el.prop('nodeName') == 'SELECT') {
	          el.trigger('change');
	      }
	  }
	}

	function reset_form_edit() {
	  $('#form-product-edit').trigger('reset');
	  var iFields = ['product_id', 'name', 'description', 'price', 'images'];
	  for (var i = iFields.length - 1; i >= 0; i--) {
	      var el = $('#form-product-edit').find('[name=' + iFields[i] + ']');
	      el.val('');
	      if (el.prop('nodeName') == 'SELECT') {
	          el.trigger('change');
	      } else if (['images'].includes(iFields[i])) {
	          el.parent().find('span').removeClass('bg-warning bg-success').html('');
	      }
	  }
	}

	function f_edit_form_product(product_id) {
	  $.ajax({
	      type: "get",
	      url: '{{ url('Request/Product/Product') }}',
	      data: {
	          product_id: product_id
	      },
	  }).done(function(data, textStatus, jqXHR) {
	      var keys = ['product_id', 'name', 'description', 'price', 'images', 'id'];
	      for (var i = 0; i < keys.length; i++) {
	          if (['images'].includes(keys[i])) {
	              if (data.images && data.images.length > 0) {
	                  $('#form-product-edit input[name="attachments[]"]').parent().parent().parent().find('span').addClass('bg-success').html('Exist');
	              } else {
	                  $('#form-product-edit input[name="attachments[]"]').parent().parent().parent().find('span').addClass('bg-warning').html('Nonexistent');
	              }
	          } else if (keys[i] == 'id') {
	              $('#form-product-edit input[name=product_id]').val(data[keys[i]]);
	          } else {
	              $('#form-product-edit').find('[name=' + keys[i] + ']').val(data[keys[i]]);
	          }
	      }

	      $('#modal-edit-product').modal('show');
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {});
	}

	function f_delete_form_product(product_id) {
		Swal.fire({
			title: '<strong>Are you sure wanna delete this <u>Product</u></strong> ?',
			icon: 'warning',
			showCloseButton: !true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			'<i class="fa fa-thumbs-up"></i> Yes!',
			confirmButtonAriaLabel: 'Thumbs up, Yes!',
			cancelButtonText:
			'<i class="fa fa-thumbs-down"></i> No!',
			cancelButtonAriaLabel: 'Thumbs down, No!'
		}).then((result) => {
			if (result.isConfirmed) {
			  $.ajax({
			      type: "post",
			      url: '{{ url('Request/Product/Delete-product') }}',
			      data: {
			          product_id: product_id
			      },
			  }).done(function(data, textStatus, jqXHR) {
			      window['toast'].fire({
			          icon: 'success',
			          title: 'Success deleting data.'
			      });
			  }).fail(function(jqXhr, json, errorThrown) {
			      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
			  }).always(function() {
			  	window['tbl'].ajax.reload(null, false);
			  });
			}
		})
	}
</script>
@endpush

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Products</h1>
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
						<h3 class="card-title">List Products</h3>
					</div>              
					<div class="card-body">
						<table id="productTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Price</th>
									<th>Created At</th>
									<th>Tools</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Price</th>
									<th>Created At</th>
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
<div class="modal fade" id="modal-create-product" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create Product</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-product-create">
					<div class="form-group">
            <label for="productName">Name</label>
            <input type="text" class="form-control" id="productName" placeholder="Enter product name" name="name">
          </div>
          <div class="form-group">
            <label for="productDescription">Description</label>
            <textarea class="form-control" rows="3" id="productDescription" placeholder="Enter product Description" name="description"></textarea>
          </div>
          <div class="form-group">
            <label for="productPrice">Price (IDR)</label>
            <input type="number" class="form-control" id="productPrice" placeholder="Enter product price" name="price">
          </div>
          <div class="form-group">
	          <label for="productImages">Product Images <span class="badge"></span></label>
	          <div class="input-group">
	            <div class="custom-file">
	              <input type="file" class="custom-file-input" id="productImages" name="attachments[]" multiple>
	              <label class="custom-file-label" for="productImages">Choose file</label>
	            </div>
	          </div>
          </div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" form="form-product-create" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-edit-product" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Product</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-product-edit">
					<div class="form-group">
            <label for="productName">Name</label>
            <input type="text" class="form-control" id="productName" placeholder="Enter product name" name="name">
            <input type="hidden" name="product_id" value="">
          </div>
          <div class="form-group">
            <label for="productDescription">Description</label>
            <textarea class="form-control" rows="3" id="productDescription" placeholder="Enter product Description" name="description"></textarea>
          </div>
          <div class="form-group">
            <label for="productPrice">Price (IDR)</label>
            <input type="number" class="form-control" id="productPrice" placeholder="Enter product price" name="price">
          </div>
          <div class="form-group">
	          <label for="productImages">Product Images <span class="badge"></span></label>
	          <div class="input-group">
	            <div class="custom-file">
	              <input type="file" class="custom-file-input" id="productImages" name="attachments[]" multiple>
	              <label class="custom-file-label" for="productImages">Choose file</label>
	            </div>
	          </div>
          </div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" form="form-product-edit" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
@stop