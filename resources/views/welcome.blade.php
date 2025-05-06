@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<div class="w-full">
    <!-- Hero Section -->
    <section class="relative text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">Transform Textile Waste into Opportunities</h1>
                <p class="text-xl mb-8">Connect with companies and artisans to give textile waste a second life while
                    reducing environmental impact.</p>
                
            </div>
        </div>
    </section>

    <!-- Rest of the welcome page content -->
    <div class="bg-white">
        <!-- User Types Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Companies</h3>
                        <p class="text-gray-600">List your textile waste and connect with recyclers or artisans</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Artisans</h3>
                        <p class="text-gray-600">Find free/low-cost textile materials and sell your recycled products</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Regular Users</h3>
                        <p class="text-gray-600">Purchase sustainable, recycled products directly from artisans</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-gray-100 py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Main Features</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-3">User Registration & Profiles</h3>
                        <p class="text-gray-600">Customized profiles for companies, artisans, and regular users</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-3">Waste Exchange Marketplace</h3>
                        <p class="text-gray-600">Easy textile waste listing and exchange platform</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-3">Carbon Savings Tracker</h3>
                        <p class="text-gray-600">Monitor your environmental impact in real-time</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-16" x-data="{
            activeTab: 'companies',
            steps: {
                companies: [
                    { title: 'Register', description: 'Create your company profile' },
                    { title: 'List Waste', description: 'Add your textile waste details' },
                    { title: 'Connect', description: 'Match with artisans' },
                    { title: 'Track Impact', description: 'Monitor carbon savings' }
                ],
                artisans: [
                    { title: 'Register', description: 'Create artisan profile' },
                    { title: 'Browse', description: 'Find available materials' },
                    { title: 'Request', description: 'Claim textile materials' },
                    { title: 'Create & Sell', description: 'List recycled products' }
                ],
                buyers: [
                    { title: 'Register', description: 'Create buyer account' },
                    { title: 'Explore', description: 'Browse recycled products' },
                    { title: 'Purchase', description: 'Buy sustainable items' },
                    { title: 'Support', description: 'Rate and review' }
                ]
            }
        }">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">How It Works</h2>

                <!-- Tab Navigation -->
                <div class="flex justify-center space-x-4 mb-12">
                    <button @click="activeTab = 'companies'"
                        :class="{ 'bg-green-600 text-white': activeTab === 'companies', 'bg-gray-100 text-gray-700': activeTab !== 'companies' }"
                        class="px-6 py-2 rounded-full font-semibold transition-all duration-200">
                        For Companies
                    </button>
                    <button @click="activeTab = 'artisans'"
                        :class="{ 'bg-green-600 text-white': activeTab === 'artisans', 'bg-gray-100 text-gray-700': activeTab !== 'artisans' }"
                        class="px-6 py-2 rounded-full font-semibold transition-all duration-200">
                        For Artisans
                    </button>
                    <button @click="activeTab = 'buyers'"
                        :class="{ 'bg-green-600 text-white': activeTab === 'buyers', 'bg-gray-100 text-gray-700': activeTab !== 'buyers' }"
                        class="px-6 py-2 rounded-full font-semibold transition-all duration-200">
                        For Buyers
                    </button>
                </div>

                <!-- Process Steps -->
                <div class="grid md:grid-cols-4 gap-8">
                    <template x-for="(step, index) in steps[activeTab]" :key="index">
                        <div class="text-center">
                            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl font-bold text-green-600" x-text="index + 1"></span>
                            </div>
                            <h4 class="font-semibold mb-2" x-text="step.title"></h4>
                            <p class="text-gray-600" x-text="step.description"></p>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <!-- Environmental Impact Section -->
        <section class="bg-green-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-8">Our Environmental Impact</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <div class="text-4xl font-bold mb-2">1000+</div>
                        <p>Tons of Textile Waste Saved</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">500+</div>
                        <p>Active Partnerships</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">2000+</div>
                        <p>Carbon Tons Reduced</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-6">Ready to Make a Difference?</h2>
                <p class="text-xl text-gray-600 mb-8">Join our growing community of sustainable businesses and artisans</p>
                <a href="{{ route('register') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700">Get
                    Started Today</a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} TexTurn Hub. All rights reserved.</p>
            </div>
        </footer>
    </div>
</div>
@endsection
