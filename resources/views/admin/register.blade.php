<x-layouts.app :title="__('Register')">
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-50">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
            
            <!-- Title -->
            <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-6">Create Account</h2>
            <p class="text-center text-gray-500 mb-6">Join us and get started right away 🚀</p>

            <!-- Success message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition"
                        placeholder="Enter your name"
                        required
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition"
                        placeholder="your@email.com"
                        required
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition transform hover:scale-[1.02] shadow-md"
                >
                    Register
                </button>
            </form>

            <!-- Login link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
            </p>
        </div>
    </div>
</x-layouts.app>
