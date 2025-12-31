<?php
// app/Models/Mapel.php

namespace App\Models;

require_once __DIR__ . '/Model.php';

class Mapel extends Model
{
    protected $table      = 'mata_pelajaran';
    protected $primaryKey = 'mapel_id';

    public static function findByKode($kode)
    {
        $instance = new static();
        return $instance::where('kode_mapel', $kode)->first();
    }
}