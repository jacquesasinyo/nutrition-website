@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Yeni Yiyecek Ekle</h1>

    <form action="{{ route('food.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg">
        @csrf
        <div class="mb-4">
            <label for="turkish_description" class="block text-gray-700 text-sm font-bold mb-2">Türkçe Açıklama:</label>
            <input type="text" name="turkish_description" id="turkish_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">English Description:</label>
            <input type="text" name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
            <input type="text" name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="data_type" class="block text-gray-700 text-sm font-bold mb-2">Data Type:</label>
            <input type="text" name="data_type" id="data_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="fdc_id" class="block text-gray-700 text-sm font-bold mb-2">FDC ID:</label>
            <input type="number" name="fdc_id" id="fdc_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Photo:</label>
            <input type="file" name="photo" id="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-xs mt-1">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Food Item
            </button>
            <a href="{{ route('food.index') }}" class="text-blue-500 hover:text-blue-700">Back to List</a>
        </div>
    </form>
</div>
@endsection
