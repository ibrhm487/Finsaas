<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        // Truncate existing journal entries
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        JournalDetail::truncate();
        JournalHeader::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $accounts = Account::where('company_id', $company->id)->get()->keyBy('kode_akun');
        $userId = User::where('company_id', $company->id)->first()->id;

        $kas = $accounts['111']->id;
        $bank = $accounts['112']->id;
        $piutang = $accounts['121']->id;
        $hutangUsaha = $accounts['211']->id;
        $modal = $accounts['311']->id;
        $pendapatan = $accounts['411']->id;
        $bebanOperasional = $accounts['511']->id;
        $bebanGaji = $accounts['512']->id;
        $bebanSewa = $accounts['513']->id;
        $pendapatanJasa = $accounts['412']->id;
        $perlengkapan = $accounts['122']->id;
        $peralatan = $accounts['123']->id;
        $hutangBank = $accounts['212']->id;
        $labaDitahan = $accounts['312']->id;
        $bebanListrik = $accounts['514']->id;

        $counter = 1;

        // === HELPER ===
        $buatJurnal = function ($tgl, $keterangan, array $details) use ($company, $userId, &$counter) {
            $header = JournalHeader::create([
                'company_id' => $company->id,
                'nomor_jurnal' => 'JU-2026-' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
                'tanggal' => $tgl,
                'keterangan' => $keterangan,
                'user_id' => $userId,
            ]);

            $totalDebit = 0;
            $totalKredit = 0;
            foreach ($details as $d) {
                JournalDetail::create([
                    'journal_header_id' => $header->id,
                    'akun_id' => $d[0],
                    'debit' => $d[1],
                    'kredit' => $d[2],
                ]);
                $totalDebit += $d[1];
                $totalKredit += $d[2];
            }
        };

        // === MONTHLY DATA ===
        // Each month: revenue + expenses
        // Revenue pattern: grows Jan-Jun, dips Jul-Aug, recovers Sep-Dec
        $monthlyRevenue = [
            1 => ['jasa' => 45000000, 'produk' => 25000000],
            2 => ['jasa' => 48000000, 'produk' => 22000000],
            3 => ['jasa' => 52000000, 'produk' => 28000000],
            4 => ['jasa' => 55000000, 'produk' => 30000000],
            5 => ['jasa' => 58000000, 'produk' => 27000000],
            6 => ['jasa' => 60000000, 'produk' => 32000000],
            7 => ['jasa' => 42000000, 'produk' => 20000000],
            8 => ['jasa' => 38000000, 'produk' => 18000000],
            9 => ['jasa' => 50000000, 'produk' => 26000000],
            10 => ['jasa' => 54000000, 'produk' => 29000000],
            11 => ['jasa' => 62000000, 'produk' => 35000000],
            12 => ['jasa' => 70000000, 'produk' => 40000000],
        ];

        $monthlyExpenses = [
            1 => ['gaji' => 15000000, 'sewa' => 10000000, 'operasional' => 8000000, 'listrik' => 3000000],
            2 => ['gaji' => 15000000, 'sewa' => 10000000, 'operasional' => 7500000, 'listrik' => 2800000],
            3 => ['gaji' => 15500000, 'sewa' => 10000000, 'operasional' => 9000000, 'listrik' => 3200000],
            4 => ['gaji' => 15500000, 'sewa' => 10000000, 'operasional' => 8500000, 'listrik' => 3000000],
            5 => ['gaji' => 16000000, 'sewa' => 10000000, 'operasional' => 8800000, 'listrik' => 3500000],
            6 => ['gaji' => 16000000, 'sewa' => 10000000, 'operasional' => 8200000, 'listrik' => 4000000],
            7 => ['gaji' => 16000000, 'sewa' => 10000000, 'operasional' => 7000000, 'listrik' => 5000000],
            8 => ['gaji' => 16000000, 'sewa' => 10000000, 'operasional' => 7500000, 'listrik' => 4800000],
            9 => ['gaji' => 16500000, 'sewa' => 10000000, 'operasional' => 8000000, 'listrik' => 3500000],
            10 => ['gaji' => 16500000, 'sewa' => 10000000, 'operasional' => 8500000, 'listrik' => 3000000],
            11 => ['gaji' => 17000000, 'sewa' => 10000000, 'operasional' => 9500000, 'listrik' => 3200000],
            12 => ['gaji' => 17000000, 'sewa' => 10000000, 'operasional' => 10000000, 'listrik' => 3500000],
        ];

        // --- JANUARY: Opening balances + first month operations ---
        $tgl = function ($day) { return "2026-01-" . str_pad($day, 2, '0', STR_PAD_LEFT); };

        // Opening balance: Setoran modal
        $buatJurnal($tgl(1), 'Setoran modal awal', [
            [$kas, 200000000, 0],
            [$modal, 0, 200000000],
        ]);

        // Pembelian perlengkapan & peralatan
        $buatJurnal($tgl(2), 'Pembelian perlengkapan kantor', [
            [$perlengkapan, 15000000, 0],
            [$kas, 0, 15000000],
        ]);
        $buatJurnal($tgl(3), 'Pembelian peralatan kantor', [
            [$peralatan, 50000000, 0],
            [$hutangBank, 0, 50000000],
        ]);

        // Revenue
        $rev = $monthlyRevenue[1];
        $buatJurnal($tgl(5), 'Pendapatan jasa konsultasi', [
            [$kas, $rev['jasa'], 0],
            [$pendapatanJasa, 0, $rev['jasa']],
        ]);
        $buatJurnal($tgl(8), 'Pendapatan penjualan produk', [
            [$piutang, $rev['produk'], 0],
            [$pendapatan, 0, $rev['produk']],
        ]);

        // Expenses
        $exp = $monthlyExpenses[1];
        $buatJurnal($tgl(10), 'Pembayaran gaji karyawan', [
            [$bebanGaji, $exp['gaji'], 0],
            [$kas, 0, $exp['gaji']],
        ]);
        $buatJurnal($tgl(12), 'Pembayaran sewa kantor', [
            [$bebanSewa, $exp['sewa'], 0],
            [$kas, 0, $exp['sewa']],
        ]);
        $buatJurnal($tgl(15), 'Pembayaran beban operasional', [
            [$bebanOperasional, $exp['operasional'], 0],
            [$kas, 0, $exp['operasional']],
        ]);
        $buatJurnal($tgl(20), 'Pembayaran listrik & air', [
            [$bebanListrik, $exp['listrik'], 0],
            [$kas, 0, $exp['listrik']],
        ]);

        // Penerimaan piutang
        $buatJurnal($tgl(25), 'Penerimaan pembayaran piutang', [
            [$kas, 15000000, 0],
            [$piutang, 0, 15000000],
        ]);

        for ($m = 2; $m <= 12; $m++) {
            $tgl = function ($day) use ($m) {
                return "2026-" . str_pad($m, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            };

            $rev = $monthlyRevenue[$m];
            $exp = $monthlyExpenses[$m];

            // Revenue entries
            $buatJurnal($tgl(3), 'Pendapatan jasa konsultasi', [
                [$kas, $rev['jasa'], 0],
                [$pendapatanJasa, 0, $rev['jasa']],
            ]);
            $buatJurnal($tgl(7), 'Pendapatan penjualan produk', [
                [$piutang, $rev['produk'], 0],
                [$pendapatan, 0, $rev['produk']],
            ]);

            // Expense entries
            $buatJurnal($tgl(10), 'Pembayaran gaji karyawan', [
                [$bebanGaji, $exp['gaji'], 0],
                [$kas, 0, $exp['gaji']],
            ]);
            $buatJurnal($tgl(12), 'Pembayaran sewa kantor', [
                [$bebanSewa, $exp['sewa'], 0],
                [$kas, 0, $exp['sewa']],
            ]);
            $buatJurnal($tgl(15), 'Pembayaran beban operasional', [
                [$bebanOperasional, $exp['operasional'], 0],
                [$kas, 0, $exp['operasional']],
            ]);
            $buatJurnal($tgl(20), 'Pembayaran listrik & air', [
                [$bebanListrik, $exp['listrik'], 0],
                [$kas, 0, $exp['listrik']],
            ]);

            // Penerimaan piutang (setiap 2 bulan)
            if ($m % 2 == 0) {
                $amount = $monthlyRevenue[$m - 1]['produk'] + $monthlyRevenue[$m]['produk'];
                $buatJurnal($tgl(25), 'Penerimaan pembayaran piutang', [
                    [$kas, $amount, 0],
                    [$piutang, 0, $amount],
                ]);
            }

            // Pembayaran hutang (setiap 3 bulan)
            if ($m % 3 == 0) {
                $buatJurnal($tgl(28), 'Pembayaran angsuran hutang bank', [
                    [$hutangBank, 10000000, 0],
                    [$kas, 0, 10000000],
                ]);
            }
        }
    }
}
