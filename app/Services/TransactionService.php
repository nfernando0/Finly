<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;

class TransactionService
{
    /**
     * Import mutasi rekening myBCA dari file CSV.
     *
     * @param UploadedFile $file File CSV yang diunggah
     * @param User $user User yang memiliki transaksi
     * @return int Jumlah baris yang berhasil diimpor
     */
    public function importMyBcaCsv(UploadedFile $file, User $user): int
    {
        $lines = file($file->getRealPath());
        if (empty($lines)) {
            throw new Exception(__('The file is empty.'));
        }
        
        $delimiter = ',';
        if (strpos($lines[0], ';') !== false) {
            $delimiter = ';';
        }

        $handle = fopen($file->getRealPath(), 'r');
        $imported = 0;
        $inTable = false;

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            // Check if this is the header row
            if (!$inTable) {
                $imploded = strtolower(implode(' ', $row));
                if (str_contains($imploded, 'tanggal') || str_contains($imploded, 'keterangan') || str_contains($imploded, 'date')) {
                    $inTable = true;
                    continue;
                }
            }

            if ($inTable) {
                // If the row doesn't match a valid length, skip
                if (count($row) < 3) continue;

                $tanggal = trim($row[0] ?? '');
                $keterangan = trim($row[1] ?? '');
                
                // Usually BCA mutasi is in index 3, but let's check index 2 or 3
                $jumlahRow = trim($row[3] ?? ($row[2] ?? ''));
                
                if (empty($tanggal) || empty($jumlahRow)) continue;

                $isDebit = false;
                $isCredit = false;
                
                $rowStr = strtoupper(implode(' ', $row));
                if (str_contains($rowStr, ' DB ') || str_contains($rowStr, 'DEBIT') || str_ends_with(strtoupper($jumlahRow), 'DB')) {
                    $isDebit = true;
                } elseif (str_contains($rowStr, ' CR ') || str_contains($rowStr, 'KREDIT') || str_ends_with(strtoupper($jumlahRow), 'CR')) {
                    $isCredit = true;
                }

                $amountRaw = preg_replace('/[^0-9.\-]/', '', str_replace(',', '', $jumlahRow));
                if (!is_numeric($amountRaw) || (float)$amountRaw == 0) {
                    $jumlahRow = trim($row[2] ?? '');
                    if (str_ends_with(strtoupper($jumlahRow), 'DB')) $isDebit = true;
                    if (str_ends_with(strtoupper($jumlahRow), 'CR')) $isCredit = true;
                    $amountRaw = preg_replace('/[^0-9.\-]/', '', str_replace(',', '', $jumlahRow));
                }

                $amount = abs((float) $amountRaw);
                if ($amount == 0) continue;

                $type = 'expense';
                if ($isCredit || $amountRaw < 0) {
                    $type = 'income';
                }

                $parsedDate = $this->parseBcaDate($tanggal);

                if ($parsedDate) {
                    $user->transactions()->create([
                        'type' => $type,
                        'amount' => $amount,
                        'transaction_date' => $parsedDate,
                        'status' => 'posted',
                        'description' => substr($keterangan, 0, 255),
                        'reference' => 'Import myBCA',
                    ]);
                    $imported++;
                }
            }
        }

        fclose($handle);

        return $imported;
    }

    /**
     * Membantu melakukan parsing format tanggal BCA.
     */
    private function parseBcaDate(string $tanggal): ?Carbon
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $tanggal);
        } catch (Exception $e) {
            try {
                return Carbon::createFromFormat('d/m/y', $tanggal);
            } catch (Exception $e2) {
                try {
                    $date = Carbon::createFromFormat('d/m', $tanggal);
                    $date->year(date('Y'));
                    return $date;
                } catch (Exception $e3) {
                    try {
                        return Carbon::parse($tanggal);
                    } catch (Exception $e4) {
                        return null;
                    }
                }
            }
        }
    }
}
