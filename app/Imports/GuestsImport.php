<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue
{
    private int $weddingId;

    public function __construct(int $weddingId)
    {
        $this->weddingId = $weddingId;
    }

    public function model(array $row)
    {
        if (empty($row['nama'])) {
            return null;
        }

        $isVip = false;
        if (!empty($row['vip']) && in_array(strtolower(trim($row['vip'])), ['y', 'yes', 'ya', '1', 'true', 'v'])) {
            $isVip = true;
        }

        return new Guest([
            'wedding_id' => $this->weddingId,
            'name' => $row['nama'],
            'whatsapp_number' => $row['no_wa'] ?? null,
            'is_vip' => $isVip,
            'company_or_address' => $row['alamatinstansi'] ?? null,
            'relationship' => $row['relasi'] ?? null,
            'secure_token' => Guest::generateToken(),
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
