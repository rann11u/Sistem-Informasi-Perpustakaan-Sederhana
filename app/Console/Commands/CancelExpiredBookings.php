<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrowings:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan seluruh booking online yang melebihi batas waktu pengambilan 1x24 jam dan kembalikan stok buku';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa booking online yang telah kedaluwarsa (> 1 hari)...');

        $count = Borrowing::cancelExpiredBookings();

        if ($count > 0) {
            $this->info("Sukses: {$count} booking kedaluwarsa berhasil dibatalkan dan stok buku telah dikembalikan.");
        } else {
            $this->line('Tidak ada booking yang kedaluwarsa saat ini.');
        }

        return Command::SUCCESS;
    }
}
