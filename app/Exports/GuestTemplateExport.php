<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GuestTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Nama', 'No WA', 'VIP', 'Alamat/Instansi', 'Relasi'],
            ['Budi Santoso', '081234567890', 'Ya', 'PT Maju Terus', 'Teman Kerja'],
        ];
    }
}
