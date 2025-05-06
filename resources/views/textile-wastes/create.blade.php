@extends('layouts.app')

@section('title', 'Create Textile Waste Listing')

@section('content')
    <div class="min-h-screen py-8 bg-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('textile-waste.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Listings
                    </a>
                </div>
            </div>

            <!-- Main content card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-8" x-data="{
                currentStep: 1,
                totalSteps: 4,
                formData: {
                    title: '',
                    description: '',
                    waste_type: '',
                    material_type: '',
                    quantity: '',
                    unit: '',
                    condition: '',
                    color: '',
                    composition: '',
                    minimum_order_quantity: '',
                    price_per_unit: '',
                    location: '',
                    availability_status: 'available',
                    sustainability_metrics: {
                        water_saved: '',
                        co2_reduced: '',
                        landfill_diverted: '',
                        energy_saved: ''
                    }
                },
                imagePreview: null,

                // Submit form with image
                submitForm() {

                    const form = document.getElementById('textile-waste-form');
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
                        return this.formData.title && this.formData.waste_type && this.formData.material_type;
                    } else if (this.currentStep === 2) {
                        return this.formData.quantity && this.formData.unit && this.formData.condition && this.formData.location;
                    }
                    return true;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];

                    if (file) {
                        // Process the file for preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = {
                                src: e.target.result,
                                name: file.name,
                                size: this.formatFileSize(file.size),
                                file: file // Store the file object
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
                    // Clear the preview
                    this.imagePreview = null;

                    // Clear the file input
                    const fileInput = document.getElementById('image');
                    fileInput.value = '';
                },

                progressPercentage() {
                    return ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
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
                            <div :class="{ 'bg-green-600': currentStep >= 3, 'bg-gray-200': currentStep < 3 }"
                                class="w-12 h-1 mx-2"></div>
                        </div>
                        <div class="flex items-center">
                            <div :class="{ 'bg-green-600 text-white': currentStep >= 4, 'bg-gray-200': currentStep < 4 }"
                                class="flex items-center justify-center w-8 h-8 rounded-full">4</div>
                        </div>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create Textile Waste Listing</h2>

                <form id="textile-waste-form" method="POST" action="{{ route('textile-waste.store') }}"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div x-show="currentStep === 1" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Basic Information</h3>

                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block font-medium text-gray-700 mb-1">Title <span
                                        class="text-red-500">*</span></label>
                                <input id="title" name="title" type="text" required x-model="formData.title"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., Cotton Fabric Remnants">
                                @error('title')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="4" x-model="formData.description"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="Describe your textile waste in detail..."></textarea>
                                @error('description')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Waste Type -->
                                <div>
                                    <label for="waste_type" class="block font-medium text-gray-700 mb-1">Waste Type <span
                                            class="text-red-500">*</span></label>
                                    <select id="waste_type" name="waste_type" required x-model="formData.waste_type"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                                        <option value="">Select waste type</option>
                                        <option value="fabric">Fabric</option>
                                        <option value="yarn">Yarn</option>
                                        <option value="offcuts">Offcuts</option>
                                        <option value="scraps">Scraps</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('waste_type')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Material Type -->
                                <div>
                                    <label for="material_type" class="block font-medium text-gray-700 mb-1">Material Type
                                        <span class="text-red-500">*</span></label>
                                    <input id="material_type" name="material_type" type="text" required
                                        x-model="formData.material_type"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="E.g., Cotton, Polyester, Wool">
                                    @error('material_type')
                                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Specifications -->
                    <div x-show="currentStep === 2" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Specifications</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block font-medium text-gray-700 mb-1">Quantity <span
                                        class="text-red-500">*</span></label>
                                <input id="quantity" name="quantity" type="number" step="0.01" required
                                    x-model="formData.quantity"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="Enter quantity">
                                @error('quantity')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block font-medium text-gray-700 mb-1">Unit <span
                                        class="text-red-500">*</span></label>
                                <select id="unit" name="unit" required x-model="formData.unit"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600">
                                    <option value="">Select unit</option>
                                    <option value="kg">Kilograms (kg)</option>
                                    <option value="meters">Meters</option>
                                    <option value="pieces">Pieces</option>
                                </select>
                                @error('unit')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Condition -->
                            <div>
                                <label for="condition" class="block font-medium text-gray-700 mb-1">Condition <span
                                        class="text-red-500">*</span></label>
                                <input id="condition" name="condition" type="text" required
                                    x-model="formData.condition"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., New, Used, Damaged">
                                @error('condition')
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

                            <!-- Composition -->
                            <div>
                                <label for="composition" class="block font-medium text-gray-700 mb-1">Composition</label>
                                <input id="composition" name="composition" type="text" x-model="formData.composition"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., 80% Cotton, 20% Polyester">
                                @error('composition')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block font-medium text-gray-700 mb-1">Location <span
                                        class="text-red-500">*</span></label>
                                <input id="location" name="location" type="text" required
                                    x-model="formData.location"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="E.g., New York, NY">
                                @error('location')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Pricing & Availability -->
                    <div x-show="currentStep === 3" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Pricing & Availability</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Minimum Order Quantity -->
                            <div>
                                <label for="minimum_order_quantity" class="block font-medium text-gray-700 mb-1">Minimum
                                    Order Quantity</label>
                                <input id="minimum_order_quantity" name="minimum_order_quantity" type="number"
                                    step="0.01" x-model="formData.minimum_order_quantity"
                                    class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                    placeholder="Enter minimum quantity">
                                @error('minimum_order_quantity')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Price Per Unit -->
                            <div>
                                <label for="price_per_unit" class="block font-medium text-gray-700 mb-1">Price Per
                                    Unit</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500">MAD</span>
                                    </div>
                                    <input id="price_per_unit" name="price_per_unit" type="number" step="0.01"
                                        x-model="formData.price_per_unit"
                                        class="w-full p-3 pl-8 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="0.00">
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Leave blank if offering for free</p>
                                @error('price_per_unit')
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="mt-6">
                            <label class="block font-medium text-gray-700 mb-1">Image</label>

                            <!-- Image Preview - Fixed version with proper null checking -->
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

                            <!-- Always have the file input in the DOM regardless of step -->
                            <div :class="{ 'hidden': currentStep !== 3 }">
                                <input id="image" name="image" type="file" accept="image/*"
                                    @change="handleImageUpload" class="hidden">
                            </div>

                            <!-- Upload button - shown when no image -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                                x-show="!imagePreview">
                                <label for="image" class="cursor-pointer">
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

                    <!-- Step 4: Sustainability Metrics -->
                    <div x-show="currentStep === 4" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Sustainability Metrics</h3>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <p class="text-sm text-gray-700 mb-4">These metrics help buyers understand the
                                environmental
                                impact of reusing your materials.</p>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="water_saved"
                                        class="block text-sm font-medium text-gray-700 mb-1">Water
                                        Saved (liters)</label>
                                    <input id="water_saved" name="sustainability_metrics[water_saved]" type="number"
                                        x-model="formData.sustainability_metrics.water_saved"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="Estimated water saved">
                                </div>
                                <div>
                                    <label for="co2_reduced" class="block text-sm font-medium text-gray-700 mb-1">CO2
                                        Emissions Reduced (kg)</label>
                                    <input id="co2_reduced" name="sustainability_metrics[co2_reduced]" type="number"
                                        x-model="formData.sustainability_metrics.co2_reduced"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="Estimated CO2 reduction">
                                </div>
                                <div>
                                    <label for="landfill_diverted"
                                        class="block text-sm font-medium text-gray-700 mb-1">Landfill Waste Diverted
                                        (kg)</label>
                                    <input id="landfill_diverted" name="sustainability_metrics[landfill_diverted]"
                                        type="number" x-model="formData.sustainability_metrics.landfill_diverted"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="Estimated waste diverted">
                                </div>
                                <div>
                                    <label for="energy_saved"
                                        class="block text-sm font-medium text-gray-700 mb-1">Energy
                                        Saved (kWh)</label>
                                    <input id="energy_saved" name="sustainability_metrics[energy_saved]"
                                        type="number" x-model="formData.sustainability_metrics.energy_saved"
                                        class="w-full p-3 border rounded-lg focus:ring-green-600 focus:border-green-600"
                                        placeholder="Estimated energy saved">
                                </div>
                            </div>

                            <div class="mt-6">
                                <div class="bg-white p-4 rounded-lg border border-green-200">
                                    <h3 class="font-medium text-gray-800 mb-2">Sustainability Impact Summary</h3>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                        <div class="bg-green-50 p-3 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-green-700"
                                                x-text="formData.sustainability_metrics.water_saved || '0'"></div>
                                            <div class="text-xs text-gray-600">Liters of Water</div>
                                        </div>
                                        <div class="bg-green-50 p-3 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-green-700"
                                                x-text="formData.sustainability_metrics.co2_reduced || '0'"></div>
                                            <div class="text-xs text-gray-600">kg CO2 Reduced</div>
                                        </div>
                                        <div class="bg-green-50 p-3 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-green-700"
                                                x-text="formData.sustainability_metrics.landfill_diverted || '0'">
                                            </div>
                                            <div class="text-xs text-gray-600">kg Waste Diverted</div>
                                        </div>
                                        <div class="bg-green-50 p-3 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-green-700"
                                                x-text="formData.sustainability_metrics.energy_saved || '0'"></div>
                                            <div class="text-xs text-gray-600">kWh Energy Saved</div>
                                        </div>
                                    </div>
                                </div>
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
                                Create Listing
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
        // Add this to enhance the select with color indicators
        document.addEventListener('DOMContentLoaded', function() {
            const colorSelect = document.getElementById('color');
            const options = colorSelect.options;

            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const value = option.value;

                if (value) {
                    const color = value === 'multicolor' ?
                        'linear-gradient(to right, red, green, blue)' :
                        value;

                    option.style.backgroundColor = value === 'white' ? '#ffffff' : 'transparent';
                    option.style.paddingLeft = '30px';
                    option.style.position = 'relative';
                }
            }
        });
    </script>
@endsection
