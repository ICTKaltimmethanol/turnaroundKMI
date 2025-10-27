<div>
    @if ($getRecord()?->qrCode?->img_path)
        <p style="padding: 10px 0;">QR Code</p>
        <img
            src="{{ Storage::disk('public')->url($getRecord()->qrCode->img_path) }}"
            style="height: 160px;"
            alt="QR Code"
        />
        <br><br>
        <!-- Tombol Download -->
        <a 
            href="{{ Storage::disk('public')->url($getRecord()->qrCode->img_path) }}" 
            download="QR_Code_{{ $getRecord()->full_name }}.png"
            style="display: inline-block; padding: 8px 16px; background-color: #4CAF50; color: white; border-radius: 4px; text-decoration: none;"
        >
            Download QR Code
        </a>
    @else
        <p>Tidak ada QR Code.</p>
        <p>Silakan tambahkan data karyawan terlebih dahulu untuk menghasilkan QR Code.</p>
    @endif
</div>
