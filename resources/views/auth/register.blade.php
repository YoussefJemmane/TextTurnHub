@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="bg-white text-gray-800 p-8 rounded-xl shadow-lg w-full max-w-lg" x-data="{
    currentStep: 1,
    role: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    company_name: '',
    company_size: '',
    waste_types: [],
    artisan_specialty: '',
    artisan_experience: '',
    materials_interest: [],
    interests: [],
    sustainability_importance: '',

    nextStep() {
        if (this.currentStep === 1 && !this.role) {
            alert('Please select a role');
            return;
        }

        if (this.currentStep === 2) {
            if (!this.name || !this.email || !this.password || !this.password_confirmation) {
                alert('Please fill in all required fields');
                return;
            }

            if (this.password !== this.password_confirmation) {
                alert('Passwords do not match');
                return;
            }
        }

        this.currentStep++;
    },

    prevStep() {
        this.currentStep--;
    },

    handleSubmit(event) {
        // Basic validation
        if (!this.name || !this.email || !this.password) {
            alert('Please fill in all required fields');
            event.preventDefault();
            return;
        }

        // Role-specific validation
        if (this.role === 'company') {
            if (!this.company_name || !this.company_size) {
                alert('Please fill in all company information');
                event.preventDefault();
                return;
            }
        } else if (this.role === 'artisan') {
            if (!this.artisan_specialty || !this.artisan_experience) {
                alert('Please fill in all artisan information');
                event.preventDefault();
                return;
            }
        } else if (this.role === 'user') {
            if (!this.sustainability_importance) {
                alert('Please indicate how important sustainability is to you');
                event.preventDefault();
                return;
            }
        }
    }
}">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div :class="{'bg-green-600 text-white': currentStep >= 1, 'bg-gray-200': currentStep < 1}" class="flex items-center justify-center w-8 h-8 rounded-full">1</div>
                <div :class="{'bg-green-600': currentStep >= 1, 'bg-gray-200': currentStep < 1}" class="w-12 h-1 mx-2"></div>
            </div>
            <div class="flex items-center">
                <div :class="{'bg-green-600 text-white': currentStep >= 2, 'bg-gray-200': currentStep < 2}" class="flex items-center justify-center w-8 h-8 rounded-full">2</div>
                <div :class="{'bg-green-600': currentStep >= 2, 'bg-gray-200': currentStep < 2}" class="w-12 h-1 mx-2"></div>
            </div>
            <div class="flex items-center">
                <div :class="{'bg-green-600 text-white': currentStep >= 3, 'bg-gray-200': currentStep < 3}" class="flex items-center justify-center w-8 h-8 rounded-full">3</div>
            </div>
        </div>
    </div>

    <h2 class="text-3xl font-bold text-center mb-6">Create an Account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-on:submit="handleSubmit">
        @csrf
        <input type="hidden" name="role" x-model="role">

        <!-- Step 1: Select Role -->
        <div x-show="currentStep === 1">
            <h3 class="text-lg font-semibold mb-4">Select Your Role</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div @click="role = 'company'" :class="{'border-green-600 bg-green-50': role === 'company'}" class="border-2 rounded-lg p-4 cursor-pointer hover:border-green-600 transition-colors">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="font-medium">Company</span>
                    </div>
                </div>

                <div @click="role = 'artisan'" :class="{'border-green-600 bg-green-50': role === 'artisan'}" class="border-2 rounded-lg p-4 cursor-pointer hover:border-green-600 transition-colors">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                            </svg>
                        </div>
                        <span class="font-medium">Artisan</span>
                    </div>
                </div>

                <div @click="role = 'user'" :class="{'border-green-600 bg-green-50': role === 'user'}" class="border-2 rounded-lg p-4 cursor-pointer hover:border-green-600 transition-colors">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="font-medium">User</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" @click="nextStep" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">Next</button>
            </div>
        </div>

        <!-- Step 2: Basic Information -->
        <div x-show="currentStep === 2">
            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>

            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Name</label>
                <input id="name" type="text" name="name" x-model="name" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autofocus autocomplete="name">
                @error('name')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block font-medium mb-1">Email</label>
                <input id="email" type="email" name="email" x-model="email" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autocomplete="username">
                @error('email')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block font-medium mb-1">Password</label>
                <input id="password" type="password" name="password" x-model="password" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autocomplete="new-password">
                @error('password')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-medium mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" x-model="password_confirmation" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600" required autocomplete="new-password">
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep" class="border border-green-600 text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition">Back</button>
                <button type="button" @click="nextStep" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">Next</button>
            </div>
        </div>

        <!-- Step 3: Role Specific Information -->
        <div x-show="currentStep === 3">
            <!-- Company Fields -->
            <div x-show="role === 'company'">
                <h3 class="text-lg font-semibold mb-4">Company Information</h3>

                <div class="mb-4">
                    <label for="company_name" class="block font-medium mb-1">Company Name</label>
                    <input id="company_name" type="text" name="company_name" x-model="company_name" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                    @error('company_name')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="company_size" class="block font-medium mb-1">Company Size</label>
                    <select id="company_size" name="company_size" x-model="company_size" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                        <option value="">Select company size</option>
                        <option value="1-10">1-10 employees</option>
                        <option value="11-50">11-50 employees</option>
                        <option value="51-200">51-200 employees</option>
                        <option value="201-500">201-500 employees</option>
                        <option value="500+">500+ employees</option>
                    </select>
                    @error('company_size')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Waste Types Available</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="waste_types[]" value="textile" x-model="waste_types" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Textile Waste</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="waste_types[]" value="wood" x-model="waste_types" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Wood Waste</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="waste_types[]" value="plastic" x-model="waste_types" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Plastic Waste</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="waste_types[]" value="metal" x-model="waste_types" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Metal Scraps</span>
                        </label>
                    </div>
                    @error('waste_types')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Artisan Fields -->
            <div x-show="role === 'artisan'">
                <h3 class="text-lg font-semibold mb-4">Artisan Information</h3>

                <div class="mb-4">
                    <label for="artisan_specialty" class="block font-medium mb-1">Specialty</label>
                    <select id="artisan_specialty" name="artisan_specialty" x-model="artisan_specialty" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                        <option value="">Select your specialty</option>
                        <option value="clothing">Clothing</option>
                        <option value="accessories">Accessories</option>
                        <option value="home_decor">Home Decor</option>
                        <option value="furniture">Furniture</option>
                        <option value="other">Other</option>
                    </select>
                    @error('artisan_specialty')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="artisan_experience" class="block font-medium mb-1">Experience Level</label>
                    <select id="artisan_experience" name="artisan_experience" x-model="artisan_experience" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                        <option value="">Select experience level</option>
                        <option value="beginner">Beginner (0-2 years)</option>
                        <option value="intermediate">Intermediate (3-5 years)</option>
                        <option value="experienced">Experienced (5+ years)</option>
                        <option value="master">Master Artisan (10+ years)</option>
                    </select>
                    @error('artisan_experience')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Materials of Interest</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="materials_interest[]" value="textile" x-model="materials_interest" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Textile</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="materials_interest[]" value="wood" x-model="materials_interest" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Wood</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="materials_interest[]" value="plastic" x-model="materials_interest" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Plastic</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="materials_interest[]" value="metal" x-model="materials_interest" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Metal</span>
                        </label>
                    </div>
                    @error('materials_interest')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Regular User Fields -->
            <div x-show="role === 'user'">
                <h3 class="text-lg font-semibold mb-4">User Information</h3>

                <div class="mb-4">
                    <label class="block font-medium mb-2">What sustainable products are you interested in?</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="interests[]" value="clothing" x-model="interests" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Clothing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="interests[]" value="accessories" x-model="interests" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Accessories</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="interests[]" value="home_decor" x-model="interests" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Home Decor</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="interests[]" value="furniture" x-model="interests" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2">Furniture</span>
                        </label>
                    </div>
                    @error('interests')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sustainability_importance" class="block font-medium mb-1">How important is sustainability to you?</label>
                    <select id="sustainability_importance" name="sustainability_importance" x-model="sustainability_importance" class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                        <option value="">Select option</option>
                        <option value="very">Very important</option>
                        <option value="somewhat">Somewhat important</option>
                        <option value="neutral">Neutral</option>
                        <option value="learning">I'm just learning about it</option>
                    </select>
                    @error('sustainability_importance')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep" class="border border-green-600 text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition">Back</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">Register</button>
            </div>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-green-600 hover:underline">Sign In</a>
        </p>
    </div>
</div>
@endsection
