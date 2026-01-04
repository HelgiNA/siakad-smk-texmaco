<?php
namespace App\Models;

require_once __DIR__ . "/../Models/Model.php";

use PDO;
use PDOException;

class DetailAbsensi extends Model
{
    protected $table = "detail_absensi";
    protected $primaryKey = "detail_id";
}
