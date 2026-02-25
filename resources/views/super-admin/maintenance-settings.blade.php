@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Maintenance Settings</h1>
        <a href="{{ route('super-admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Maintenance Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-cog mr-2 text-blue-500"></i>Maintenance Page Settings
            </h2>
            
            <form action="{{ route('super-admin.maintenance.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="page_title" class="block text-gray-700 text-sm font-bold mb-2">Page Title</label>
                    <input type="text" name="page_title" id="page_title" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                        value="{{ old('page_title', $settings->page_title) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="school_title" class="block text-gray-700 text-sm font-bold mb-2">School/Organization Title</label>
                    <input type="text" name="school_title" id="school_title" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                        value="{{ old('school_title', $settings->school_title) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="maintenance_message" class="block text-gray-700 text-sm font-bold mb-2">Maintenance Message</label>
                    <textarea name="maintenance_message" id="maintenance_message" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                        required>{{ old('maintenance_message', $settings->maintenance_message) }}</textarea>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Contact Email</label>
                    <input type="email" name="email" id="email" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                        value="{{ old('email', $settings->email) }}">
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Contact Phone</label>
                    <input type="text" name="phone" id="phone" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                        value="{{ old('phone', $settings->phone) }}">
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Maintenance Mode Toggle -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-power-off mr-2 text-yellow-500"></i>Maintenance Mode
            </h2>
            
            <div class="mb-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-semibold text-gray-800">Current Status</h3>
                        <p class="text-sm text-gray-600">
                            @if($settings->is_active)
                                <span class="text-red-500 font-semibold">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Maintenance Mode is ON
                                </span>
                            @else
                                <span class="text-green-500 font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>System is Running Normally
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="text-4xl">
                        @if($settings->is_active)
                            <i class="fas fa-tools text-red-500"></i>
                        @else
                            <i class="fas fa-check-circle text-green-500"></i>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                @if($settings->is_active)
                    <form action="{{ route('super-admin.maintenance.disable') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline">
                            <i class="fas fa-play mr-2"></i>Disable Maintenance Mode
                        </button>
                    </form>
                @else
                    <form action="{{ route('super-admin.maintenance.enable') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline">
                            <i class="fas fa-pause mr-2"></i>Enable Maintenance Mode
                        </button>
                    </form>
                @endif
            </div>
            
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Note:
                </h4>
                <p class="text-sm text-yellow-700">
                    When maintenance mode is enabled, all users (except administrators) will see the maintenance page. 
                    The database connection error will also automatically display this page.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Preview Section -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-eye mr-2 text-purple-500"></i>Preview
        </h2>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
            <iframe src="{{ url('/maintenance-preview') }}" class="w-full h-96 rounded" frameborder="0"></iframe>
        </div>
    </div>
</div>
@endsection
