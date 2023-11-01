{{-- Extends Layout Auth --}}
@extends('auth.app')
@section('title', 'Login')

{{-- Push ke Stack --}}
@push('extra-lib-css')
@endpush
@push('extra-lib-js')
@endpush
@push('extra-css')
@endpush
@push('extra-js')
@endpush

@section('content')
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register a new account.</p>

      <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" value="{{ old('name') }}" name="name" autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
          <span class="error invalid-feedback" style="display:block;">{{ $message }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" name="email" autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
          <span class="error invalid-feedback" style="display:block;">{{ $message }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
          <span class="error invalid-feedback" style="display:block;">{{ $message }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password_confirmation')
          <span class="error invalid-feedback" style="display:block;">{{ $message }}</span>
          @enderror
        </div>
        <div class="row">
          <div class="col-8">
            {{-- <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div> --}}
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="{{ route('login') }}">I already have a membership</a>
      </p>
    </div>
  </div>
  
@stop