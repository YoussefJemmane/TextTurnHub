@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @role('company')
                {{-- Company Dashboard Content --}}
                @include('dashboard.company')
            @endrole


        </div>
    </div>
@endsection
