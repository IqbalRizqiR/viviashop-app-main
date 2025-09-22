<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmptyDatabase extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'db:empty';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Kosongkan semua data dari tabel di database, tetapi biarkan struktur tabel tetap utuh.';

    /**
     * Jalankan console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SEMUA DATA dari database? Ini tidak bisa dibatalkan.')) {
            
            // Nonaktifkan foreign key checks untuk menghindari error
            Schema::disableForeignKeyConstraints();

            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

            foreach ($tables as $table) {
                // Jangan kosongkan tabel migrasi
                if ($table == 'migrations') {
                    continue;
                }
                DB::table($table)->truncate();
                $this->info("Tabel '$table' telah dikosongkan.");
            }

            // Aktifkan kembali foreign key checks
            Schema::enableForeignKeyConstraints();

            $this->info('SEMUA TABEL BERHASIL DIKOSONGKAN.');
        } else {
            $this->info('Proses dibatalkan.');
        }

        return 0;
    }
}