<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - KiGadGet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold flex items-center">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    KiGadGet
                </a>

                <!-- Menu -->
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="{{ route('home') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('shop') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-store mr-1"></i> Shop
                    </a>
                    
                    @auth
                        @if(auth()->user()->isCustomer())
                            <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                            <a href="{{ route('customer.orders') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-shopping-bag mr-1"></i> Pesanan Saya
                            </a>
                            <a href="{{ route('cart.index') }}" class="hover:text-blue-200 transition relative">
                                <i class="fas fa-shopping-cart text-xl"></i>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-user-shield mr-1"></i> Admin Panel
                            </a>
                        @endif
                        
                        <!-- Profile Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 hover:text-blue-200 transition">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-200 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <a href="{{ route('home') }}" class="block py-2 hover:text-blue-200">Home</a>
                <a href="{{ route('shop') }}" class="block py-2 hover:text-blue-200">Shop</a>
                @auth
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="block py-2 hover:text-blue-200">Dashboard</a>
                        <a href="{{ route('customer.orders') }}" class="block py-2 hover:text-blue-200">Pesanan Saya</a>
                        <a href="{{ route('cart.index') }}" class="block py-2 hover:text-blue-200">Keranjang</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="block py-2 hover:text-blue-200">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 hover:text-blue-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 hover:text-blue-200">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 hover:text-blue-200">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Terjadi Kesalahan!</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">KiGadGet</h3>
                    <p class="text-gray-400">Toko handphone terpercaya dengan produk original dan harga terbaik.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('shop') }}" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@kigadget.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; 2024 KiGadGet. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>