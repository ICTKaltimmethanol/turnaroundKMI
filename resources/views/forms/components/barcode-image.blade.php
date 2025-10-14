<div>
    @if ($getRecord()?->qrCode?->img_path)
        <p style="padding: 10px 0;">Barcode</p>
        <img
            src="{{ Storage::disk('public')->url($getRecord()->qrCode->img_path) }}"
            style="width: 160px; height: 60px;"
            alt="Barcode"
        />
    @else
        <p>Tidak ada barcode.</p>
        <p>Silakan tambahkan data karyawan terlebih dahulu untuk menghasilkan barcode.</p>
    @endif
</div>
