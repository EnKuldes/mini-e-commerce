{{-- Extends Layout Auth --}}
@extends('layouts.app')
@section('title', 'Products')

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
		$('.product-image-thumb').on('click', function () {
      var $image_element = $(this).find('img')
      $('.product-image').prop('src', $image_element.attr('src'))
      $('.product-image-thumb.active').removeClass('active')
      $(this).addClass('active')
    });
	});

	function addToCart(product_id) {
		$.ajax({
	      type: "post",
	      url: '{{ url('Request/Order/Add-cart') }}',
	      data: {
	          product_id: product_id
	      },
	  }).done(function(data, textStatus, jqXHR) {
	      Swal.fire({
					title: '<strong>Success adding to cart</strong>',
					icon: 'success',
					showCloseButton: !true,
					showCancelButton: true,
					focusConfirm: false,
					confirmButtonText:
					'<i class="fa fa-shopping-cart"></i> go to Cart',
					cancelButtonText:
					'<i class="fa fa-shopping-bag"></i> browse more',
				}).then((result) => {
					if (result.isConfirmed) {
					  window.location.href = '{{ url('/Page/Order/Cart') }}'
					} else if (result.isDismissed) {
						window.location.href = '{{ url('/Page/Order/Home') }}'
					}
				})
	  }).fail(function(jqXhr, json, errorThrown) {
	      ajaxFailedNotify(jqXhr.responseJSON, errorThrown)
	  }).always(function() {});
	}
</script>
@endpush

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Product Information</h1>
			</div>
			<div class="col-sm-6">
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>

<section class="content">
	<div class="card card-solid">
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-sm-6">
					<h3 class="d-inline-block d-sm-none">{{ $product->name }}</h3>
					<div class="col-12">
						<img src="{{ (count($product->images) > 0 && \Storage::exists($product->images[0]) ? \Storage::url($product->images[0]) : asset('dist/img/no-photo.jpg')) }}" class="product-image" alt="Product Image">
					</div>
					@if (count($product->images) > 0)
					<div class="col-12 product-image-thumbs">
						@for ($i = 0; $i < count($product->images); $i++)
						<div class="product-image-thumb {{ ($i == 0 ? 'active' : '') }}"><img src="{{ (\Storage::exists($product->images[$i]) ? \Storage::url($product->images[$i]) : asset('dist/img/no-photo.jpg')) }}" alt="Product Image"></div>
						@endfor
					</div>
					@endif
				</div>
				<div class="col-12 col-sm-6">
					<h3 class="my-3">{{ $product->name }}</h3>
					<hr>

					<div class="bg-gray py-2 px-3 mt-4">
						<h2 class="mb-0">
							Rp. {{ number_format($product->price, 2)}}
						</h2>
					</div>

					<div class="mt-4">
						<button type="button" class="btn btn-primary btn-lg btn-flat" onclick="addToCart({{ $product->id }})">
							<i class="fas fa-cart-plus fa-lg mr-2"></i>
							Add to Cart
						</button>

						{{-- <div class="btn btn-default btn-lg btn-flat">
							<i class="fas fa-heart fa-lg mr-2"></i>
							Add to Wishlist
						</div> --}}
					</div>
					<div class="mt-4">
						<nav class="w-100">
							<div class="nav nav-tabs" id="product-tab" role="tablist">
								<a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Description</a>
								<a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Comments</a>
								<a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>
							</div>
						</nav>
						<div class="tab-content p-3" id="nav-tabContent">
							<div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab"> {{ $product->description }} </div>
							<div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"> Vivamus rhoncus nisl sed venenatis luctus. Sed condimentum risus ut tortor feugiat laoreet. Suspendisse potenti. Donec et finibus sem, ut commodo lectus. Cras eget neque dignissim, placerat orci interdum, venenatis odio. Nulla turpis elit, consequat eu eros ac, consectetur fringilla urna. Duis gravida ex pulvinar mauris ornare, eget porttitor enim vulputate. Mauris hendrerit, massa nec aliquam cursus, ex elit euismod lorem, vehicula rhoncus nisl dui sit amet eros. Nulla turpis lorem, dignissim a sapien eget, ultrices venenatis dolor. Curabitur vel turpis at magna elementum hendrerit vel id dui. Curabitur a ex ullamcorper, ornare velit vel, tincidunt ipsum. </div>
							<div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> Cras ut ipsum ornare, aliquam ipsum non, posuere elit. In hac habitasse platea dictumst. Aenean elementum leo augue, id fermentum risus efficitur vel. Nulla iaculis malesuada scelerisque. Praesent vel ipsum felis. Ut molestie, purus aliquam placerat sollicitudin, mi ligula euismod neque, non bibendum nibh neque et erat. Etiam dignissim aliquam ligula, aliquet feugiat nibh rhoncus ut. Aliquam efficitur lacinia lacinia. Morbi ac molestie lectus, vitae hendrerit nisl. Nullam metus odio, malesuada in vehicula at, consectetur nec justo. Quisque suscipit odio velit, at accumsan urna vestibulum a. Proin dictum, urna ut varius consectetur, sapien justo porta lectus, at mollis nisi orci et nulla. Donec pellentesque tortor vel nisl commodo ullamcorper. Donec varius massa at semper posuere. Integer finibus orci vitae vehicula placerat. </div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->

</section>

@stop