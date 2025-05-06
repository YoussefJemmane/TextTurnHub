@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="bg-white text-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-3xl font-bold text-center mb-6">Sign In</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block font-medium mb-1">Email</label>
            <input id="email" type="email" name="email" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autofocus autocomplete="username">
            @error('email')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium mb-1">Password</label>
            <input id="password" type="password" name="password" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autocomplete="current-password">
            @error('password')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4 flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>

        <div class="flex justify-between items-center mt-6">
            <div class="text-sm">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-green-600 hover:underline">Forgot your password?</a>
                @endif
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">Sign In</button>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-green-600 hover:underline">Register</a>
            </p>
        </div>
    </form>
</div>
@endsection
