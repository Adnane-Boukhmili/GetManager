<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <style>
                    
                    .card {
                        background-color: black;
                        color: white;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        padding: 20px;
                        width: 45%; 
                        margin: 10px;
                        display: inline-block;
                        text-align: center;
                        text-decoration: none;
                    }
            
                    .card:hover {
                        cursor: pointer; 
                    }
            
                    .card h2 {
                        font-size: 24px;
                    }
                </style>
            <a href="/pricing" class="card">
                <h2>Pricing</h2>
                <p>Explore our pricing options.</p>
            </a>
            
            <a href="/managing" class="card">
                <h2>Manage Employees</h2>
                <p>Efficiently manage your workforce.</p>
            </a>
            
            </div>
        </div>
    </div>
</x-app-layout>
