<div>
    @if ($getRecord()?->qrCode?->img_path)
        <p style="padding: 10px 0;">Barcode</p>
        <img
            src="{{ Storage::disk('public')->url($getRecord()->qrCode->img_path) }}"
            style=" height: 160px;"
            alt="QRcode"
        />
    @else
        <p>Tidak ada qr code.</p>
        <p>Silakan tambahkan data karyawan terlebih dahulu untuk menghasilkan qr code.</p>
    @endif
</div>
