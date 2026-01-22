<x-app-layout>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Dashboard</h1>

        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

        <hr class="my-4">

        @if(app()->isLocal())
            <h3 class="font-semibold">Google Access Token</h3>
            <pre class="bg-gray-100 p-2 text-xs overflow-x-auto">
{{ session('google_access_token') }}
            </pre>

            <h3 class="font-semibold mt-4">Laravel API Token</h3>
            <pre class="bg-gray-100 p-2 text-xs overflow-x-auto">
{{ session('api_token') }}
            </pre>
        @endif
    </div>
</x-app-layout>
