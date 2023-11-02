{{-- Extends Layout Auth --}}
@extends('layouts.app')
@section('title', 'Cart')

{{-- Push ke Stack --}}
@push('extra-lib-css')
@endpush
@push('extra-lib-js')
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
		getCart();
	});

	function getCart() {
		$.ajax({
	      type: "get",
	      url: '{{ url('Request/Order/Cart') }}',
	      data: {
	          // 
	      },
	  }).done(function(data, textStatus, jqXHR) {
	      var tHtml = "", fHtml = "";
	      var details = data.order_details;
	      if (details.length == 0) {
	      	tHtml = '<tr> <td colspan="4" rowspan="2" class="text-center">Nothing here</td> </tr>';
	      	fHtml = '<tr><td colspan="2" rowspan="2">Total</td><td colspan="2" rowspan="2">Rp. '+parseInt(0).toLocaleString('id-ID')+'</td></tr>'
	      }
	      for (var i = 0; i < details.length; i++) {
	      	tHtml += '<tr>';
	      	tHtml += '<td>'+details[i]['product']['name']+'</td>';
	      	tHtml += '<td><input class="form-control order-detail-qty" data-detail_id="'+details[i]['id']+'" type="number" min="1" value="'+details[i]['qty']+'"></td>';
	      	tHtml += '<td>Rp. '+details[i]['current_price'].toLocaleString('id-ID')+'</td>';
	      	tHtml += '<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="f_remove_from_cart('+details[i]['id']+')"><i class="fas fa-trash"></i> </button></td>';
	      	tHtml += '</tr>';
	      }
	      fHtml = '<tr><td colspan="2" rowspan="2">Total</td><td colspan="2" rowspan="2">Rp. '+parseInt(data.total_price).toLocaleString('id-ID')+'</td></tr>;'
	      $('#cartTable tbody').html(tHtml);
	      $('#cartTable tfoot').html(fHtml);
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {
	  	$('.order-detail-qty').each(function (i, e) {
				$(this).on('change', function (e) {
					var detail_id = $(this).data('detail_id'), qty = $(this).val();
					$.ajax({
						type: "post",
						url: '{{ url('Request/Order/Update-qty-product') }}',
						data: {
							detail_id: detail_id
							, qty: qty
						},
					}).done(function(data, textStatus, jqXHR) {
						window['toast'].fire({
							icon: 'success',
							title: 'Success updating qty.'
						});
					}).fail(function(jqXhr, json, errorThrown) {
						ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
					}).always(function() {
						getCart();
					});
				})
			});
	  });
	}

	function f_remove_from_cart(detail_id) {
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
					url: '{{ url('Request/Order/Remove-cart') }}',
					data: {
						detail_id: detail_id
					},
				}).done(function(data, textStatus, jqXHR) {
					window['toast'].fire({
						icon: 'success',
						title: 'Success deleting data.'
					});
				}).fail(function(jqXhr, json, errorThrown) {
					ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
				}).always(function() {
					getCart();
				});
			}
		})		
	}

	$('#btnCheckout').on('click', function (e) {
		$.ajax({
			type: "post",
			url: '{{ url('Request/Order/Checkout') }}',
			data: {
				// 
			},
		}).done(function(data, textStatus, jqXHR) {
			window['toast'].fire({
				icon: 'success',
				title: 'Successfully checkout.'
			});
			window.location.href = "{{ url('Page/Order/Home') }}";
		}).fail(function(jqXhr, json, errorThrown) {
			ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
		}).always(function() {
		});
	})
</script>
@endpush

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>My Cart</h1>
			</div>
			<div class="col-sm-6">
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="card">
					<div class="card-body p-0">
						<table class="table table-striped" id="cartTable">
							<thead>
								<tr>
									<th>Product Name</th>
									<th style="width: 15%">Qty</th>
									<th style="width: 30%">Price</th>
									<th style="width: 5%">Tools</th>
								</tr>
							</thead>
							<tbody>
								{{--  --}}
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" rowspan="2">Total</td>
									<td colspan="2" rowspan="2">Rp. 0.00</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="card-footer text-muted">
				    <button type="button" class="btn btn-primary" id="btnCheckout">Checkout</button>
				  </div>
				</div>
			</div>
		</div>
	</div>
</section>

@stop