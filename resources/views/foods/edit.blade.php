@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Yiyeceği Düzenle</h1>

        <form action="{{ route('food.update', $food->id) }}" method="POST" enctype="multipart/form-data" class="max-w-lg">
            @csrf
            @method('PUT')
            <input type="hidden" name="page" value="{{ $page }}">

            <div class="mb-4">
                <label for="turkish_description" class="block text-gray-700 text-sm font-bold mb-2">Turkish Description:</label>
                <input type="text" name="turkish_description" id="turkish_description" value="{{ $food->turkish_description }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">English Description:</label>
                <input type="text" name="description" id="description" value="{{ $food->description }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                <input type="text" name="category" id="category" value="{{ $food->category }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="data_type" class="block text-gray-700 text-sm font-bold mb-2">Data Type:</label>
                <input type="text" name="data_type" id="data_type" value="{{ $food->data_type }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="fdc_id" class="block text-gray-700 text-sm font-bold mb-2">FDC ID:</label>
                <input type="text" name="fdc_id" id="fdc_id" value="{{ $food->fdc_id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Photo:</label>
                @if($food->photo)
                    <div class="mb-2">
                        <img src="{{ asset($food->photo) }}" alt="{{ $food->description }}" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="photo" id="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-gray-600 text-xs mt-1">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Food Item
                </button>
                <a href="{{ route('food.index', ['page' => $page]) }}" class="text-blue-500 hover:text-blue-700">Back to List</a>
            </div>
        </form>
    </div>
@endsection
