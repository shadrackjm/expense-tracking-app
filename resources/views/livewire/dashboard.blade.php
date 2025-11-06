<div class="min-h-screen bg-gray-50 dark:bg-neutral-900">
     <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                    <p class="text-purple-100 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center gap-4">
                    <button wire:click="previousMonth" class="p-2 bg-white/20 hover:bg-white/30 rounded-lg text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="text-white font-semibold">
                        {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}
                    </div>
                    <button wire:click="nextMonth" class="p-2 bg-white/20 hover:bg-white/30 rounded-lg text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
         <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
             <!-- Total Spent Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Spent</h3>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">${{ number_format($totalSpent, 2) }}</div>
                @if($monthlyBudget > 0)
                    <div class="mt-2 text-sm {{ $totalSpent > $monthlyBudget ? 'text-red-600' : 'text-green-600' }}">
                        {{ $totalSpent > $monthlyBudget ? 'Over' : 'Under' }} budget by ${{ number_format(abs($monthlyBudget - $totalSpent), 2) }}
                    </div>
                @endif
            </div>

             <!-- Budget Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Monthly Budget</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">${{ number_format($monthlyBudget, 2) }}</div>
                @if($monthlyBudget > 0)
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                            <span>{{ $percentageUsed }}% used</span>
                            <span>${{ number_format($monthlyBudget - $totalSpent, 2) }} left</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $percentageUsed > 100 ? 'bg-red-500' : ($percentageUsed > 80 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="width: {{ min($percentageUsed, 100) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Categories Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Categories</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $expenseByCategory->count() }}</div>
                <div class="mt-2 text-sm text-gray-600">Active spending categories</div>
            </div>
            <!-- Recurring Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Recurring</h3>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $recurringExpenseCount }}</div>
                <div class="mt-2 text-sm text-gray-600">Active subscriptions</div>
            </div>
        </div>
        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- monthly trend chart --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">6-Month Spending Trend</h3>
                <canvas id="monthlyTrendChart"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Spending by Category</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <!-- Top Categories & Recent Expenses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Categories -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Spending Categories</h3>
                <div class="space-y-4">
                    @forelse($topCategories as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                                     style="background-color: {{ $category->color }}20;">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }};"></div>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ round(($category->total / $totalSpent) * 100, 1) }}% of total
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-800">${{ number_format($category->total, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            No expenses yet this month
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Expenses</h3>
                    <a href="/expenses" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentExpenses as $expense)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3">
                                @if($expense->category)
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                                         style="background-color: {{ $expense->category->color }}20;">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $expense->category->color }};"></div>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-800">{{ $expense->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $expense->date->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-800">-${{ number_format($expense->amount, 2) }}</div>
                                @if($expense->is_auto_generated)
                                    <div class="text-xs text-orange-600">Auto</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            No expenses yet
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/expenses/create" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold">Add Expense</div>
                        <div class="text-sm text-purple-100">Record new expense</div>
                    </div>
                </div>
            </a>

            <a href="/recurring-expenses" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold">Recurring</div>
                        <div class="text-sm text-blue-100">Manage subscriptions</div>
                    </div>
                </div>
            </a>

            <a href="/categories" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold">Categories</div>
                        <div class="text-sm text-green-100">Organize expenses</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:navigated',function(){
            // monthly Trend chart
            const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyComparison->pluck('month')),
                    datasets: [{
                        label: 'Spending',
                        data: @json($monthlyComparison->pluck('total')),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(147, 51, 234)',
                    }] 
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
            // category Pie chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($expenseByCategory->pluck('name')),
                    datasets: [{
                        data: @json($expenseByCategory->pluck('total')),
                        backgroundColor: @json($expenseByCategory->pluck('color')),
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

        });
    </script>
</div>
