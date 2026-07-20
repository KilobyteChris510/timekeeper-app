@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700 max-w-md mx-auto">
    <h2 class="text-xl font-medium text-gray-200 mb-6">Admin Login</h2>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autofocus
                class="w-full bg-[#1a1a1a] border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:border-gray-400"
            >
            @error('password')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition">
            Sign In
        </button>
    </form>
</div>
@endsection
