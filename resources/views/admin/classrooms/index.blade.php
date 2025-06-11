@extends('admin.layout')

@section('content')
<div class="space-y-6">    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kelas</h1>
        <a href="{{ route('admin.classrooms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tambah Kelas Baru
        </a>
    </div>

    <!-- Classes Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($classRooms as $classRoom)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $classRoom->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($classRoom->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($classRoom->type === 'bimbel')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Bimbel
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Regular
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">                        <div class="text-sm text-gray-900">
                            {{ $classRoom->teacher ? $classRoom->teacher->user->name : 'Belum ditugaskan' }}
                        </div>
                        @if($classRoom->teacher)
                            <div class="text-sm text-gray-500">{{ $classRoom->teacher->subject }}</div>
                        @endif
                    </td>                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $classRoom->students_count }} siswa</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($classRoom->price)
                                Rp {{ number_format($classRoom->price, 0, ',', '.') }}                            @else
                                Gratis
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">                        @if($classRoom->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.classrooms.show', $classRoom) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                        <a href="{{ route('admin.classrooms.edit', $classRoom) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.classrooms.destroy', $classRoom) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No classes found. <a href="{{ route('admin.classrooms.create') }}" class="text-blue-600 hover:text-blue-900">Add the first class</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($classRooms->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $classRooms->links() }}
    </div>
    @endif
</div>
@endsection
