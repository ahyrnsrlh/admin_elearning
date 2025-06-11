@extends('admin.layout')

@section('content')
<div class="space-y-6">    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kelas Baru</h1>
        <a href="{{ route('admin.classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali ke Kelas
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.classrooms.store') }}">
            @csrf
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                           class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('subject') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="type" id="type" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('type') ? 'border-red-500' : 'border-gray-300' }}"
                            onchange="togglePrice()">
                        <option value="">Pilih Tipe</option>
                        <option value="reguler" {{ old('type') === 'reguler' ? 'selected' : '' }}>Regular</option>
                        <option value="bimbel" {{ old('type') === 'bimbel' ? 'selected' : '' }}>Bimbel</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teacher -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700">Guru</label>
                    <select name="teacher_id" id="teacher_id" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('teacher_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Guru (Opsional)</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }} - {{ $teacher->subject }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price (only for bimbel) -->
                <div id="price-field" style="{{ old('type') === 'bimbel' ? '' : 'display: none;' }}">
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="1000"
                           class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="3" 
                          class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Kelas aktif
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Buat Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePrice() {
    const typeSelect = document.getElementById('type');
    const priceField = document.getElementById('price-field');
    
    if (typeSelect.value === 'bimbel') {
        priceField.style.display = 'block';
    } else {
        priceField.style.display = 'none';
        document.getElementById('price').value = '';
    }
}
</script>
@endsection
