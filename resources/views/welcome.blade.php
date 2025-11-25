<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseFlow - Smart Expense Tracking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out forwards;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-sm z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold gradient-text">ExpenseFlow</span>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-purple-600 transition">How It Works</a>
                    <a href="#pricing" class="text-gray-700 hover:text-purple-600 transition">Pricing</a>
                    <a href="/login" class="text-gray-700 hover:text-purple-600 transition">Login</a>
                    <a href="/register" class="gradient-bg text-white px-6 py-2 rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Get Started
                    </a>
                </div>

                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="#features" class="block text-gray-700 hover:text-purple-600">Features</a>
                <a href="#how-it-works" class="block text-gray-700 hover:text-purple-600">How It Works</a>
                <a href="#pricing" class="block text-gray-700 hover:text-purple-600">Pricing</a>
                <a href="/login" class="block text-gray-700 hover:text-purple-600">Login</a>
                <a href="/register" class="block gradient-bg text-white px-6 py-2 rounded-lg text-center">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 gradient-bg overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column -->
                <div class="text-white space-y-8">
                    <div class="animate-fade-in-up" style="animation-delay: 0.1s; opacity: 0;">
                        <span class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-4">
                            ⚡ Powered by TALL Stack
                        </span>
                        <h1 class="text-5xl sm:text-6xl font-bold leading-tight">
                            Take Control of Your
                            <span class="block mt-2">Finances Today</span>
                        </h1>
                    </div>
                    
                    <p class="text-xl text-purple-100 animate-fade-in-up" style="animation-delay: 0.3s; opacity: 0;">
                        Track expenses, manage budgets, and achieve your financial goals with our beautiful and powerful expense tracker built with Laravel, Livewire, Alpine.js, and Tailwind CSS.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: 0.5s; opacity: 0;">
                        <a href="/register" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
                            Start Free Trial
                        </a>
                        <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition text-center">
                            Learn More
                        </a>
                    </div>

                    <div class="flex items-center gap-8 pt-4 animate-fade-in-up" style="animation-delay: 0.7s; opacity: 0;">
                        <div>
                            <div class="text-3xl font-bold">10K+</div>
                            <div class="text-purple-200 text-sm">Active Users</div>
                        </div>
                        <div class="h-12 w-px bg-purple-300"></div>
                        <div>
                            <div class="text-3xl font-bold">$2M+</div>
                            <div class="text-purple-200 text-sm">Tracked</div>
                        </div>
                        <div class="h-12 w-px bg-purple-300"></div>
                        <div>
                            <div class="text-3xl font-bold">4.9★</div>
                            <div class="text-purple-200 text-sm">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Dashboard Preview -->
                <div class="relative animate-slide-in-right" style="animation-delay: 0.4s; opacity: 0;">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-2xl transform rotate-3"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl p-6 animate-float">
                        <!-- Mini Dashboard Preview -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-800">This Month</h3>
                                <span class="text-sm text-gray-500">October 2025</span>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
                                <div class="text-sm opacity-90 mb-2">Total Spent</div>
                                <div class="text-4xl font-bold">$3,247.85</div>
                                <div class="text-sm mt-2 flex items-center">
                                    <span class="text-green-300">↓ 12% from last month</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-red-50 rounded-lg p-4">
                                    <div class="text-xs text-gray-600 mb-1">Food</div>
                                    <div class="text-xl font-bold text-gray-800">$847</div>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="text-xs text-gray-600 mb-1">Transport</div>
                                    <div class="text-xl font-bold text-gray-800">$234</div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">🍕 Pizza Night</span>
                                    <span class="font-semibold text-gray-800">-$45.00</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">⛽ Gas Station</span>
                                    <span class="font-semibold text-gray-800">-$60.00</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">☕ Coffee Shop</span>
                                    <span class="font-semibold text-gray-800">-$12.50</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600">Everything you need to manage your expenses efficiently</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Interactive Dashboard</h3>
                    <p class="text-gray-600">Beautiful charts and real-time statistics to visualize your spending patterns.</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Recurring Expenses</h3>
                    <p class="text-gray-600">Set up automatic recurring expenses that are generated based on your schedule.</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Budget Management</h3>
                    <p class="text-gray-600">Set budgets for categories and get alerts when you're approaching limits.</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Real-time Updates</h3>
                    <p class="text-gray-600">Livewire-powered interface updates instantly without page refreshes.</p>
                </div>

                <div class="bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-pink-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Category Tags</h3>
                    <p class="text-gray-600">Organize expenses with customizable categories and color-coded tags.</p>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Mobile Responsive</h3>
                    <p class="text-gray-600">Track expenses on any device with our fully responsive design.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TALL Stack Showcase -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Built with the TALL Stack</h2>
                <p class="text-xl text-gray-600">Modern, powerful, and elegant technology stack</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                        T
                    </div>
                    <h3 class="text-xl font-bold mb-2">Tailwind CSS</h3>
                    <p class="text-gray-600">Utility-first CSS for rapid UI development</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                        A
                    </div>
                    <h3 class="text-xl font-bold mb-2">Alpine.js</h3>
                    <p class="text-gray-600">Lightweight JavaScript for interactivity</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-red-400 to-orange-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                        L
                    </div>
                    <h3 class="text-xl font-bold mb-2">Laravel</h3>
                    <p class="text-gray-600">The PHP framework for web artisans</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-pink-400 to-purple-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                        L
                    </div>
                    <h3 class="text-xl font-bold mb-2">Livewire</h3>
                    <p class="text-gray-600">Dynamic interfaces without leaving PHP</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 gradient-bg">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-4xl font-bold mb-6">Ready to Take Control of Your Finances?</h2>
            <p class="text-xl mb-8 text-purple-100">Join thousands of users who are already tracking their expenses smarter.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:shadow-2xl transition transform hover:-translate-y-1">
                    Start Your Free Trial
                </a>
                <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition">
                    View Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold">ExpenseFlow</span>
                    </div>
                    <p class="text-gray-400">Smart expense tracking for everyone.</p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Privacy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms</a></li>
                        <li><a href="#" class="hover:text-white transition">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2025 ExpenseFlow. Built with the TALL Stack. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
