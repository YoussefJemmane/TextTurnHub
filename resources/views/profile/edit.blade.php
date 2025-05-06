@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class=" text-gray-800 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
        </div>

        <!-- Profile Section Cards -->
        <div class="space-y-6">
            <!-- Profile Information Card -->
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-100">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Profile Information
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your account's profile information and email address.
                        </p>
                    </header>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block font-medium mb-1 text-gray-700">Name</label>
                            <input id="name" name="name" type="text"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600"
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block font-medium mb-1 text-gray-700">Email</label>
                            <input id="email" name="email" type="email"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600"
                                value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">
                                        Your email address is unverified.

                                        <button form="send-verification"
                                            class="text-green-600 underline hover:text-green-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Click here to re-send the verification email.
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            A new verification link has been sent to your email address.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center">
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                Save Changes
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="ml-4 text-sm text-green-600"
                                >Saved successfully!</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-100">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Update Password
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Ensure your account is using a long, random password to stay secure.
                        </p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block font-medium mb-1 text-gray-700">Current Password</label>
                            <input id="current_password" name="current_password" type="password"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600"
                                autocomplete="current-password">
                            @error('current_password')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-medium mb-1 text-gray-700">New Password</label>
                            <input id="password" name="password" type="password"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600"
                                autocomplete="new-password">
                            @error('password')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-medium mb-1 text-gray-700">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-600 focus:border-green-600"
                                autocomplete="new-password">
                            @error('password_confirmation')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                Update Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="ml-4 text-sm text-green-600"
                                >Password updated!</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-100">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Delete Account
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                    </header>

                    <button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition"
                    >Delete Account</button>

                    <div
                        x-data="{ show: false, name: 'confirm-user-deletion' }"
                        x-show="show"
                        x-on:open-modal.window="show = ($event.detail === name)"
                        x-on:close.stop="show = false"
                        x-on:keydown.escape.window="show = false"
                        class="fixed inset-0 z-50 overflow-y-auto"
                        style="display: none;"
                    >
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div
                                x-on:click="show = false"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                            ></div>

                            <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                    @csrf
                                    @method('delete')

                                    <h2 class="text-lg font-medium text-gray-900">
                                        Are you sure you want to delete your account?
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Once your account is deleted, all of its resources and data will be permanently deleted.
                                        Please enter your password to confirm you would like to permanently delete your account.
                                    </p>

                                    <div class="mt-6">
                                        <label for="password" class="sr-only">Password</label>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-red-600 focus:border-red-600"
                                            placeholder="Password"
                                        />
                                        @error('password')
                                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            x-on:click="show = false"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition"
                                        >Cancel</button>

                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                        >Delete Account</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
