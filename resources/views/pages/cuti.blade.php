@extends('welcome')

@section('title', 'Cuti')

@section('content')
    <div class="mb-12 max-w-md mx-auto">
        <div class="flex justify-between items-center py-4">
            <p class="font-bold text-2xl text-white">Ajukan Cuti </p>
            <div class=" border border-white rounded-lg p-1 bg-white text-sm hover:bg-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>

        </div>
        @if (session('success'))
            <div class="mb-4 p-3 text-sm text-green-800 bg-green-100 border border-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 border border-red-300 rounded-md">
                {{ session('error') }}
            </div>  
        @endif
        <form action="{{ route('cuti.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 w-full mx-auto space-y-4">
            @csrf

            <div>
                <label for="time_off_action" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aksi Cuti</label>
                <select id="time_off_action" name="time_off_action" class="mt-1 py-2 px-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="sakit">Sakit</option>
                    <option value="melahirkan">Melahirkan</option>
                    <option value="duka">Duka</option>
                </select>
            </div>

            <input type="text" name="time_off_status" value="pending" hidden>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="mt-1 py-2 px-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Jelaskan alasan cuti Anda..."></textarea>
            </div>  
            <div>
                <button type="submit" class="w-full bg-blue-900 text-white py-2 px-4 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Ajukan Cuti</button>             
            </div>
        </form>
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6 py-4">
                @forelse ($timeOffs as $to)
                    
                    @php
                    \Carbon\Carbon::setLocale('id');
                        $date = \Carbon\Carbon::parse($to->created_at)->translatedFormat('l, d F Y');
                    $statusColor = 'yellow';
                    if ($to->time_off_status === 'approved') {
                        $statusColor = 'green';
                    } elseif ($to->time_off_status === 'rejected') {
                        $statusColor = 'red';
                    }
                    @endphp



                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 w-full mx-auto space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-md ">{{ $date }}</p>
                            <div class="bg-{{ $statusColor }}-100 rounded-full border border-{{ $statusColor }}-300 p-1 px-2 text-{{ $statusColor }}-800 text-xs">{{ $to->time_off_status }}</div>
                        </div>
                        <hr class="border border-gray-300">
                        
                            <div class="flex items-center bg-blue-100 py-2 pr-8 pl-4 rounded-xl ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-gray-900">{{ $to->time_off_action}}</p>
                                    <p class="text-md  text-gray-800">{{ $to->description }}</p>
                                </div>
                            </div>
              

                        
                    </div>
                @empty
                    <div class=" py-1 px-4 bg-white rounded-full border border-gray-700 mt-2">
                        <p class="text-gray-500 text-center">Belum pernah membuat izin.</p>
                    </div>
                @endforelse
            </div>
    </div>  
    </div>

    
@endsection