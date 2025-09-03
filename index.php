<?php
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real - Budget Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'sans': ['Poppins', 'sans-serif'],
                },
                extend: {
                    colors: {
                        dark: {
                            DEFAULT: '#0f1123',
                            card: '#171b35'
                        },
                        primary: '#7C3AED',
                        accent: '#F471FF',
                        success: '#00FF9D',
                        warning: '#FFB800',
                        danger: '#FF3D57',
                        income: '#dcfce7',
                        expense: '#fee2e2',
                        neon: {
                            blue: '#4DFFFF',
                            purple: '#9D4EDD',
                            pink: '#FF49DB'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary: #7C3AED;
            --accent: #F471FF;
        }
        
        /* Theme Colors */
        .btn-primary {
            background-color: var(--primary);
        }
        .text-primary {
            color: var(--primary);
        }
        .border-primary {
            border-color: var(--primary);
        }
        .btn-accent {
            background-color: var(--accent);
        }
        .text-accent {
            color: var(--accent);
        }

        /* Animation Settings */
        body:not(.reduce-animations) .animate-fade {
            transition: opacity 0.3s ease-in-out;
        }
        body:not(.reduce-animations) .animate-slide {
            transition: transform 0.3s ease-in-out;
        }
        body.reduce-animations * {
            transition: none !important;
        }

        /* Compact View */
        body.compact-view .card {
            padding: 0.75rem !important;
        }
        body.compact-view .text-lg {
            font-size: 1rem;
        }
        body.compact-view .text-xl {
            font-size: 1.1rem;
        }
        body.compact-view .text-2xl {
            font-size: 1.25rem;
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .chart-container {
            position: relative;
            height: 80px;
        }
        .pulse-wave {
            stroke: #7C3AED;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: pulse-wave 15s linear infinite;
            stroke-linecap: round;
            stroke-linejoin: round;
            filter: drop-shadow(0 0 2px #7C3AED);
        }
        @keyframes pulse-wave {
            to {
                stroke-dashoffset: -1000;
            }
        }
        .electric-loading circle {
            stroke-linecap: round;
        }
        
        /* Card hover effects */
        .hover-card {
            transition: all 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(105, 83, 247, 0.2);
        }
        
        /* Number animation */
        @keyframes countup {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .animate-number {
            animation: countup 0.5s ease-out forwards;
        }
        
        /* Button hover effects */
        .hover-button {
            transition: all 0.2s ease;
        }
        .hover-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px -2px rgba(105, 83, 247, 0.3);
        }
        
        /* Smooth card transition */
        .card-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* Gamer Theme Enhancements */
        .bg-dark-card {
            background: linear-gradient(135deg, #171b35 0%, #1a1f3c 100%);
            border: 1px solid rgba(124, 58, 237, 0.1);
            box-shadow: 0 0 15px rgba(124, 58, 237, 0.1);
        }

        .hover-card {
            position: relative;
            overflow: hidden;
        }

        .hover-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(124, 58, 237, 0.1),
                transparent
            );
            transition: 0.5s;
        }

        .hover-card:hover::before {
            left: 100%;
        }

        .neon-border {
            position: relative;
        }

        .neon-border::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .neon-border:hover::after {
            opacity: 1;
        }

        .cyber-button {
            position: relative;
            background: linear-gradient(135deg, #7C3AED 0%, #F471FF 100%);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .cyber-button::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            background: #171b35;
            border-radius: inherit;
            z-index: 1;
        }

        .cyber-button span {
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #7C3AED 0%, #F471FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cyber-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(124, 58, 237, 0.5);
        }

        .cyber-button:hover span {
            background: linear-gradient(135deg, #F471FF 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .neon-text {
            color: #4DFFFF;
            text-shadow: 0 0 5px rgba(77, 255, 255, 0.5);
        }

        .neon-text-success {
            color: #00FF9D;
            text-shadow: 0 0 5px rgba(0, 255, 157, 0.5);
        }

        .neon-text-danger {
            color: #FF3D57;
            text-shadow: 0 0 5px rgba(255, 61, 87, 0.5);
        }

        /* Animated Background Effect */
        /* Smart Card Styles */
        .smart-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .smart-card.minimized {
            height: 3rem !important;
            overflow: hidden;
        }

        .smart-card .card-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .smart-card.minimized .card-content {
            opacity: 0;
            transform: translateY(-20px);
        }

        .smart-card .card-header {
            cursor: move;
            user-select: none;
        }

        .smart-card .minimize-btn {
            transition: transform 0.3s ease;
        }

        .smart-card.minimized .minimize-btn {
            transform: rotate(180deg);
        }

        .smart-card.dragging {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(124, 58, 237, 0.1),
                       0 8px 10px -6px rgba(124, 58, 237, 0.1);
        }

        .smart-card .drag-handle {
            cursor: move;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .smart-card:hover .drag-handle {
            opacity: 0.5;
        }

        .smart-card .drag-handle:hover {
            opacity: 1;
        }

        .card-placeholder {
            border: 2px dashed rgba(124, 58, 237, 0.3);
            background: rgba(124, 58, 237, 0.1);
            border-radius: 0.75rem;
            margin: 0.25rem 0;
        }

        @keyframes cardPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.005); }
            100% { transform: scale(1); }
        }

        .smart-card:hover {
            animation: cardPulse 2s infinite;
        }

        .cyber-grid {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: linear-gradient(transparent 1px, #0f1123 1px),
                            linear-gradient(90deg, transparent 1px, #0f1123 1px);
            background-size: 30px 30px;
            background-position: center center;
            animation: gridMove 20s linear infinite;
            opacity: 0.1;
            pointer-events: none;
        }

        @keyframes gridMove {
            from {
                transform: translate(0, 0);
            }
            to {
                transform: translate(-30px, -30px);
            }
        }

        /* Glowing Progress Bars */
        .progress-bar {
            background: linear-gradient(90deg, #7C3AED, #F471FF);
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
        }

        /* Animated Stats */
        .stat-value {
            animation: glowPulse 2s infinite;
        }

        @keyframes glowPulse {
            0% { text-shadow: 0 0 5px rgba(124, 58, 237, 0.5); }
            50% { text-shadow: 0 0 15px rgba(124, 58, 237, 0.8); }
            100% { text-shadow: 0 0 5px rgba(124, 58, 237, 0.5); }
        }

        /* Gaming Style Typing Effect */
        .typing-text {
            overflow: hidden;
            white-space: nowrap;
            margin: 0;
            animation: typing 3s steps(30), cursor .4s step-end infinite alternate;
            border-right: 3px solid #7C3AED;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes cursor {
            50% { border-color: transparent }
        }

        .welcome-text {
            background: linear-gradient(135deg, #7C3AED, #F471FF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
            display: inline-block;
            position: relative;
        }

        .welcome-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(124, 58, 237, 0.2), transparent);
            transform: translateX(-100%);
            animation: scanning 3s linear infinite;
        }

        @keyframes scanning {
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body class="bg-dark text-white">
    <div class="cyber-grid"></div>
    <div class="container mx-auto px-2 py-2 max-w-7xl relative">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-4">
                <div class="relative group cursor-pointer" onclick="showAvatarModal()">
                    <div class="w-14 h-14 bg-gradient-to-r from-primary to-accent rounded-full p-0.5 transition-all duration-300 transform group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-primary/20">
                        <div class="w-full h-full rounded-full overflow-hidden bg-dark flex items-center justify-center relative">
                            <?php
                            $avatarStyle = $_COOKIE['avatarStyle'] ?? 'avataaars';
                            $avatarSeed = $_COOKIE['avatarSeed'] ?? 'Real';
                            $avatarBg = $_COOKIE['avatarBg'] ?? '6953f7';
                            $avatarUrl = "https://api.dicebear.com/7.x/{$avatarStyle}/svg?seed={$avatarSeed}&backgroundColor={$avatarBg}";
                            ?>
                            <img id="avatarImage" src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="Profile" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-1 text-xs text-white text-center transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-black/30 backdrop-blur-sm">Edit</div>
                        </div>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-success rounded-full border-2 border-dark flex items-center justify-center animate-pulse">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                </div>

                <!-- Avatar Edit Modal -->
                <div id="avatarModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-dark-card rounded-xl p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4">Edit Avatar</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Background Color</label>
                                    <input type="color" id="backgroundColor" class="w-full h-10 rounded-lg cursor-pointer" value="#6953f7">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Avatar Style</label>
                                    <select id="avatarStyle" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        <option value="avataaars">Human</option>
                                        <option value="bottts">Robot</option>
                                        <option value="pixel-art">Pixel Art</option>
                                        <option value="initials">Initials</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Seed (Name)</label>
                                <input type="text" id="avatarSeed" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" value="Real">
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button onclick="hideAvatarModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                                <button onclick="updateAvatar()" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/90 transition">Update Avatar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="typing-text">
                        <h1 class="text-2xl font-bold welcome-text">Welcome back Real!</h1>
                    </div>
                    <p class="text-gray-400 transform hover:-translate-y-0.5 transition-transform duration-200">Track your spending and savings</p>
                </div>
            </div>
            <div class="flex gap-4">
                <button id="debtTrackerBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </button>

                <button id="calculatorBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10H9m3-5h3m-6 0h0m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>

                <div class="relative">
                    <button id="notificationBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="notificationBadge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs flex items-center justify-center text-white font-bold">0</span>
                    </button>
                    <!-- Notifications Panel -->
                    <div id="notificationsPanel" class="hidden absolute top-12 right-0 bg-dark-card rounded-lg shadow-xl p-4 z-50 w-80 max-h-[400px] overflow-y-auto">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-semibold">Notifications</h3>
                            <button id="clearNotifications" class="text-xs text-gray-400 hover:text-white transition">Clear all</button>
                        </div>
                        <div id="notificationsList" class="space-y-2">
                            <!-- Notifications will be added here -->
                        </div>
                    </div>
                </div>
                <button id="settingsBtn" class="p-2 rounded-lg bg-dark-card hover:bg-gray-700 transition group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
                
                <!-- Settings Panel -->
                <div id="settingsPanel" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50">
                    <div class="absolute right-4 top-16 w-80 bg-dark-card rounded-xl shadow-2xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Settings</h3>
                            <button onclick="toggleSettings()" class="p-2 hover:bg-gray-700 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Settings Sections -->
                        <div class="space-y-4">
                            <!-- Theme Settings -->
                            <div class="bg-dark/50 rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Theme</h4>
                                <div class="flex gap-2">
                                    <button class="w-8 h-8 rounded-full bg-gradient-to-r from-primary to-accent" onclick="setTheme('default')"></button>
                                    <button class="w-8 h-8 rounded-full bg-gradient-to-r from-green-400 to-blue-500" onclick="setTheme('nature')"></button>
                                    <button class="w-8 h-8 rounded-full bg-gradient-to-r from-red-500 to-yellow-500" onclick="setTheme('sunset')"></button>
                                </div>
                            </div>
                            
                            <!-- Display Settings -->
                            <div class="bg-dark/50 rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Display</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center justify-between">
                                        <span class="text-sm text-gray-400">Animations</span>
                                        <input type="checkbox" id="toggleAnimations" class="toggle" checked>
                                    </label>
                                    <label class="flex items-center justify-between">
                                        <span class="text-sm text-gray-400">Show Balance</span>
                                        <input type="checkbox" id="toggleBalance" class="toggle" checked>
                                    </label>
                                    <label class="flex items-center justify-between">
                                        <span class="text-sm text-gray-400">Compact View</span>
                                        <input type="checkbox" id="toggleCompact" class="toggle">
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Currency Settings -->
                            <div class="bg-dark/50 rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Currency Format</h4>
                                <select id="currencyFormat" class="w-full bg-dark text-white rounded-lg p-2 text-sm border border-gray-600">
                                    <option value="PHP">₱ (PHP)</option>
                                    <option value="USD">$ (USD)</option>
                                    <option value="EUR">€ (EUR)</option>
                                </select>
                            </div>
                            
                            <!-- Budget Settings -->
                            <div class="bg-dark/50 rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Budget Alerts</h4>
                                <div class="space-y-2">
                                    <label class="block">
                                        <span class="text-sm text-gray-400">Daily Limit</span>
                                        <input type="number" id="dailyLimit" class="w-full bg-dark text-white rounded-lg p-2 text-sm border border-gray-600" value="200">
                                    </label>
                                    <label class="flex items-center justify-between">
                                        <span class="text-sm text-gray-400">Alert Notifications</span>
                                        <input type="checkbox" id="toggleAlerts" class="toggle" checked>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="mt-4">
                            <button onclick="saveSettings()" class="w-full cyber-button px-4 py-2 rounded-lg transition duration-300">
                                <span>Save Settings</span>
                            </button>
                        </div>
                    </div>
                </div>
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
                <div id="debtTrackerOverlay" class="hidden absolute top-16 right-4 bg-dark-card rounded-lg shadow-xl p-4 z-50 w-[500px] max-h-[600px] overflow-y-auto hover-card">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-lg font-semibold">Debt Tracker</h2>
                            <p class="text-xs text-gray-400">Manage your debts efficiently</p>
                        </div>
                        <button onclick="showAddDebtModal()" class="p-2 rounded-lg bg-success hover:bg-success/90 transition flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add</span>
                        </button>
                    </div>
                    <div class="space-y-3">
                        <!-- Debts will be displayed as cards -->
                        <div id="debtsList" class="grid gap-3">
                            <!-- Debts will be dynamically added here as cards -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Main Grid Layout -->
        <div id="smartCardGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 mb-1">
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
            <div class="smart-card bg-dark-card rounded-lg p-3 shadow-lg hover-card" data-card-id="monthly-overview">
                <div class="card-header flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <div class="drag-handle p-1 rounded hover:bg-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                        </div>
                        <h2 class="text-base font-semibold">Monthly Overview</h2>
                    </div>
                    <div class="flex items-center gap-2">>
                        <div class="px-2 py-1 bg-dark/50 rounded-lg text-xs">
                            <span class="text-gray-400">Daily Avg: </span>
                            <span class="text-primary font-medium">₱<?php 
                                $daysInMonth = date('t');
                                $currentDay = min((int)date('d'), $daysInMonth);
                                echo number_format($totalExpenses / $currentDay, 2); 
                            ?></span>
                        </div>
                        <span class="text-xs text-gray-400"><?php echo date('F Y'); ?></span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Income Circle -->
                    <div class="text-center">
                        <div class="relative w-40 h-40 mx-auto mb-2">
                            <svg class="progress-ring electric-loading" width="100%" height="100%" viewBox="0 0 160 160">
                                <defs>
                                    <filter id="income-glow">
                                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <circle class="text-gray-700/20" stroke="currentColor" stroke-width="8" fill="transparent" r="65" cx="80" cy="80"/>
                                <circle class="text-success electric-pulse electric-dash" stroke="currentColor" stroke-width="8" 
                                    fill="transparent" r="65" cx="80" cy="80" filter="url(#income-glow)"/>
                                <?php
                                    $prevMonthIncome = 0; // You would need to fetch this from your database
                                    $percentChange = $prevMonthIncome != 0 ? (($totalIncome - $prevMonthIncome) / $prevMonthIncome) * 100 : 0;
                                ?>
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center w-full px-2">
                                <span class="text-2xl font-bold text-success block">₱<?php echo number_format($totalIncome, 2); ?></span>
                                <p class="text-xs text-gray-400 mt-1">Total Income</p>
                                <div class="flex items-center justify-center gap-1 mt-1">
                                    <?php
                                        $highestCategory = "Adopt Me"; // You would need to fetch this from your database
                                        $highestAmount = 0; // You would need to fetch this from your database
                                    ?>
                                    <span class="text-[10px] text-success/80">Top: <?php echo $highestCategory; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-dark/50 rounded-lg p-2">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-medium text-success">Income Total</h3>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-success/10 text-success">
                                    <?php echo round(($totalIncome / ($totalIncome + $totalExpenses)) * 100); ?>%
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-400">Resets Monthly</p>
                                <p class="text-[10px] text-gray-400">
                                    <?php
                                        $remainingDays = $daysInMonth - $currentDay;
                                        echo $remainingDays . " days left";
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Expense Circle -->
                    <div class="text-center">
                        <div class="relative w-40 h-40 mx-auto mb-2">
                            <svg class="progress-ring electric-loading" width="100%" height="100%" viewBox="0 0 160 160">
                                <defs>
                                    <filter id="expense-glow">
                                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <circle class="text-gray-700/20" stroke="currentColor" stroke-width="8" fill="transparent" r="65" cx="80" cy="80"/>
                                <circle class="text-red-500 electric-pulse electric-dash" stroke="currentColor" stroke-width="8" 
                                    fill="transparent" r="65" cx="80" cy="80" filter="url(#expense-glow)"/>
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center w-full px-2">
                                <span class="text-2xl font-bold text-red-500 block">₱<?php echo number_format($totalExpenses, 2); ?></span>
                                <p class="text-xs text-gray-400 mt-1">Total Expenses</p>
                                <div class="flex items-center justify-center gap-1 mt-1">
                                    <?php
                                        $highestExpenseCategory = "Wants/Needs/Fee"; // You would need to fetch this from your database
                                        $highestExpenseAmount = 0; // You would need to fetch this from your database
                                    ?>
                                    <span class="text-[8px] text-red-500/80 whitespace-nowrap">
                                        Highest: <span class="font-medium"><?php echo $highestExpenseCategory; ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-dark/50 rounded-lg p-2">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-medium text-red-500">Expense Total</h3>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-red-500/10 text-red-500">
                                    <?php echo round(($totalExpenses / ($totalIncome + $totalExpenses)) * 100); ?>%
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-400">Resets Monthly</p>
                                <span class="text-[10px] <?php echo $totalExpenses > 6000 ? 'text-red-400' : 'text-green-400'; ?>">
                                    <?php echo $totalExpenses > 6000 ? 'Over Budget' : 'Under Budget'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Income vs Expenses Trend -->
                <div class="mt-3 bg-dark/50 rounded-lg p-2">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-xs font-medium">Monthly Flow</h4>
                        <div class="flex gap-3">
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-success"></span>
                                <span class="text-[10px] text-gray-400">Income</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="text-[10px] text-gray-400">Expenses</span>
                            </span>
                        </div>
                    </div>
                    <div class="h-24">
                        <canvas id="flowChart"></canvas>
                    </div>
                    <script>
                        // Get the last 7 days of data
                        <?php
                        $dates = [];
                        $incomeData = [];
                        $expenseData = [];
                        
                        for ($i = 6; $i >= 0; $i--) {
                            $date = date('Y-m-d', strtotime("-$i days"));
                            $dates[] = date('M j', strtotime($date));
                            
                            $stmt = $conn->prepare("SELECT 
                                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as daily_income,
                                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as daily_expenses
                                FROM transactions 
                                WHERE DATE(transaction_date) = :date");
                            $stmt->execute(['date' => $date]);
                            $result = $stmt->fetch();
                            
                            $incomeData[] = $result['daily_income'];
                            $expenseData[] = $result['daily_expenses'];
                        }
                        ?>
                        
                        const flowCtx = document.getElementById('flowChart').getContext('2d');
                        new Chart(flowCtx, {
                            type: 'line',
                            data: {
                                labels: <?php echo json_encode($dates); ?>,
                                datasets: [{
                                    label: 'Income',
                                    data: <?php echo json_encode($incomeData); ?>,
                                    borderColor: '#00FF9D',
                                    backgroundColor: 'rgba(0, 255, 157, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    borderWidth: 2,
                                    pointBackgroundColor: '#00FF9D',
                                    pointBorderColor: '#00FF9D',
                                    pointHoverBackgroundColor: '#fff',
                                    pointHoverBorderColor: '#00FF9D',
                                    pointHoverBorderWidth: 2,
                                    animation: {
                                        duration: 2000,
                                        easing: 'easeInOutQuart'
                                    }
                                },
                                {
                                    label: 'Expenses',
                                    data: <?php echo json_encode($expenseData); ?>,
                                    borderColor: '#FF3D57',
                                    backgroundColor: 'rgba(255, 61, 87, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    borderWidth: 2,
                                    pointBackgroundColor: '#FF3D57',
                                    pointBorderColor: '#FF3D57',
                                    pointHoverBackgroundColor: '#fff',
                                    pointHoverBorderColor: '#FF3D57',
                                    pointHoverBorderWidth: 2,
                                    animation: {
                                        duration: 2000,
                                        easing: 'easeInOutQuart'
                                    }
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'nearest',
                                    axis: 'x',
                                    intersect: false
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                        titleColor: '#fff',
                                        bodyColor: '#fff',
                                        padding: 12,
                                        borderColor: 'rgba(124, 58, 237, 0.5)',
                                        borderWidth: 1,
                                        displayColors: true,
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += '₱' + context.parsed.y.toLocaleString('en-PH', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                                return label;
                                            },
                                            afterBody: function(context) {
                                                const income = context[0].parsed.y;
                                                const expense = context[1]?.parsed.y || 0;
                                                const difference = income - expense;
                                                return [
                                                    '',
                                                    'Net: ₱' + difference.toLocaleString('en-PH', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    })
                                                ];
                                            }
                                        }
                                    },
                                    zoom: {
                                        zoom: {
                                            wheel: {
                                                enabled: true,
                                                modifierKey: 'ctrl'
                                            },
                                            pinch: {
                                                enabled: true
                                            },
                                            drag: {
                                                enabled: true,
                                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                                borderColor: 'rgba(124, 58, 237, 0.5)',
                                                borderWidth: 1
                                            },
                                            mode: 'x'
                                        },
                                        pan: {
                                            enabled: true,
                                            mode: 'x'
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.1)'
                                        },
                                        ticks: {
                                            color: '#9ca3af',
                                            callback: function(value) {
                                                return '₱' + value.toLocaleString('en-PH');
                                            },
                                            font: {
                                                size: 8
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#9ca3af',
                                            font: {
                                                size: 8
                                            }
                                        }
                                    }
                                },
                                elements: {
                                    point: {
                                        radius: 2,
                                        hoverRadius: 6
                                    }
                                },
                                animation: {
                                    duration: 1000,
                                    easing: 'easeInOutQuart'
                                }
                            }
                        });
                    </script>
                </div>
            </div>

            <!-- Cashflow Card -->
            <div class="bg-dark-card rounded-xl p-3 shadow-lg hover-card">
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
                            <p class="text-xs text-gray-400" id="monthlyProgress">TOTAL BALANCE</p>
                            <p class="text-2xl font-bold neon-text stat-value" id="netBalance">₱33,125.22</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-700/30 rounded-lg h-8 relative overflow-hidden">
                        <svg class="w-full h-full absolute top-0 left-0" preserveAspectRatio="none" viewBox="0 0 500 24">
                            <path id="pulsePath" class="pulse-wave" d="M0,12 
                            C5,12 7,8 10,8 
                            C13,8 15,16 18,16 
                            C21,16 23,10 26,10 
                            C29,10 31,14 34,14 
                            C37,14 39,6 42,6 
                            C45,6 47,18 50,18 
                            C53,18 55,4 58,4 
                            C61,4 63,20 66,20 
                            C69,20 71,8 74,8 
                            C77,8 79,16 82,16 
                            C85,16 87,6 90,6 
                            C93,6 95,14 98,14 
                            C101,14 103,10 106,10 
                            C109,10 111,18 114,18 
                            C117,18 119,4 122,4 
                            C125,4 127,20 130,20 
                            C133,20 135,8 138,8 
                            C141,8 143,12 146,12 
                            C149,12 151,6 154,6 
                            C157,6 159,16 162,16 
                            C165,16 167,10 170,10 
                            C173,10 175,14 178,14 
                            C181,14 183,8 186,8 
                            C189,8 191,18 194,18 
                            C197,18 199,4 202,4 
                            C205,4 207,20 210,20 
                            C213,20 215,8 218,8 
                            C221,8 223,12 226,12 
                            C229,12 231,6 234,6 
                            C237,6 239,16 242,16 
                            C245,16 247,10 250,10 
                            C253,10 255,14 258,14 
                            C261,14 263,8 266,8 
                            C269,8 271,18 274,18 
                            C277,18 279,4 282,4 
                            C285,4 287,20 290,20 
                            C293,20 295,8 298,8 
                            C301,8 303,12 306,12 
                            C309,12 311,6 314,6 
                            C317,6 319,16 322,16 
                            C325,16 327,10 330,10 
                            C333,10 335,14 338,14 
                            C341,14 343,8 346,8 
                            C349,8 351,18 354,18 
                            C357,18 359,4 362,4 
                            C365,4 367,20 370,20 
                            C373,20 375,8 378,8 
                            C381,8 383,12 386,12 
                            C389,12 391,6 394,6 
                            C397,6 399,16 402,16 
                            C405,16 407,10 410,10 
                            C413,10 415,14 418,14 
                            C421,14 423,8 426,8 
                            C429,8 431,18 434,18 
                            C437,18 439,4 442,4 
                            C445,4 447,20 450,20 
                            C453,20 455,8 458,8 
                            C461,8 463,12 466,12 
                            C469,12 471,6 474,6 
                            C477,6 479,16 482,16 
                            C485,16 487,10 490,10 
                            C493,10 495,14 498,14 
                            L500,12" 
                            stroke="currentColor" fill="none" stroke-width="1.5"/>
                        </svg>
                        <div id="progressBar" class="bg-primary h-full rounded-lg opacity-10" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Today's Transactions</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-dark/50 p-3 rounded-lg">
                            <div>
                                <p class="font-medium text-success">Today's Income</p>
                                <p class="text-xs text-gray-400" id="todayDate"></p>
                            </div>
                            <span id="todayIncome" class="text-lg font-bold text-success">₱0.00</span>
                        </div>
                        <div class="flex justify-between items-center bg-dark/50 p-3 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-red-500">Today's Expenses</p>
                                <p class="text-xs text-gray-400" id="remainingBudget">Deducted from Balance</p>
                                <p id="expenseStatus" class="text-xs mt-1"></p>
                            </div>
                            <span id="todayExpenses" class="text-base font-bold text-red-500">₱0.00</span>
                        </div>
                    </div>

                    <!-- Velocity Tracker -->
                    <div class="mt-3 bg-dark/50 rounded-lg p-2">
                        <div class="flex items-center gap-1">
                            <h2 class="text-sm font-semibold">Spending Velocity</h2>
                            <?php
                            // Calculate spending velocity (last 3 days average)
                            $stmt = $conn->prepare("SELECT 
                                AVG(daily_total) as avg_spending
                                FROM (
                                    SELECT DATE(transaction_date) as date, 
                                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as daily_total
                                    FROM transactions 
                                    WHERE DATE(transaction_date) >= DATE_SUB(CURRENT_DATE, INTERVAL 3 DAY)
                                    GROUP BY DATE(transaction_date)
                                ) as daily_totals");
                            $stmt->execute();
                            $result = $stmt->fetch();
                            $avgSpending = $result['avg_spending'] ?? 0;
                            
                            // Get today's spending
                            $stmt = $conn->prepare("SELECT 
                                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as today_spending
                                FROM transactions 
                                WHERE DATE(transaction_date) = CURRENT_DATE");
                            $stmt->execute();
                            $result = $stmt->fetch();
                            $todaySpending = $result['today_spending'];
                            
                            // Calculate percentage difference
                            $percentDiff = $avgSpending != 0 ? (($todaySpending - $avgSpending) / $avgSpending) * 100 : 0;
                            $trendClass = $percentDiff > 0 ? 'text-red-400' : 'text-success';
                            $trendIcon = $percentDiff > 0 ? '↑' : '↓';
                            
                            // Calculate projected spending
                            $daysLeft = date('t') - date('j');
                            $projectedSpending = $avgSpending * $daysLeft;
                            ?>
                            <span class="text-xs font-bold <?php echo $trendClass; ?>">
                                <?php echo $trendIcon . ' ' . abs(round($percentDiff)); ?>% vs 3-day avg
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="text-xs text-gray-400">
                                3-day avg: <span class="text-white">₱<?php echo number_format($avgSpending, 2); ?>/day</span>
                            </div>
                            <div class="text-xs text-gray-400">
                                Projected: 
                                <span class="<?php echo $projectedSpending > 6000 ? 'text-red-400' : 'text-success'; ?>">
                                    ₱<?php echo number_format($projectedSpending, 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Goals Card -->
            <div class="bg-dark-card rounded-xl p-3 shadow-lg hover-card">
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
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 flex items-center justify-center">
                                    <img src="assets/maya.png" alt="Maya" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs text-gray-400">Personal Investment</p>
                                        <span class="text-xs bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-full">Maya</span>
                                    </div>
                                    <p class="text-lg font-bold neon-text">₱15,000.00</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <span id="investment1Profit" class="text-green-500 text-sm">+₱194.18</span>
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
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
                <div class="bg-dark-card rounded-lg p-2 shadow-lg hover-card">
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
                <div class="bg-dark-card rounded-lg p-2 shadow-lg hover-card">
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
            <div class="col-span-2 bg-dark-card rounded-xl p-4 shadow-lg hover-card">
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
        <div class="bg-dark-card rounded-xl shadow-lg p-4 mb-4 hover-card">
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
                            <option value="Debt">Debt</option>
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
                                class="w-full cyber-button px-6 py-2 rounded-lg transition duration-300">
                            <span>Add Transaction</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Transactions Card -->
        <div class="bg-dark-card rounded-xl shadow-lg p-4 mb-4 hover-card">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-lg font-semibold">Transaction History</h2>
                <div class="flex gap-2">
                    <button id="filterBtn" class="p-2 hover:bg-gray-700 rounded-lg transition flex items-center gap-1 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filters
                    </button>
                    <button id="downloadBtn" class="p-2 hover:bg-gray-700 rounded-lg transition flex items-center gap-1 text-sm" onclick="exportTransactions()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <!-- Filters Panel -->
            <div id="filtersPanel" class="hidden mb-4 p-4 bg-dark/50 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Date Range</label>
                        <select id="dateFilter" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div id="customDateRange" class="hidden md:col-span-2 grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Start Date</label>
                            <input type="date" id="startDate" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">End Date</label>
                            <input type="date" id="endDate" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Amount Range</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="minAmount" placeholder="Min" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                            <span class="text-gray-400">-</span>
                            <input type="number" id="maxAmount" placeholder="Max" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Category</label>
                        <select id="categoryFilter" class="w-full px-3 py-2 rounded-lg bg-dark text-white border border-gray-600">
                            <option value="all">All Categories</option>
                            <option value="income">Income Only</option>
                            <option value="expense">Expenses Only</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4 gap-2">
                    <button onclick="clearFilters()" class="px-4 py-2 text-sm rounded-lg bg-gray-700 hover:bg-gray-600 transition">Clear Filters</button>
                    <button onclick="applyFilters()" class="px-4 py-2 text-sm rounded-lg bg-primary hover:bg-primary/90 transition">Apply Filters</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-dark-card">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Date & Time
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
                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-400">
                        Showing <span id="pageInfo">0-0 of 0</span> transactions
                    </div>
                    <div class="flex gap-2">
                        <button id="prevPage" class="px-3 py-1 rounded-lg bg-dark hover:bg-gray-700 transition disabled:opacity-50">Previous</button>
                        <button id="nextPage" class="px-3 py-1 rounded-lg bg-dark hover:bg-gray-700 transition disabled:opacity-50">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Debt Modal -->
        <div id="addDebtModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-dark-card rounded-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Add New Debt</h3>
                <form id="addDebtForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Debtor Name</label>
                        <input type="text" id="debtorName" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Amount</label>
                        <input type="number" id="debtAmount" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" step="0.01" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Due Date (Optional)</label>
                        <input type="date" id="dueDate" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Notes (Optional)</label>
                        <textarea id="debtNotes" class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideAddDebtModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-success hover:bg-success/90 transition">Add Debt</button>
                    </div>
                </form>
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
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#6953F7',
                    pointBorderColor: '#6953F7',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#6953F7',
                    pointHoverBorderWidth: 2,
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                },
                {
                    label: 'Mother\'s Business',
                    data: [9000, 9000, 9000, 9000, 9000, 9000],
                    borderColor: '#CD46F7',
                    backgroundColor: 'rgba(205, 70, 247, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#CD46F7',
                    pointBorderColor: '#CD46F7',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#CD46F7',
                    pointHoverBorderWidth: 2,
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        borderColor: 'rgba(124, 58, 237, 0.5)',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const dataset = context.dataset;
                                const label = dataset.label;
                                const formattedValue = '₱' + value.toLocaleString('en-PH', { 
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                });
                                return `${label}: ${formattedValue}`;
                            },
                            afterBody: function(context) {
                                const datasetIndex = context[0].datasetIndex;
                                const pointIndex = context[0].dataIndex;
                                const currentValue = context[0].raw;
                                const initialValue = context[0].dataset.data[0];
                                const profit = currentValue - initialValue;
                                const percentageGain = ((currentValue - initialValue) / initialValue) * 100;
                                
                                return [
                                    '',
                                    `Initial Investment: ₱${initialValue.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`,
                                    `Profit: ₱${profit.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`,
                                    `Return: ${percentageGain.toFixed(2)}%`
                                ];
                            }
                        }
                    },
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                                modifierKey: 'ctrl'
                            },
                            pinch: {
                                enabled: true
                            },
                            drag: {
                                enabled: true,
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                borderColor: 'rgba(124, 58, 237, 0.5)',
                                borderWidth: 1
                            },
                            mode: 'x'
                        },
                        pan: {
                            enabled: true,
                            mode: 'x'
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

        function animateValue(element, start, end, duration) {
            const startTime = performance.now();
            const formatter = new Intl.NumberFormat('en-PH', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2,
                style: 'decimal'
            });
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const current = start + (end - start) * easeOutQuart;
                
                // Ensure we always show 2 decimal places
                element.textContent = `₱${formatter.format(parseFloat(current.toFixed(2)))}`;
                element.classList.add('animate-number');
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        function updateBalance() {
            fetch('get_today_totals.php')
            .then(response => response.json())
            .then(data => {
                const income = parseFloat(data.income);
                const expenses = parseFloat(data.expenses);
                const totalIncomeElement = document.getElementById('totalIncome');
                const totalExpensesElement = document.getElementById('totalExpenses');
                
                // Get current values
                const currentIncome = parseFloat(totalIncomeElement.textContent.replace(/[₱,]/g, '')) || 0;
                const currentExpenses = parseFloat(totalExpensesElement.textContent.replace(/[₱,]/g, '')) || 0;
                
                // Animate the changes
                animateValue(totalIncomeElement, currentIncome, income, 1000);
                animateValue(totalExpensesElement, currentExpenses, expenses, 1000);
                
                // Update expense status
                const expenseStatus = document.getElementById('expenseStatus');
                if (expenses > 200) {
                    expenseStatus.textContent = '⚠️ Warning: Daily expenses exceeded 200 PHP limit';
                    expenseStatus.className = 'text-xs mt-1 text-yellow-500 font-medium';
                } else {
                    expenseStatus.textContent = '🌟 Excellent! Keeping expenses under control';
                    expenseStatus.className = 'text-xs mt-1 text-green-400 font-medium';
                }
                
                // Add highlight effect for changes
                if (currentIncome !== income) {
                    totalIncomeElement.classList.add('text-success');
                    setTimeout(() => totalIncomeElement.classList.remove('text-success'), 1000);
                }
                if (currentExpenses !== expenses) {
                    totalExpensesElement.classList.add('text-danger');
                    setTimeout(() => totalExpensesElement.classList.remove('text-danger'), 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Category Icons mapping
        const categoryIcons = {
            // Income categories
            'Adopt Me': '🎮',
            'Grow a Garden': '🌱',
            'Debt': '💸',
            'MIDMAN fee': '💼',
            // Expense categories
            'Breakfast': '🍳',
            'Lunch': '🍱',
            'Dinner': '🍽️',
            'Wants/Needs/Fee': '🛍️',
            // Add more categories and icons as needed
        };

        let currentPage = 1;
        const itemsPerPage = 10;
        let filteredTransactions = [];

        function getTransactionIcon(categoryName) {
            return categoryIcons[categoryName] || '💰';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function displayTransactions(page = 1) {
            const queryParams = new URLSearchParams({
                type: currentView,
                page: page,
                ...getFilterParams()
            });

            fetch(`get_transactions.php?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                filteredTransactions = data.transactions || [];
                const tbody = document.getElementById('transactionsList');
                tbody.innerHTML = '';
                
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const paginatedTransactions = filteredTransactions.slice(start, end);
                
                paginatedTransactions.forEach(transaction => {
                    const row = document.createElement('tr');
                    const colorClass = transaction.type === 'income' ? 'text-green-500' : 'text-red-500';
                    const icon = getTransactionIcon(transaction.category_name);
                    
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-xl" role="img" aria-label="${transaction.category_name}">${icon}</span>
                                <div>
                                    <div class="text-sm font-medium text-white">${transaction.category_name}</div>
                                    <div class="text-xs text-gray-400">${transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1)}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm ${colorClass} font-semibold">₱${parseFloat(transaction.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-400">${formatDate(transaction.transaction_date)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <button onclick="showDeleteModal(${transaction.id}, ${transaction.amount}, '${transaction.category_name}')" 
                                        class="px-3 py-1 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-all duration-200 focus:outline-none flex items-center gap-2 text-sm group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="group-hover:translate-x-0.5 transition-transform duration-200">Delete</span>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Update pagination
                updatePagination(filteredTransactions.length, page);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function updatePagination(totalItems, currentPage) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentPage * itemsPerPage, totalItems);
            
            document.getElementById('pageInfo').textContent = `${start}-${end} of ${totalItems}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
        }

        function getFilterParams() {
            const dateFilter = document.getElementById('dateFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const minAmount = document.getElementById('minAmount').value;
            const maxAmount = document.getElementById('maxAmount').value;
            const category = document.getElementById('categoryFilter').value;

            return {
                dateFilter,
                startDate,
                endDate,
                minAmount,
                maxAmount,
                category
            };
        }

        // Initialize filter functionality
        document.getElementById('filterBtn').addEventListener('click', () => {
            const filtersPanel = document.getElementById('filtersPanel');
            filtersPanel.classList.toggle('hidden');
        });

        document.getElementById('dateFilter').addEventListener('change', (e) => {
            const customDateRange = document.getElementById('customDateRange');
            customDateRange.classList.toggle('hidden', e.target.value !== 'custom');
        });

        function clearFilters() {
            document.getElementById('dateFilter').value = 'all';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('minAmount').value = '';
            document.getElementById('maxAmount').value = '';
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('customDateRange').classList.add('hidden');
            applyFilters();
        }

        function applyFilters() {
            currentPage = 1;
            displayTransactions(currentPage);
        }

        // Pagination event listeners
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayTransactions(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayTransactions(currentPage);
            }
        });

        function exportTransactions() {
            const data = filteredTransactions.map(t => ({
                Category: t.category_name,
                Type: t.type,
                Amount: `₱${parseFloat(t.amount).toFixed(2)}`,
                Date: formatDate(t.transaction_date)
            }));

            const csv = convertToCSV(data);
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', `transactions_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function convertToCSV(arr) {
            const array = [Object.keys(arr[0])].concat(arr);
            return array.map(row => {
                return Object.values(row)
                    .map(value => `"${value}"`)
                    .join(',');
            }).join('\\n');
        }

        function showDeleteModal(id, amount, category) {
            const modal = document.getElementById('deleteTransactionModal');
            const amountElement = document.getElementById('deleteTransactionAmount');
            const categoryElement = document.getElementById('deleteTransactionCategory');
            
            // Update modal content
            amountElement.textContent = `₱${parseFloat(amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
            categoryElement.textContent = category;
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Set up confirm button
            const confirmButton = document.getElementById('confirmDelete');
            confirmButton.onclick = () => {
                deleteTransaction(id);
                modal.classList.add('hidden');
            };
            
            // Set up cancel button
            const cancelButton = document.getElementById('cancelDelete');
            cancelButton.onclick = () => {
                modal.classList.add('hidden');
            };
            
            // Close modal when clicking outside
            modal.onclick = (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            };
        }

        function deleteTransaction(id) {
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
                    // Update everything immediately
                    updateBalance();
                    updateCashflowCard();
                    updateMonthlyOverview();
                    updateAllCards();
                    displayTransactions();
                    
                    // Show success notification
                    notifications.add('Transaction deleted successfully', 'success');
                } else {
                    notifications.add('Error deleting transaction: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notifications.add('Error deleting transaction. Please try again.', 'error');
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
                    // Update everything immediately
                    updateBalance();
                    updateCashflowCard();
                    updateMonthlyOverview();
                    updateAllCards();
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
            // Update today's date
            const today = new Date();
            document.getElementById('todayDate').textContent = today.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            // First fetch the total balance
            fetch('get_total_balance.php')
            .then(response => response.json())
            .then(totals => {
                // Then fetch today's transactions
                fetch('get_today_totals.php')
                .then(response => response.json())
                .then(data => {
                    const todayIncome = parseFloat(data.income) || 0;
                    const todayExpenses = parseFloat(data.expenses) || 0;
                    const netBalance = parseFloat(totals.net_balance) || 0;

                    // Update the display with both total and today's numbers
                    document.getElementById('todayIncome').textContent = `₱${todayIncome.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    document.getElementById('todayExpenses').textContent = `₱${todayExpenses.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    document.getElementById('netBalance').textContent = `₱${netBalance.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    
                    // Update progress information
                    document.getElementById('monthlyProgress').textContent = `Running Balance`;
                    document.getElementById('progressBar').style.width = '100%';
                    document.getElementById('remainingBudget').textContent = `Total Balance`;
                });
            })
            .catch(error => {
                console.error('Error updating cashflow:', error);
            });
        }

        // Update cashflow on load
        updateCashflowCard();

        // Add refresh button functionality
        document.getElementById('refreshCashflow').addEventListener('click', updateCashflowCard);

        // Function to update monthly overview circles
        async function updateMonthlyOverview() {
            try {
                const response = await fetch('get_monthly_totals.php');
                const data = await response.json();
                
                const totalIncome = parseFloat(data.income) || 0;
                const totalExpenses = parseFloat(data.expenses) || 0;
                
                // Update the monthly overview income number
                const monthlyIncomeElement = document.querySelector('.text-success.text-2xl');
                if (monthlyIncomeElement) {
                    animateValue(monthlyIncomeElement, parseFloat(monthlyIncomeElement.textContent.replace(/[₱,]/g, '')), totalIncome, 1000);
                }
                
                // Update the monthly overview expense number
                const monthlyExpenseElement = document.querySelector('.text-red-500.text-2xl');
                if (monthlyExpenseElement) {
                    animateValue(monthlyExpenseElement, parseFloat(monthlyExpenseElement.textContent.replace(/[₱,]/g, '')), totalExpenses, 1000);
                }
            } catch (error) {
                console.error('Error updating monthly overview:', error);
            }
        }

        // Function to update both cards
        async function updateAllCards() {
            await Promise.all([
                updateCashflowCard(),
                updateMonthlyOverview()
            ]);
        }

        // Update cards every 30 seconds
        setInterval(updateAllCards, 30000);

        // Also update when a transaction is added or deleted
        const originalDisplayTransactions = displayTransactions;
        displayTransactions = async function(...args) {
            await originalDisplayTransactions(...args);
            updateAllCards();
        };

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
                displayDebts(); // Refresh debts list when opening
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

        // Avatar Modal Functions
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        function showAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
            
            // Get values from cookies or use defaults
            const style = getCookie('avatarStyle') || 'avataaars';
            const seed = getCookie('avatarSeed') || 'Real';
            const bg = getCookie('avatarBg') || '6953f7';
            
            // Set the form values
            document.getElementById('avatarSeed').value = seed;
            document.getElementById('backgroundColor').value = '#' + bg;
            document.getElementById('avatarStyle').value = style;
        }

        function hideAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
        }

        function updateAvatar() {
            const seed = document.getElementById('avatarSeed').value || 'Real';
            const backgroundColor = document.getElementById('backgroundColor').value.replace('#', '');
            const style = document.getElementById('avatarStyle').value;
            const avatarUrl = `https://api.dicebear.com/7.x/${style}/svg?seed=${encodeURIComponent(seed)}&backgroundColor=${backgroundColor}`;
            
            // Set cookies to persist avatar settings (valid for 1 year)
            const oneYear = 365 * 24 * 60 * 60;
            document.cookie = `avatarStyle=${style}; max-age=${oneYear}; path=/`;
            document.cookie = `avatarSeed=${seed}; max-age=${oneYear}; path=/`;
            document.cookie = `avatarBg=${backgroundColor}; max-age=${oneYear}; path=/`;
            
            document.getElementById('avatarImage').src = avatarUrl;
            hideAvatarModal();
        }

        // Debt Tracking Functions
        function showAddDebtModal() {
            document.getElementById('addDebtModal').classList.remove('hidden');
            document.getElementById('debtorName').focus();
        }

        function hideAddDebtModal() {
            document.getElementById('addDebtModal').classList.add('hidden');
            document.getElementById('addDebtForm').reset();
        }

        function displayDebts() {
            fetch('get_debts.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('debtsList');
                container.innerHTML = '';
                
                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-6 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <p class="text-sm">No debts recorded yet</p>
                            <button onclick="showAddDebtModal()" class="mt-2 text-primary hover:text-primary/80">Add your first debt</button>
                        </div>
                    `;
                    return;
                }
                
                data.forEach(debt => {
                    const card = document.createElement('div');
                    let statusClass = 'text-gray-400';
                    let statusText = 'No due date';
                    let statusBg = 'bg-gray-500/10';
                    let dueDateDisplay = 'Not set';
                    
                    if (debt.due_date) {
                        const dueDate = new Date(debt.due_date);
                        const today = new Date();
                        const isOverdue = dueDate < today;
                        
                        statusClass = isOverdue ? 'text-red-500' : 'text-yellow-500';
                        statusBg = isOverdue ? 'bg-red-500/10' : 'bg-yellow-500/10';
                        statusText = isOverdue ? 'Overdue' : 'Pending';
                        dueDateDisplay = formatDate(debt.due_date);
                    }
                    
                    card.className = 'bg-dark/50 rounded-lg p-3 hover:bg-dark/70 transition-colors duration-200';
                    card.innerHTML = `
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xl" role="img" aria-label="Debt">💸</span>
                                <div>
                                    <h3 class="font-medium">${debt.debtor_name}</h3>
                                    <p class="text-xs text-gray-400">${formatDate(debt.created_date)}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-semibold text-success">₱${parseFloat(debt.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full ${statusClass} ${statusBg}">
                                    ${statusText}
                                </span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mb-3">
                            ${debt.notes ? `<p class="italic">"${debt.notes}"</p>` : ''}
                            ${debt.due_date ? `<p class="mt-1">Due: ${dueDateDisplay}</p>` : ''}
                        </div>
                        <div class="flex justify-end gap-2">
                            <button onclick="markDebtAsPaid(${debt.id}, ${debt.amount})" 
                                    class="px-2 py-1 rounded bg-success/10 text-success hover:bg-success/20 transition-colors duration-200 text-xs flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Paid
                            </button>
                            <button onclick="deleteDebt(${debt.id})" 
                                    class="px-2 py-1 rounded bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors duration-200 text-xs flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    `;
                    container.appendChild(card);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        document.getElementById('addDebtForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                debtor_name: document.getElementById('debtorName').value,
                amount: document.getElementById('debtAmount').value,
                due_date: document.getElementById('dueDate').value,
                notes: document.getElementById('debtNotes').value
            };
            
            fetch('save_debt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    hideAddDebtModal();
                    displayDebts();
                } else {
                    alert('Error saving debt: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving debt. Please try again.');
            });
        });

        async function markDebtAsPaid(debtId, amount) {
            try {
                if (!confirm('Mark this debt as paid? This will also record it as an income transaction.')) {
                    return;
                }
                
                console.log('Marking debt as paid:', { debtId, amount });
                
                const updateResponse = await fetch('update_debt.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${debtId}`
                });
                
                console.log('Update response:', updateResponse);
                const data = await updateResponse.json();
                console.log('Update data:', data);
                
                if(data.success) {
                    // Add a transaction for the paid debt
                    const transactionResponse = await fetch('save_transaction.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `type=income&amount=${amount}&category=Debt`
                    });
                    
                    console.log('Transaction response:', transactionResponse);
                    const transactionData = await transactionResponse.json();
                    console.log('Transaction data:', transactionData);
                    
                    if(transactionData.success) {
                        displayDebts();
                        displayTransactions();
                        updateBalance();
                    } else {
                        console.error('Transaction error:', transactionData);
                        alert('Error recording transaction: ' + transactionData.message);
                    }
                } else {
                    console.error('Update error:', data);
                    alert('Error updating debt: ' + data.message);
                }
            } catch (error) {
                console.error('Caught error:', error);
                alert('Error processing debt payment. Please try again.');
            }
        }

        function deleteDebt(debtId) {
            if (!confirm('Are you sure you want to delete this debt record?')) {
                return;
            }
            
            fetch('delete_debt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${debtId}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    displayDebts();
                } else {
                    alert('Error deleting debt: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting debt. Please try again.');
            });
        }

        // Initialize debt list
        displayDebts();

        // Notifications System
        const notifications = {
            list: [],
            maxCount: 50, // Maximum number of notifications to store

            add(message, type = 'info', timestamp = new Date()) {
                const notification = {
                    id: Date.now(),
                    message,
                    type,
                    timestamp,
                    read: false
                };
                
                this.list.unshift(notification);
                if (this.list.length > this.maxCount) {
                    this.list.pop();
                }
                
                this.saveToLocalStorage();
                this.updateUI();
            },

            markAllAsRead() {
                this.list.forEach(notification => notification.read = true);
                this.saveToLocalStorage();
                this.updateUI();
            },

            clear() {
                this.list = [];
                this.saveToLocalStorage();
                this.updateUI();
            },

            saveToLocalStorage() {
                localStorage.setItem('financeNotifications', JSON.stringify(this.list));
            },

            loadFromLocalStorage() {
                const saved = localStorage.getItem('financeNotifications');
                if (saved) {
                    this.list = JSON.parse(saved);
                    this.updateUI();
                }
            },

            updateUI() {
                const badge = document.getElementById('notificationBadge');
                const panel = document.getElementById('notificationsList');
                const unreadCount = this.list.filter(n => !n.read).length;
                
                // Update badge
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
                
                // Update panel
                if (this.list.length === 0) {
                    panel.innerHTML = `
                        <div class="text-center py-4 text-gray-400">
                            <p class="text-sm">No notifications</p>
                        </div>
                    `;
                } else {
                    panel.innerHTML = this.list.map(notification => `
                        <div class="p-2 rounded bg-dark/50 hover:bg-dark/70 transition-colors duration-200 ${notification.read ? 'opacity-60' : ''}">
                            <div class="flex items-start gap-2">
                                <span class="text-xl" role="img">
                                    ${notification.type === 'warning' ? '⚠️' : 
                                      notification.type === 'success' ? '✅' : 
                                      notification.type === 'error' ? '❌' : 'ℹ️'}
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${formatDate(new Date(notification.timestamp))}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            }
        };

        // Initialize notifications
        notifications.loadFromLocalStorage();

        // Notification Panel Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationsPanel = document.getElementById('notificationsPanel');
        
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationsPanel.classList.toggle('hidden');
            if (!notificationsPanel.classList.contains('hidden')) {
                notifications.markAllAsRead();
            }
        });

        document.getElementById('clearNotifications').addEventListener('click', (e) => {
            e.stopPropagation();
            notifications.clear();
        });

        // Close panel when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationsPanel.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationsPanel.classList.add('hidden');
            }
        });

        // Hook into existing functions to add notifications
        const originalUpdateBalance = updateBalance;
        updateBalance = async function() {
            await originalUpdateBalance();
            const expenses = parseFloat(document.getElementById('totalExpenses').textContent.replace(/[₱,]/g, '')) || 0;
            if (expenses > 200) {
                notifications.add('Daily expenses have exceeded ₱200!', 'warning');
            }
        };

        const originalMarkDebtAsPaid = markDebtAsPaid;
        markDebtAsPaid = async function(debtId, amount) {
            await originalMarkDebtAsPaid(debtId, amount);
            notifications.add(`Debt payment of ₱${amount.toLocaleString('en-PH', { minimumFractionDigits: 2 })} recorded.`, 'success');
        };

        // Check for upcoming debt due dates
        function checkUpcomingDueDates() {
            fetch('get_debts.php')
            .then(response => response.json())
            .then(debts => {
                const today = new Date();
                debts.forEach(debt => {
                    if (debt.due_date) {
                        const dueDate = new Date(debt.due_date);
                        const daysUntilDue = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
                        
                        if (daysUntilDue <= 3 && daysUntilDue > 0) {
                            notifications.add(
                                `Debt payment of ₱${parseFloat(debt.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })} for ${debt.debtor_name} is due in ${daysUntilDue} day${daysUntilDue === 1 ? '' : 's'}!`,
                                'warning'
                            );
                        }
                    }
                });
            });
        }

        // Check for upcoming due dates every hour
        checkUpcomingDueDates();
        setInterval(checkUpcomingDueDates, 3600000);
        // Settings functionality
        function toggleSettings() {
            const settingsPanel = document.getElementById('settingsPanel');
            settingsPanel.classList.toggle('hidden');
        }

        document.getElementById('settingsBtn').addEventListener('click', () => {
            toggleSettings();
        });

        // Close settings when clicking outside
        document.getElementById('settingsPanel').addEventListener('click', (e) => {
            if (e.target.id === 'settingsPanel') {
                toggleSettings();
            }
        });

        // Theme settings
        function setTheme(theme) {
            const root = document.documentElement;
            switch(theme) {
                case 'default':
                    root.style.setProperty('--primary', '#7C3AED');
                    root.style.setProperty('--accent', '#F471FF');
                    break;
                case 'nature':
                    root.style.setProperty('--primary', '#10B981');
                    root.style.setProperty('--accent', '#3B82F6');
                    break;
                case 'sunset':
                    root.style.setProperty('--primary', '#EF4444');
                    root.style.setProperty('--accent', '#F59E0B');
                    break;
            }
            localStorage.setItem('theme', theme);
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        }

        // Display settings
        function toggleFeature(feature, enabled) {
            switch(feature) {
                case 'animations':
                    document.body.classList.toggle('reduce-animations', !enabled);
                    break;
                case 'balance':
                    document.querySelectorAll('.balance-amount').forEach(el => {
                        el.classList.toggle('hidden', !enabled);
                    });
                    break;
                case 'compact':
                    document.body.classList.toggle('compact-view', enabled);
                    break;
            }
            localStorage.setItem(feature + 'Enabled', enabled);
        }

        // Initialize toggles from localStorage
        ['animations', 'balance', 'compact'].forEach(feature => {
            const enabled = localStorage.getItem(feature + 'Enabled') === 'true';
            document.getElementById('toggle' + feature.charAt(0).toUpperCase() + feature.slice(1)).checked = enabled;
            toggleFeature(feature, enabled);
        });

        // Add event listeners for toggles
        document.getElementById('toggleAnimations').addEventListener('change', (e) => {
            toggleFeature('animations', e.target.checked);
        });

        document.getElementById('toggleBalance').addEventListener('change', (e) => {
            toggleFeature('balance', e.target.checked);
        });

        document.getElementById('toggleCompact').addEventListener('change', (e) => {
            toggleFeature('compact', e.target.checked);
        });

        // Currency format settings
        document.getElementById('currencyFormat').addEventListener('change', (e) => {
            localStorage.setItem('currencyFormat', e.target.value);
            location.reload(); // Reload to apply new currency format
        });

        // Load saved currency format
        const savedCurrency = localStorage.getItem('currencyFormat');
        if (savedCurrency) {
            document.getElementById('currencyFormat').value = savedCurrency;
        }

        // Budget settings
        document.getElementById('dailyLimit').addEventListener('change', (e) => {
            const limit = parseFloat(e.target.value);
            localStorage.setItem('dailyLimit', limit);
        });

        document.getElementById('toggleAlerts').addEventListener('change', (e) => {
            localStorage.setItem('alertsEnabled', e.target.checked);
        });

        // Load saved budget settings
        const savedLimit = localStorage.getItem('dailyLimit');
        if (savedLimit) {
            document.getElementById('dailyLimit').value = savedLimit;
        }
        document.getElementById('toggleAlerts').checked = localStorage.getItem('alertsEnabled') !== 'false';

        // Save all settings
        function saveSettings() {
            notifications.add('Settings saved successfully', 'success');
            toggleSettings();
        }

    </script>

    <!-- Delete Transaction Confirmation Modal -->
    <div id="deleteTransactionModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-dark-card rounded-xl p-6 w-full max-w-md scale-100 opacity-100 transform transition-all duration-300 hover:scale-[1.02]">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500 animate-[pulse_2s_ease-in-out_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Delete Transaction</h3>
                <p class="text-gray-400 mb-2">Are you sure you want to delete this transaction?</p>
                <div class="bg-dark/50 rounded-lg p-3 mb-4 space-y-1">
                    <p class="text-sm text-gray-400">Amount</p>
                    <p id="deleteTransactionAmount" class="text-xl font-bold text-red-500">₱0.00</p>
                    <p class="text-sm text-gray-400">Category</p>
                    <p id="deleteTransactionCategory" class="text-base font-medium">Category</p>
                </div>
                <p class="text-sm text-red-400">This action cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button id="cancelDelete" class="flex-1 px-4 py-2 rounded-lg border border-gray-600 bg-dark hover:bg-gray-800 transition-colors duration-200 group">
                    <span class="group-hover:-translate-x-0.5 transition-transform duration-200 inline-block">Cancel</span>
                </button>
                <button id="confirmDelete" class="flex-1 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 transition-all duration-200 group">
                    <span class="group-hover:translate-x-0.5 transition-transform duration-200 inline-block">Delete</span>
                </button>
            </div>
        </div>
    </div>
</body>
</html>