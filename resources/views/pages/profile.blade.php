@extends('welcome') {{-- Sesuaikan layout jika perlu --}}

@section('title', 'ID Card')

@section('content')

        <div class="max-w-md p-4 bg-center bg-cover bg-no-repeat bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')] bg-gray-700 bg-blend-multiply rounded-xl shadow-lg overflow-hidden border border-gray-200">
            {{-- BODY --}}
            <div class="p-4 px-2 pt-2  rounded-lg text-center space-y-2">
                {{-- HEADER --}}
                <div class="rounded-lg text-white text-center py-2 pt-4">
                    <h2 class="text-xl font-bold italic uppercase tracking-wide text-blue-400">Turn <span class="text-red-400">Around</span> <span class="text-white">2025</span></h2>
                    <p class="text-sm">PT. Kaltim Methanol Industri</p>
                </div>
                {{-- QR Code --}}
                <div class=" bg-white p-4 rounded-lg border border-gray-200">
                    @if ($employee->qrCode && $employee->qrCode->img_path)
                        <img src="{{ asset('storage/' . $employee->qrCode->img_path) }}" alt="QR Code"
                            class="<w-6></w-6>0  mx-auto object-contain">
                        <p class="text-xs text-gray-500 mt-2">Scan untuk verifikasi</p>
                    @else
                        <p class="text-sm text-red-500">QR Code tidak tersedia</p>
                    @endif
                </div>

                {{-- Info Karyawan --}}
                <div class="text-gray-800 py-2">
                    <h3 class="text-xl font-bold text-gray-100">{{ $employee->full_name }}</h3>
                    <p class="text-md text-gray-200 italic">{{ $employee->company->name ?? 'Nama Perusahaan' }} - {{ $employee->position->name ?? 'Posisi' }}</p>
                    <p class="text-sm text-gray-300 ">{{ $employee->employees_code }}</p>
                </div>

                {{-- Icons 
                <div class=" flex justify-center items-center gap-6 py-2 bg-white rounded-lg border border-gray-200">
                    <img src="{{ asset('images/sojitz.png') }}" alt="Sojitz" class="h-5" />
                    <img src="{{ asset('images/daicel.png') }}" alt="Daicel" class="h-5" />
                    <img src="{{ asset('images/humpus.png') }}" alt="Humpus" class="h-5" />
                    <img src="{{ asset('images/engineer2.png') }}" alt="Engineer 2" class="h-5"/>
                </div>--}}
            </div>
        </div>
@endsection
