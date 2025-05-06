@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="bg-white text-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-3xl font-bold text-center mb-6">Forgot Password</h2>

    <p class="mb-6 text-gray-600 text-center">
        No problem! Enter your email address and we'll send you a link to reset your password.
    </p>

    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="block font-medium mb-1">Email</label>
            <input id="email" type="email" name="email" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autofocus>

            @error('email')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('login') }}" class="text-green-600 hover:underline">Back to login</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                Send Reset Link
            </button>
        </div>
    </form>
</div>
@endsection
