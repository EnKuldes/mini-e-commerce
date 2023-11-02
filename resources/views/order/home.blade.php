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
	});
</script>
@endpush

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<h2 class="text-center display-4">Search</h2>
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<form action="{{ url("Page/Order/Home") }}" method="get">
					<div class="input-group">
						<input type="search" class="form-control form-control-lg" placeholder="Type your keywords here" name="search" value="{{ Request::get('search') }}">
						<div class="input-group-append">
							<button type="submit" class="btn btn-lg btn-success">
								<i class="fa fa-search"></i>
							</button>
						</div>
						<div class="input-group-append">
							<button type="button" class="btn btn-lg btn-default" onclick="window.location.href = '{{ url('Page/Order/Cart') }}'">
								<i class="fa fa-shopping-cart"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			@if (count($products) == 0)
			<div class="error-page">
				<div class="error-content">
					<h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! No result.</h3>
				</div>
			</div>
			@endif
			@foreach ($products as $record)
				<div class="col-sm-2 pr-1">
					<a href="{{ url("Page/Order/Information?product_id={$record->id}") }}" title="{{ $record->name }}"{{--  target=”_blank” --}}>
						<div class="card card-outline-info">
							<div class="card-body">
								<div class="text-center">
									<img class="img img-fluid img-thumbnail" src="{{ (count($record->images) > 0 && \Storage::exists($record->images[0]) ? \Storage::url($record->images[0]) : asset('dist/img/no-photo.jpg')) }}" alt="{{ $record->name }}" width="128" height="128">
								</div>
								<h3 class="profile-username text-center">{{ $record->name }}</h3>
								<p class="text-muted text-center">Rp. {{ number_format($record->price, 2) }}</p>
						  </div>
						</div>
					</a>
				</div>
			@endforeach
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="text-center align-self-center">
					{{ $products->links() }}
				</div>
			</div>
		</div>
	</div>
</section>

@stop