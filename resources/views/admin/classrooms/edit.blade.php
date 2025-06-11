@extends('admin.layout')

@section('title', 'Edit Class')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Kelas</h2>
            <a href="{{ route('admin.classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Kelas
            </a>
        </div>

        <form method="POST" action="{{ route('admin.classrooms.update', $classRoom) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                <!-- Class Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $classRoom->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $classRoom->subject) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                <!-- Teacher -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Guru</label>
                    <select name="teacher_id" id="teacher_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Pilih guru...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $classRoom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }} - {{ $teacher->subject }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Kelas</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required onchange="togglePriceField()">
                        <option value="">Pilih tipe...</option>
                        <option value="reguler" {{ old('type', $classRoom->type) === 'reguler' ? 'selected' : '' }}>Regular</option>
                        <option value="bimbel" {{ old('type', $classRoom->type) === 'bimbel' ? 'selected' : '' }}>Bimbel</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price (only for bimbel) -->
                <div id="priceField" class="{{ old('type', $classRoom->type) === 'bimbel' ? '' : 'hidden' }}">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $classRoom->price) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" min="0">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Schedule -->
                <div>
                    <label for="schedule" class="block text-sm font-medium text-gray-700 mb-2">Jadwal</label>
                    <input type="text" name="schedule" id="schedule" value="{{ old('schedule', $classRoom->schedule) }}" 
                           placeholder="contoh: Senin & Rabu 16:00-18:00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('schedule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $classRoom->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Perbarui Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePriceField() {
    const typeSelect = document.getElementById('type');
    const priceField = document.getElementById('priceField');
    
    if (typeSelect.value === 'bimbel') {
        priceField.classList.remove('hidden');
    } else {
        priceField.classList.add('hidden');
    }
}
</script>
@endsection
