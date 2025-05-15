@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="min-h-screen py-8 bg-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>

            <!-- Main content card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-8" x-data="{
                currentStep: 1,
                totalSteps: 3,
                formData: {
                    name: '',
                    description: '',
                    category: '',
                    price: '',
                    stock: '',
                    unit: '',
                    color: '',
                    material: '',
                    image: null
                },
                imagePreview: null,

                submitForm() {
                    const form = document.getElementById('product-form');
                    const formData = new FormData(form);

                    if (this.imagePreview) {
                        formData.append('image', this.imagePreview.file);
                    }

                    form.submit();
                },

                nextStep() {
                    if (this.currentStep < this.totalSteps) {
                        this.currentStep++;
                        window.scrollTo(0, 0);
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo(0, 0);
                    }
                },

                stepComplete() {
                    if (this.currentStep === 1) {
                        return this.formData.name && this.formData.category && this.formData.price;
                    } else if (this.currentStep === 2) {
                        return this.formData.stock && this.formData.unit;
                    }
                    return true;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = {
                                src: e.target.result,
                                name: file.name,
                                size: this.formatFileSize(file.size),
                                file: file
                            };
                        };
                        reader.readAsDataURL(file);
                    }
                },

                formatFileSize(size) {
                    if (size < 1024) {
                        return size + ' bytes';
                    } else if (size < 1024 * 1024) {
                        return (size / 1024).toFixed(1) + ' KB';
                    } else {
                        return (size / (1024 * 1024)).toFixed(1) + ' MB';
                    }
                },

                removeImage() {
                    this.imagePreview = null;
                    const fileInput = document.getElementById('image');
                    fileInput.value = '';
                }
            }">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div :class="{ 'bg-green-600 text-white': currentStep >= 1, 'bg-gray-200': currentStep < 1 }"
                                class="flex items-center justify-center w-8 h-8 rounded-full">1</div>
                            <div :class="{ 'bg-green-600': currentStep >= 1, 'bg-gray-200': currentStep < 1 }"
                                class="w-12 h-1 mx-2"></div>
                        </div>
                        <div class="flex items-center">
                            <div :class="{ 'bg-green-600 text-white': currentStep >= 2, 'bg-gray-200': currentStep < 2 }"
                                class="flex items-center justify-center w-8 h-8 rounded-full">2</div>
                            <div :class="{ 'bg-green-600': currentStep >= 2, 'bg-gray-200': currentStep < 2 }"
                                class="w-12 h-1 mx-2"></div>
                        </div>
                        <div class="flex items-center">
                            <div :class="{ 'bg-green-600 text-white': currentStep >= 3, 'bg-gray-200': currentStep < 3 }"
                                class="flex items-center justify-center w-8 h-8 rounded-full">3</div>
                        </div>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create Product</h2>

                <form id="product-form" method="POST" action="{{ route('products.store') }}"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div x-show="currentStep === 1" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Basic Information</h3>
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                                <input id="name" name="name" type="text" required x-model="formData.name"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., Handmade Tote Bag">
                                @error('name')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Description -->
                            <div>
                                <label for="description" class="block font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="4" x-model="formData.description"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="Describe your product in detail..."></textarea>
                                @error('description')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Category -->
                                <div>
                                    <label for="category" class="block font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                    <select id="category" name="category" required x-model="formData.category"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                                        <option value="">Select category</option>
                                        <option value="bags">Bags</option>
                                        <option value="clothing">Clothing</option>
                                        <option value="accessories">Accessories</option>
                                        <option value="home">Home</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('category')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block font-medium text-gray-700 mb-1">Price (MAD) <span class="text-red-500">*</span></label>
                                    <input id="price" name="price" type="number" step="0.01" required x-model="formData.price"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="0.00">
                                    @error('price')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Details -->
                    <div x-show="currentStep === 2" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Product Details</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
                                <input id="stock" name="stock" type="number" required x-model="formData.stock"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="Enter available quantity">
                                @error('stock')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                                <select id="unit" name="unit" required x-model="formData.unit"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                                    <option value="">Select unit</option>
                                    <option value="piece">Piece</option>
                                    <option value="set">Set</option>
                                    <option value="kg">Kilogram</option>
                                </select>
                                @error('unit')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Color -->
                            <div>
                                <label for="color" class="block font-medium text-gray-700 mb-1">Color</label>
                                <select id="color" name="color" x-model="formData.color"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                                    <option value="">Select a color</option>
                                    <option value="red">Red</option>
                                    <option value="blue">Blue</option>
                                    <option value="green">Green</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="purple">Purple</option>
                                    <option value="pink">Pink</option>
                                    <option value="orange">Orange</option>
                                    <option value="brown">Brown</option>
                                    <option value="black">Black</option>
                                    <option value="white">White</option>
                                    <option value="grey">Grey</option>
                                    <option value="multicolor">Multicolor</option>
                                </select>
                                @error('color')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Material -->
                            <div>
                                <label for="material" class="block font-medium text-gray-700 mb-1">Material</label>
                                <input id="material" name="material" type="text" x-model="formData.material"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., Cotton, Leather, Wool">
                                @error('material')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Image Upload -->
                    <div x-show="currentStep === 3" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Product Image</h3>
                        <div class="mt-6">
                            <label class="block font-medium text-gray-700 mb-1">Image</label>
                            <div class="mb-4" x-show="imagePreview !== null">
                                <div class="flex flex-wrap gap-4">
                                    <div class="relative bg-gray-50 rounded-lg p-2 border border-gray-200 shadow-sm">
                                        <div class="absolute top-2 right-2 z-10">
                                            <button type="button" @click="removeImage()"
                                                class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-center bg-white rounded border border-gray-100"
                                            style="width: 200px; height: 200px;">
                                            <img x-bind:src="imagePreview ? imagePreview.src : ''"
                                                class="max-h-full max-w-full object-contain rounded"
                                                style="max-width: 200px; max-height: 200px;" />
                                        </div>
                                        <div class="mt-2 text-xs">
                                            <div class="text-gray-800 truncate" x-text="imagePreview ? imagePreview.name : ''"></div>
                                            <div class="text-gray-500" x-text="imagePreview ? imagePreview.size : ''"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input id="image" name="image" type="file" accept="image/*"
                                    @change="handleImageUpload" class="hidden">
                                <label for="image" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer block"
                                    x-show="!imagePreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Click to upload an image</p>
                                    <p class="text-xs text-gray-500">(max 2MB)</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="button" x-show="currentStep > 1" @click="prevStep()"
                            class="border border-green-600 text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        <div class="flex justify-end">
                            <button type="button" x-show="currentStep < totalSteps" @click="nextStep()"
                                :disabled="!stepComplete()"
                                :class="{ 'opacity-50 cursor-not-allowed': !stepComplete() }"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition flex items-center">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="button" x-show="currentStep === totalSteps" @click="submitForm()"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition flex items-center">
                                Create Product
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorSelect = document.getElementById('color');
            if (!colorSelect) return;
            const options = colorSelect.options;
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const value = option.value;
                if (value) {
                    option.style.backgroundColor = value === 'white' ? '#ffffff' : 'transparent';
                    option.style.paddingLeft = '30px';
                    option.style.position = 'relative';
                }
            }
        });
    </script>
@endsection