@extends('layouts.admin')

@section('content')
    @isset($header)
        <div class="mb-4">
            {{ $header }}
        </div>
    @endisset

    {{ $slot ?? '' }}
    @yield('app_content')
@endsection
