<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Rujukan;
use App\Models\RumahSakit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tahun = (int) now()->format('Y');

        $pasienCount = $user->isSuperAdmin()
            ? Pasien::count()
            : Kunjungan::query()
                ->visibleTo($user)
                ->distinct('pasien_id')
                ->count('pasien_id');

        $rujukanKirimCount = $user->isSuperAdmin()
            ? Rujukan::count()
            : Rujukan::where('rumah_sakit_asal_id', $user->rumah_sakit_id)->count();

        $rujukanTerimaCount = $user->isSuperAdmin()
            ? RumahSakit::count()
            : Rujukan::where('rumah_sakit_tujuan_id', $user->rumah_sakit_id)->count();

        $pasienPerBulan = $this->monthlyPatientSeries($user, $tahun);
        $rujukanPerBulan = $this->monthlyReferralSeries($user, $tahun);

        return view('dashboard', [
            'tahun' => $tahun,
            'scopeLabel' => $user->isSuperAdmin()
                ? 'Platform semua rumah sakit'
                : ($user->rumahSakit->nama ?? 'Rumah sakit saya'),
            'pasienCount' => $pasienCount,
            'rujukanKirimLabel' => $user->isSuperAdmin() ? 'Total Rujukan' : 'Rujukan Dikirim',
            'rujukanKirimCount' => $rujukanKirimCount,
            'rujukanTerimaLabel' => $user->isSuperAdmin() ? 'Total Rumah Sakit' : 'Rujukan Diterima',
            'rujukanTerimaCount' => $rujukanTerimaCount,
            'seriesPasien' => $pasienPerBulan,
            'seriesRujukan' => $rujukanPerBulan,
        ]);
    }

    private function monthlyPatientSeries($user, int $year): array
    {
        $series = array_fill(1, 12, 0);

        if ($user->isSuperAdmin()) {
            Pasien::query()
                ->whereYear('created_at', $year)
                ->get(['created_at'])
                ->each(function (Pasien $pasien) use (&$series) {
                    if ($pasien->created_at) {
                        $series[(int) $pasien->created_at->format('n')]++;
                    }
                });

            return array_values($series);
        }

        Kunjungan::query()
            ->visibleTo($user)
            ->whereYear('tanggal_kunjungan', $year)
            ->get(['tanggal_kunjungan', 'pasien_id'])
            ->groupBy(function (Kunjungan $kunjungan) {
                return optional($kunjungan->tanggal_kunjungan)->format('n');
            })
            ->each(function ($kunjunganPerBulan, $month) use (&$series) {
                if ($month) {
                    $series[(int) $month] = $kunjunganPerBulan->pluck('pasien_id')->unique()->count();
                }
            });

        return array_values($series);
    }

    private function monthlyReferralSeries($user, int $year): array
    {
        $series = array_fill(1, 12, 0);

        Rujukan::query()
            ->visibleTo($user)
            ->whereYear('created_at', $year)
            ->get(['created_at'])
            ->each(function (Rujukan $rujukan) use (&$series) {
                if ($rujukan->created_at) {
                    $series[(int) $rujukan->created_at->format('n')]++;
                }
            });

        return array_values($series);
    }
}
