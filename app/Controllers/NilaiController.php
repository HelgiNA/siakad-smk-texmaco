<?php

namespace App\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class NilaiController extends Controller
{
    public function create()
    {
        $guru_id = $this->request->session('user_id');
        $tahun_ajaran_aktif = TahunAjaran::getActive();
        $jadwal = Jadwal::findByGuru($guru_id, $tahun_ajaran_aktif['id']);

        $this->view('nilai/create', [
            'jadwal' => $jadwal
        ]);
    }

    public function input()
    {
        $kelas_id = $this->request->get('kelas_id');
        $mapel_id = $this->request->get('mapel_id');

        $siswa = Siswa::getByKelas($kelas_id);
        $nilai = Nilai::getByKelasMapel($kelas_id, $mapel_id);

        $this->view('nilai/input', [
            'siswa' => $siswa,
            'nilai' => $nilai,
            'kelas_id' => $kelas_id,
            'mapel_id' => $mapel_id
        ]);
    }

    public function store()
    {
        $data = $this->request->post();
        $nilai = [];

        foreach ($data['nilai'] as $siswa_id => $row) {
            if ($row['tugas'] < 0 || $row['tugas'] > 100 ||
                $row['uts'] < 0 || $row['uts'] > 100 ||
                $row['uas'] < 0 || $row['uas'] > 100) {
                return $this->redirect('/nilai/create')->with('error', 'Nilai harus di antara 0 dan 100.');
            }

            $nilai[] = [
                'siswa_id' => $siswa_id,
                'mapel_id' => $data['mapel_id'],
                'tahun_id' => TahunAjaran::getActive()['id'],
                'tugas' => $row['tugas'],
                'uts' => $row['uts'],
                'uas' => $row['uas'],
            ];
        }

        if (Nilai::saveBatch($nilai)) {
            return $this->redirect('/nilai/create')->with('success', 'Nilai berhasil disimpan.');
        } else {
            return $this->redirect('/nilai/create')->with('error', 'Gagal menyimpan nilai.');
        }
    }
}
