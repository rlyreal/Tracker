<?php
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIQ - Financial Intelligence</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'sans': ['Poppins', 'sans-serif'],
                },
                extend: {
                    colors: {
                        dark: {
                            DEFAULT: '#302D43',
                            card: '#3a3751'
                        },
                        primary: '#6953F7',
                        accent: '#CD46F7',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        income: '#dcfce7',
                        expense: '#fee2e2'
                    }
                }
            }
        }
    </script>
    <style>
        .progress-ring {
            transform: rotate(-90deg);
        }
        .chart-container {
            position: relative;
            height: 80px;
        }
        .electric-loading circle {
            stroke-linecap: round;
        }
        .electric-pulse {
            animation: electricPulse 1.5s ease-in-out infinite;
            filter: drop-shadow(0 0 8px currentColor);
        }
        .electric-dash {
            stroke-dasharray: 326.73;
            stroke-dashoffset: 326.73;
            animation: electricLoad 2s ease-in-out forwards;
        }
        @keyframes electricLoad {
            from {
                stroke-dashoffset: 326.73;
            }
            to {
                stroke-dashoffset: 0;
            }
        }
        @keyframes electricPulse {
            0% {
                filter: drop-shadow(0 0 2px currentColor);
                opacity: 0.6;
            }
            50% {
                filter: drop-shadow(0 0 12px currentColor) drop-shadow(0 0 25px currentColor);
                opacity: 1;
            }
            100% {
                filter: drop-shadow(0 0 2px currentColor);
                opacity: 0.6;
            }
        }
        .calc-btn {
            padding: 0.4rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            background-color: rgba(58, 55, 81, 0.3);
            transition: all 0.2s;
            min-width: 2rem;
        }
        .calc-btn:hover {
            background-color: rgba(74, 72, 95, 0.5);
        }
        #calculatorOverlay {
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-dark text-white">
    <div class="container mx-auto px-2 py-2 max-w-7xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="2" fill="none"/>
                        <text x="12" y="16" text-anchor="middle" font-size="14" fill="white" stroke="none" font-family="Poppins, Arial, sans-serif" font-weight="bold">₱</text>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Welcome back Real!</h1>
                    <p class="text-gray-400">Track your spending and savings</p>
                </div>
            </div>
            <div class="flex gap-4">
                <button id="calculatorBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10H9m3-5h3m-6 0h0m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                <button id="debtTrackerBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                <button class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>
                <button class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
                <!-- Calculator Overlay -->
                <div id="calculatorOverlay" class="hidden absolute top-16 right-4 bg-dark-card rounded-lg shadow-xl p-3 z-50">
                    <input type="text" id="calcDisplay" class="w-full mb-2 px-3 py-1 text-right text-lg bg-dark border border-gray-600 rounded-lg" readonly>
                    <div class="grid grid-cols-4 gap-1">
                        <button onclick="calcClear()" class="calc-btn bg-red-500/20 text-red-500 hover:bg-red-500/30">C</button>
                        <button onclick="calcAppend('7')" class="calc-btn">7</button>
                        <button onclick="calcAppend('8')" class="calc-btn">8</button>
                        <button onclick="calcAppend('9')" class="calc-btn">9</button>
                        <button onclick="calcAppend('4')" class="calc-btn">4</button>
                        <button onclick="calcAppend('5')" class="calc-btn">5</button>
                        <button onclick="calcAppend('6')" class="calc-btn">6</button>
                        <button onclick="calcAppend('+')" class="calc-btn bg-primary/20 text-primary hover:bg-primary/30">+</button>
                        <button onclick="calcAppend('1')" class="calc-btn">1</button>
                        <button onclick="calcAppend('2')" class="calc-btn">2</button>
                        <button onclick="calcAppend('3')" class="calc-btn">3</button>
                        <button onclick="calcAppend('-')" class="calc-btn bg-primary/20 text-primary hover:bg-primary/30">-</button>
                        <button onclick="calcAppend('0')" class="calc-btn">0</button>
                        <button onclick="calcAppend('.')" class="calc-btn">.</button>
                        <button onclick="calcEvaluate()" class="calc-btn bg-accent/20 text-accent hover:bg-accent/30">=</button>
                        <button onclick="calcAppend('*')" class="calc-btn bg-primary/20 text-primary hover:bg-primary/30">×</button>
                    </div>
                </div>

                <!-- Debt Tracker Overlay -->
                <div id="debtTrackerOverlay" class="hidden absolute top-16 right-4 bg-dark-card rounded-lg shadow-xl p-3 z-50 w-[340px]">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-medium">People who owe you</h3>
                        <button onclick="showAddDebtModal()" class="p-1.5 hover:bg-gray-700 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-2 max-h-[300px] overflow-y-auto" id="debtsList">
                        <!-- Debts will be loaded here dynamically -->
                    </div>
                </div>

                <!-- Add Debt Modal -->
                <div id="addDebtModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-dark-card rounded-xl p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4">Add New Debt</h3>
                        <form id="addDebtForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Debtor Name</label>
                                <input type="text" id="debtorName" name="debtor_name" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Amount</label>
                                <input type="number" id="debtAmount" name="amount" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" step="0.01" min="0.01" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                                <textarea id="debtDescription" name="description" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" rows="2"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Due Date (Optional)</label>
                                <input type="date" id="debtDueDate" name="due_date" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="hideAddDebtModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/90 transition">Add Debt</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 mb-1">
            <?php
            // Get current month totals
            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');
            
            // Get total income and expenses for current month
            $stmt = $conn->prepare("SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expenses
                FROM transactions
                WHERE transaction_date BETWEEN :start AND :end");
            $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
            $totals = $stmt->fetch();
            
            $totalIncome = $totals['total_income'];
            $totalExpenses = $totals['total_expenses'];
            ?>
            <!-- Spending Overview Card -->
            <div class="bg-dark-card rounded-lg p-3 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-base font-semibold">Monthly Overview</h2>
                    <span class="text-xs text-gray-400"><?php echo date('F Y'); ?></span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Income Circle -->
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-2">
                            <svg class="progress-ring electric-loading" width="100%" height="100%" viewBox="0 0 120 120">
                                <defs>
                                    <filter id="income-glow">
                                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <circle class="text-gray-700/20" stroke="currentColor" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                <circle class="text-success electric-pulse electric-dash" stroke="currentColor" stroke-width="8" 
                                    fill="transparent" r="52" cx="60" cy="60" filter="url(#income-glow)"/>
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span class="text-2xl font-bold text-success">₱<?php echo number_format($totalIncome, 0); ?></span>
                                <p class="text-xs text-gray-400">Total Income</p>
                            </div>
                        </div>
                        <div class="bg-dark/50 rounded-lg p-2">
                            <h3 class="text-sm font-medium text-success">Income Total</h3>
                            <p class="text-xs text-gray-400">Resets Monthly</p>
                        </div>
                    </div>
                    
                    <!-- Expense Circle -->
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-2">
                            <svg class="progress-ring electric-loading" width="100%" height="100%" viewBox="0 0 120 120">
                                <defs>
                                    <filter id="expense-glow">
                                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <circle class="text-gray-700/20" stroke="currentColor" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                <circle class="text-red-500 electric-pulse electric-dash" stroke="currentColor" stroke-width="8" 
                                    fill="transparent" r="52" cx="60" cy="60" filter="url(#expense-glow)"/>
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span class="text-2xl font-bold text-red-500">₱<?php echo number_format($totalExpenses, 0); ?></span>
                                <p class="text-xs text-gray-400">Total Expenses</p>
                            </div>
                        </div>
                        <div class="bg-dark/50 rounded-lg p-2">
                            <h3 class="text-sm font-medium text-red-500">Expense Total</h3>
                            <p class="text-xs text-gray-400">Resets Monthly</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashflow Card -->
            <div class="bg-dark-card rounded-xl p-3 shadow-lg">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-base font-semibold">My Cashflow</h2>
                    <div class="relative">
                        <button id="refreshCashflow" class="p-1.5 hover:bg-gray-700 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-400" id="monthlyProgress">TOTAL BALANCE</p>
                            <p class="text-3xl font-bold text-white" id="netBalance">₱33,125.22</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-700/30 rounded-full h-1">
                        <div id="progressBar" class="bg-primary h-1 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Today's Transactions</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-dark/50 p-3 rounded-lg">
                            <div>
                                <p class="font-medium text-success">Today's Income</p>
                                <p class="text-sm text-gray-400" id="todayDate"></p>
                            </div>
                            <span id="todayIncome" class="text-lg font-bold text-success">₱0.00</span>
                        </div>
                        <div class="flex justify-between items-center bg-dark/50 p-3 rounded-lg">
                            <div>
                                <p class="font-medium text-red-500">Today's Expenses</p>
                                <p class="text-sm text-gray-400" id="remainingBudget">Deducted from Balance</p>
                            </div>
                            <span id="todayExpenses" class="text-lg font-bold text-red-500">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Goals Card -->
            <div class="bg-dark-card rounded-xl p-3 shadow-lg">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-base font-semibold">Invest in My Future</h2>
                    <div class="flex gap-2">
                        <button onclick="showAddProfitModal()" class="p-1.5 hover:bg-gray-700 rounded-lg transition text-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="chart-container mb-4" style="height: 60px;">
                    <canvas id="investmentChart"></canvas>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-xs text-gray-400">Investment Period</p>
                            <p class="text-sm font-bold">Apr 25 - Oct 25, 2025</p>
                        </div>
                        <div class="bg-primary text-white px-2 py-0.5 rounded-full text-xs">
                            6% APR
                        </div>
                    </div>
                    <div class="space-y-3">
                        <!-- First Investment -->
                        <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                            <div>
                                <p class="text-xs text-gray-400">Personal Investment</p>
                                <p class="text-lg font-bold">₱15,000.00</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <span id="investment1Profit" class="text-green-500 text-sm">+₱194.18</span>
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Second Investment -->
                        <div class="flex justify-between items-start pb-2">
                            <div>
                                <p class="text-xs text-gray-400">Mother's Business</p>
                                <p class="text-lg font-bold">₱9,000.00</p>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-end gap-1">
                                    <span id="investment2Profit" class="text-green-500 text-sm">+₱7,500.00</span>
                                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                </div>
                                <div class="flex gap-2">
                                    <input type="number" id="quickProfitAmount" class="w-24 px-2 py-1 text-xs rounded bg-dark text-white border border-gray-600" placeholder="Enter amount">
                                    <button onclick="addQuickProfit()" class="p-1 hover:bg-gray-700 rounded-lg transition text-success">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Background Card -->
            <div class="bg-dark-card rounded-xl overflow-hidden shadow-lg relative flex-1" style="min-height: 300px;">
                <!-- Audio controls removed -->
                <video 
                    id="backgroundVideo"
                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 min-w-full min-h-full w-auto h-auto object-contain"
                    autoplay
                    loop
                    muted
                    playsinline>
                    <source src="assets/background.mp4?v=<?php echo time(); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <!-- Audio element removed -->
                <div class="absolute inset-0 bg-dark/10"></div>
            </div>

            <!-- Income & Expense Overview Cards -->
            <div class="col-span-2 grid grid-cols-2 gap-2">
                <!-- Income Card -->
                <div class="bg-dark-card rounded-lg p-2 shadow-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-sm font-semibold">Income</h2>
                        <div class="relative">
                            <button onclick="switchView('income')" id="incomeToggle" 
                                    class="px-2 py-1 rounded text-xs font-medium transition-all duration-200 focus:outline-none hover:bg-gray-700">
                                View
                            </button>
                        </div>
                    </div>
                    <div id="incomeCard" class="bg-income rounded shadow-md p-2">
                        <div class="flex justify-between items-center">
                            <h5 class="text-gray-600 font-medium text-xs">Today:</h5>
                            <h3 class="text-lg font-bold text-green-700" id="totalIncome">$0.00</h3>
                        </div>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="bg-dark-card rounded-lg p-2 shadow-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-sm font-semibold">Expenses</h2>
                        <div class="relative">
                            <button onclick="switchView('expense')" id="expenseToggle"
                                    class="px-2 py-1 rounded text-xs font-medium transition-all duration-200 focus:outline-none hover:bg-gray-700">
                                View
                            </button>
                        </div>
                    </div>
                    <div id="expenseCard" class="bg-expense rounded shadow-md p-2">
                        <div class="flex justify-between items-center">
                            <h5 class="text-gray-600 font-medium text-xs">Today:</h5>
                            <h3 class="text-lg font-bold text-red-700" id="totalExpenses">$0.00</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Switch View Card -->
            <div class="col-span-2 bg-dark-card rounded-xl p-4 shadow-lg">
                <div class="flex justify-between items-start mb-3">
                    <h2 class="text-lg font-semibold">Quick Switch</h2>
                </div>
                <div class="flex bg-dark rounded-lg p-1">
                    <button onclick="switchView('income')" 
                            class="flex-1 px-2 py-1 rounded-md text-xs font-medium transition-all duration-200 focus:outline-none">
                        Income
                    </button>
                    <button onclick="switchView('expense')"
                            class="flex-1 px-2 py-1 rounded-md text-xs font-medium transition-all duration-200 focus:outline-none">
                        Expenses
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Transaction Card -->
        <div class="bg-dark-card rounded-xl shadow-lg p-4 mb-4">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-lg font-semibold">Add New Transaction</h2>
                <div class="relative">
                    <button class="p-2 hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="transactionForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <select id="incomeSelect" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none hidden">
                            <option value="">Select Income Source</option>
                            <option value="Adopt Me">Adopt Me</option>
                            <option value="Grow a Garden">Grow a Garden</option>
                            <option value="PS99">PS99</option>
                            <option value="MIDMAN fee">MIDMAN fee</option>
                        </select>
                        <select id="expenseSelect" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none hidden">
                            <option value="">Select Expense Type</option>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Wants/Needs/Fee">Wants/Needs/Fee</option>
                        </select>
                    </div>
                    <input type="number" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                           id="amount" placeholder="Amount" step="0.01" required>
                    <div class="flex gap-2">
                        <input type="hidden" id="type" value="income">
                        <button type="submit" 
                                class="w-full bg-success text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200">
                            Add Transaction
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Transactions Card -->
        <div class="bg-dark-card rounded-xl shadow-lg p-4 mb-4">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-lg font-semibold">Recent Transactions</h2>
                <div class="relative">
                    <button class="p-2 hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-card">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="transactionsList" class="bg-dark-card divide-y divide-gray-700">
                        <!-- Transactions will be added here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        // Initialize investment chart
        const ctx = document.getElementById('investmentChart').getContext('2d');
        const investmentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Apr 25', 'May 25', 'Jun 25', 'Jul 25', 'Aug 23', 'Oct 25'],
                datasets: [{
                    label: 'Personal Investment',
                    data: [15000, 15048.55, 15097.10, 15145.64, 15194.18, 15194.18],
                    borderColor: '#6953F7',
                    backgroundColor: 'rgba(105, 83, 247, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Mother\'s Business',
                    data: [9000, 9000, 9000, 9000, 9000, 9000],
                    borderColor: '#CD46F7',
                    backgroundColor: 'rgba(205, 70, 247, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toLocaleString('en-PH', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#9ca3af',
                            callback: function(value) {
                                return '₱' + value.toLocaleString('en-PH');
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });

        function updateBalance() {
            fetch('get_today_totals.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalIncome').textContent = `₱${parseFloat(data.income).toFixed(2)}`;
                document.getElementById('totalExpenses').textContent = `₱${parseFloat(data.expenses).toFixed(2)}`;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function displayTransactions() {
            fetch(`get_transactions.php?type=${currentView}`)
            .then(response => response.json())
            .then(transactions => {
                const tbody = document.getElementById('transactionsList');
                tbody.innerHTML = '';
                
                transactions.forEach(transaction => {
                    const row = document.createElement('tr');
                    const colorClass = currentView === 'income' ? 'text-green-500' : 'text-red-500';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">${transaction.category_name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm ${colorClass} font-semibold">₱${parseFloat(transaction.amount).toFixed(2)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-400">${transaction.transaction_date}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="deleteTransaction(${transaction.id})" 
                                    class="text-red-500 hover:text-red-400 transition-colors duration-200 focus:outline-none">
                                Delete
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function deleteTransaction(id) {
            if (!confirm('Are you sure you want to delete this transaction?')) {
                return;
            }
            
            fetch('delete_transaction.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    updateBalance();
                    displayTransactions();
                } else {
                    alert('Error deleting transaction: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting transaction. Please try again.');
            });
        }

        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const description = document.getElementById('type').value === 'income' 
                ? document.getElementById('incomeSelect').value 
                : document.getElementById('expenseSelect').value;
            const amount = document.getElementById('amount').value;
            const type = document.getElementById('type').value;
            
            // Send data to PHP backend
            fetch('save_transaction.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `type=${type}&amount=${amount}&category=${description}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    this.reset();
                    updateBalance();
                    displayTransactions();
                } else {
                    alert('Error saving transaction: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving transaction. Please try again.');
            });
        });

        let currentView = 'income';

        function switchView(type) {
            currentView = type;
            // Update toggle buttons
            const incomeToggle = document.getElementById('incomeToggle');
            const expenseToggle = document.getElementById('expenseToggle');
            const incomeCard = document.getElementById('incomeCard');
            const expenseCard = document.getElementById('expenseCard');
            const addButton = document.querySelector('button[type="submit"]');
            const typeInput = document.getElementById('type');
            const incomeSelect = document.getElementById('incomeSelect');
            const expenseSelect = document.getElementById('expenseSelect');

            if (type === 'income') {
                // Update button styles
                incomeToggle.classList.add('bg-success/20', 'text-success');
                expenseToggle.classList.remove('bg-danger/20', 'text-danger');
                // Show/hide cards
                incomeCard.classList.remove('hidden');
                expenseCard.classList.add('hidden');
                // Show income select, hide expense select
                incomeSelect.classList.remove('hidden');
                incomeSelect.required = true;
                expenseSelect.classList.add('hidden');
                expenseSelect.required = false;
                // Update form
                addButton.classList.remove('bg-danger', 'hover:bg-red-600');
                addButton.classList.add('bg-success', 'hover:bg-green-600');
                typeInput.value = 'income';
            } else {
                // Update button styles
                expenseToggle.classList.add('bg-danger/20', 'text-danger');
                incomeToggle.classList.remove('bg-success/20', 'text-success');
                // Show/hide cards
                expenseCard.classList.remove('hidden');
                incomeCard.classList.add('hidden');
                // Show expense select, hide income select
                expenseSelect.classList.remove('hidden');
                expenseSelect.required = true;
                incomeSelect.classList.add('hidden');
                incomeSelect.required = false;
                // Update form
                addButton.classList.remove('bg-success', 'hover:bg-green-600');
                addButton.classList.add('bg-danger', 'hover:bg-red-600');
                typeInput.value = 'expense';
            }
            
            // Update transactions display
            displayTransactions();
        }

        // Initial display
        updateBalance();
        displayTransactions();
        // Initialize toggle view
        switchView('income');

        // Cashflow card functionality
        function updateCashflowCard() {
            // Base balance
            const baseBalance = 42774.8; // Updated: Added 5000 to previous balance of 37774.8

            // Update today's date
            const today = new Date();
            document.getElementById('todayDate').textContent = today.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            // Fetch monthly totals
            fetch('get_today_totals.php')
            .then(response => response.json())
            .then(data => {
                const income = parseFloat(data.income) || 0;
                const expenses = parseFloat(data.expenses) || 0;
                const totalBalance = baseBalance + income - expenses;

                // Update the display
                document.getElementById('todayIncome').textContent = `₱${income.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                document.getElementById('todayExpenses').textContent = `₱${expenses.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                document.getElementById('netBalance').textContent = `₱${totalBalance.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                
                // Update progress information
                document.getElementById('monthlyProgress').textContent = `Current Balance`;
                document.getElementById('progressBar').style.width = '100%';
                document.getElementById('remainingBudget').textContent = `Total Balance`;
            })
            .catch(error => {
                console.error('Error updating cashflow:', error);
            });
        }

        // Update cashflow on load
        updateCashflowCard();

        // Add refresh button functionality
        document.getElementById('refreshCashflow').addEventListener('click', updateCashflowCard);

        // Update cashflow card every 5 minutes
        setInterval(updateCashflowCard, 300000);

        // Video and Audio Controls
        const video = document.getElementById('backgroundVideo');

        // Thunder timing code removed

        // Audio controls removed

        // Overlay functionality
        const calculatorBtn = document.getElementById('calculatorBtn');
        const calculatorOverlay = document.getElementById('calculatorOverlay');
        const debtTrackerBtn = document.getElementById('debtTrackerBtn');
        const debtTrackerOverlay = document.getElementById('debtTrackerOverlay');
        const calcDisplay = document.getElementById('calcDisplay');
        
        let isCalculatorOpen = false;
        let isDebtTrackerOpen = false;

        calculatorBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isDebtTrackerOpen) {
                debtTrackerOverlay.classList.add('hidden');
                isDebtTrackerOpen = false;
            }
            isCalculatorOpen = !isCalculatorOpen;
            calculatorOverlay.classList.toggle('hidden');
            if (isCalculatorOpen) {
                calcDisplay.value = '';
            }
        });

        debtTrackerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isCalculatorOpen) {
                calculatorOverlay.classList.add('hidden');
                isCalculatorOpen = false;
            }
            isDebtTrackerOpen = !isDebtTrackerOpen;
            debtTrackerOverlay.classList.toggle('hidden');
            if (isDebtTrackerOpen) {
                loadDebts();
            }
        });

        // Close overlays when clicking outside
        document.addEventListener('click', (e) => {
            if (isCalculatorOpen && !calculatorOverlay.contains(e.target) && !calculatorBtn.contains(e.target)) {
                isCalculatorOpen = false;
                calculatorOverlay.classList.add('hidden');
            }
            if (isDebtTrackerOpen && !debtTrackerOverlay.contains(e.target) && !debtTrackerBtn.contains(e.target) && !e.target.closest('#addDebtModal')) {
                isDebtTrackerOpen = false;
                debtTrackerOverlay.classList.add('hidden');
            }
        });

        function calcAppend(value) {
            calcDisplay.value += value;
        }

        function calcClear() {
            calcDisplay.value = '';
        }

        function calcEvaluate() {
            try {
                const result = eval(calcDisplay.value.replace('×', '*'));
                calcDisplay.value = Number.isInteger(result) ? result : parseFloat(result.toFixed(2));
            } catch (error) {
                calcDisplay.value = 'Error';
                setTimeout(calcClear, 1000);
            }
        }

        // Add keyboard support for calculator when it's open
        document.addEventListener('keydown', (e) => {
            if (!isCalculatorOpen) return;
            
            if (/[0-9+\-*./]/.test(e.key)) {
                e.preventDefault();
                calcAppend(e.key === '*' ? '×' : e.key);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                calcEvaluate();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                calcClear();
            } else if (e.key === 'Backspace') {
                e.preventDefault();
                calcDisplay.value = calcDisplay.value.slice(0, -1);
            }
        });

        // Debt tracking functionality
        let currentTab = 'calculator';

        function switchTab(tab) {
            currentTab = tab;
            const calcPanel = document.getElementById('calculatorPanel');
            const debtsPanel = document.getElementById('debtsPanel');
            const calcTab = document.getElementById('calcTab');
            const debtsTab = document.getElementById('debtsTab');

            if (tab === 'calculator') {
                calcPanel.classList.remove('hidden');
                debtsPanel.classList.add('hidden');
                calcTab.classList.add('text-primary', 'border-primary');
                debtsTab.classList.remove('text-primary', 'border-primary');
            } else {
                calcPanel.classList.add('hidden');
                debtsPanel.classList.remove('hidden');
                calcTab.classList.remove('text-primary', 'border-primary');
                debtsTab.classList.add('text-primary', 'border-primary');
                loadDebts();
            }
        }

        // Initialize with calculator tab
        switchTab('calculator');

        function showAddDebtModal() {
            document.getElementById('addDebtModal').classList.remove('hidden');
        }

        function hideAddDebtModal() {
            document.getElementById('addDebtModal').classList.add('hidden');
            document.getElementById('addDebtForm').reset();
        }

        function loadDebts() {
            fetch('manage_debts.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const debtsList = document.getElementById('debtsList');
                        debtsList.innerHTML = '';

                        if (data.debts.length === 0) {
                            debtsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No debts recorded</p>';
                            return;
                        }

                        data.debts.forEach(debt => {
                            const dueDate = debt.due_date ? new Date(debt.due_date).toLocaleDateString('en-PH', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : 'No due date';
                            
                            const createdDate = new Date(debt.created_at).toLocaleDateString('en-PH', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            
                            const isPaid = debt.status === 'paid';
                            
                            const debtEl = document.createElement('div');
                            debtEl.className = 'bg-dark/50 p-3 rounded-lg mb-2';
                            debtEl.innerHTML = `
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium ${isPaid ? 'text-gray-500 line-through' : 'text-white'}">${debt.debtor_name}</h4>
                                        <p class="text-xs text-gray-400">${debt.description || 'No description'}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold ${isPaid ? 'text-gray-500' : 'text-primary'}">₱${parseFloat(debt.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                                        <div class="text-xs text-gray-400">Added: ${createdDate}</div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-xs border-t border-gray-700 pt-2">
                                    <span class="text-gray-400">Due: ${dueDate}</span>
                                    <div class="flex gap-2">
                                        ${!isPaid ? `
                                            <button onclick="markDebtAsPaid(${debt.id})" class="text-success hover:text-success/80 transition">
                                                <span>Mark as Paid</span>
                                            </button>
                                        ` : `
                                            <span class="text-success">✓ Paid</span>
                                        `}
                                        <button onclick="deleteDebt(${debt.id})" class="text-red-500 hover:text-red-400 transition">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            `;
                            debtsList.appendChild(debtEl);
                        });
                    }
                })
                .catch(error => console.error('Error loading debts:', error));
        }

        document.getElementById('addDebtForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            // Get form values
            const debtorName = document.getElementById('debtorName').value.trim();
            const amount = document.getElementById('debtAmount').value;
            const description = document.getElementById('debtDescription').value.trim();
            const dueDate = document.getElementById('debtDueDate').value;

            // Validate form
            if (!debtorName || !amount) {
                alert('Please fill in required fields (Debtor Name and Amount)');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('debtor_name', debtorName);
            formData.append('amount', amount);
            formData.append('description', description);
            formData.append('due_date', dueDate);
            
            console.log('Sending debt data:', {
                action: 'add',
                debtor_name: debtorName,
                amount: amount,
                description: description,
                due_date: dueDate
            });

            // Send the data to manage_debts.php
            fetch('manage_debts.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Raw server response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed response:', data);
                    
                    if (data.success) {
                        alert('Debt added successfully!');
                        document.getElementById('addDebtForm').reset();
                        hideAddDebtModal();
                        
                        // Reload debts list
                        fetch('manage_debts.php')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const debtsList = document.getElementById('debtsList');
                                    debtsList.innerHTML = '';
                                    
                                    if (data.debts.length === 0) {
                                        debtsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No debts recorded</p>';
                                        return;
                                    }
                                    
                                    data.debts.forEach(debt => {
                                        const dueDate = debt.due_date ? new Date(debt.due_date).toLocaleDateString() : 'No due date';
                                        const isPaid = debt.status === 'paid';
                                        
                                        const debtEl = document.createElement('div');
                                        debtEl.className = 'bg-dark/50 p-3 rounded-lg mb-2';
                                        debtEl.innerHTML = `
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h4 class="font-medium ${isPaid ? 'text-gray-500 line-through' : 'text-white'}">${debt.debtor_name}</h4>
                                                    <p class="text-xs text-gray-400">${debt.description || 'No description'}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-bold ${isPaid ? 'text-gray-500' : 'text-primary'}">₱${parseFloat(debt.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                                                    <div class="text-xs text-gray-400">Added: ${new Date(debt.created_at).toLocaleDateString()}</div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-center text-xs border-t border-gray-700 pt-2">
                                                <span class="text-gray-400">Due: ${dueDate}</span>
                                                <div class="flex gap-2">
                                                    ${!isPaid ? `
                                                        <button onclick="markDebtAsPaid(${debt.id})" class="text-success hover:text-success/80 transition">
                                                            Mark as Paid
                                                        </button>
                                                    ` : `
                                                        <span class="text-success">✓ Paid</span>
                                                    `}
                                                    <button onclick="deleteDebt(${debt.id})" class="text-red-500 hover:text-red-400 transition">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                                        debtsList.appendChild(debtEl);
                                    });
                                }
                            })
                            .catch(error => console.error('Error loading debts:', error));
                    } else {
                        alert('Error adding debt: ' + (data.message || 'Unknown error'));
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Error processing server response: ' + text);
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                alert('Error connecting to server. Please try again.');
            });
        });

        function markDebtAsPaid(id) {
            if (!confirm('Mark this debt as paid?')) return;

            const formData = new FormData();
            formData.append('action', 'mark_paid');
            formData.append('id', id);

            fetch('manage_debts.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadDebts();
                } else {
                    alert('Error updating debt: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating debt. Please try again.');
            });
        }

        function deleteDebt(id) {
            if (!confirm('Are you sure you want to delete this debt?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch('manage_debts.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadDebts();
                } else {
                    alert('Error deleting debt: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting debt. Please try again.');
            });
        }

        // Add Profit Modal
        const addProfitModal = document.createElement('div');
        addProfitModal.id = 'addProfitModal';
        addProfitModal.className = 'hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        addProfitModal.innerHTML = `
            <div class="bg-dark-card rounded-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Update Investment Profit</h3>
                <form id="addProfitForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Select Investment</label>
                        <select id="investmentSelect" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="1">Personal Investment</option>
                            <option value="2">Mother's Business</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Profit Amount</label>
                        <input type="number" id="profitAmount" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" step="0.01" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideAddProfitModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-success hover:bg-success/90 transition">Update Profit</button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(addProfitModal);

        function showAddProfitModal(investmentId) {
            document.getElementById('addProfitModal').classList.remove('hidden');
            document.getElementById('profitAmount').value = '';
            document.getElementById('investmentSelect').value = investmentId;
        }

        function hideAddProfitModal() {
            document.getElementById('addProfitModal').classList.add('hidden');
            document.getElementById('addProfitForm').reset();
        }

        function addQuickProfit() {
            const amount = parseFloat(document.getElementById('quickProfitAmount').value);
            
            if (!amount || isNaN(amount)) {
                alert('Please enter a valid amount');
                return;
            }
            
            // Update profit display
            investment2Profit += amount;
            document.getElementById('investment2Profit').textContent = `+₱${investment2Profit.toFixed(2)}`;
            
            // Update chart data for investment 2
            const newData = investmentChart.data.datasets[1].data.map(value => value + amount);
            investmentChart.data.datasets[1].data = newData;
            investmentChart.update();
            
            // Clear input
            document.getElementById('quickProfitAmount').value = '';
        }

        // Investment profit tracking
        let investment1Profit = 194.18;  // Updated profit as of Aug 23, 2025
        let investment2Profit = 7500;  // Initialize with current profit

        document.getElementById('addProfitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const investmentId = document.getElementById('investmentSelect').value;
            const profitAmount = parseFloat(document.getElementById('profitAmount').value);
            
            if (investmentId === '1') {
                investment1Profit += profitAmount;
                document.getElementById('investment1Profit').textContent = `+₱${investment1Profit.toFixed(2)}`;
                
                // Update chart data for investment 1
                const newData = investmentChart.data.datasets[0].data.map(value => value + profitAmount);
                investmentChart.data.datasets[0].data = newData;
            } else {
                investment2Profit += profitAmount;
                document.getElementById('investment2Profit').textContent = `+₱${investment2Profit.toFixed(2)}`;
                
                // Update chart data for investment 2
                const newData = investmentChart.data.datasets[1].data.map(value => value + profitAmount);
                investmentChart.data.datasets[1].data = newData;
            }
            
            investmentChart.update();
            hideAddProfitModal();
        });
    </script>
</body>
</html>
