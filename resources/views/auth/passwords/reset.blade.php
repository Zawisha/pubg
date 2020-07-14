@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="form-group text-center" style="font-size: 3rem; font-weight: bold; color: #ffc000;">
                    @lang('auth.restore.title')
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row text-center justify-content-center flex-column align-items-center">
                            <label for="email"
                                   class="col-form-label d-block text-center">@lang('auth.restore.email')</label>

                            <input id="email" type="email" style="font-size: 1.6rem;"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                   autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group row text-center justify-content-center flex-column align-items-center">
                            <label for="password"
                                   style="font-size: 1.6rem;"
                                   class="col-form-label d-block text-center">@lang('auth.restore.new_password')</label>

                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group row text-center justify-content-center flex-column align-items-center">
                            <label for="password-confirm"
                                   class="col-form-label d-block text-md-center">@lang('auth.restore.confirm_password')</label>

                            <input id="password-confirm"
                                   style="font-size: 1.6rem;"
                                   type="password" class="form-control"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group row text-center justify-content-center">
                            <div class="text-center">
                                <button type="submit" class="btn button-modal menu-button">
                                    @lang('auth.restore.set_password')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection