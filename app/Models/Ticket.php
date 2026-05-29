<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; // Buka semua gembok kolom

    // ========================================================
    // KONSTANTA STATUS TIKET (GLPI STANDARDS)
    // ========================================================
    public const STATUS_NEW            = 1;
    public const STATUS_ASSIGNED       = 2; // Processing (Assigned)
    public const STATUS_PLANNED        = 3; // Processing (Planned)
    public const STATUS_PENDING        = 4; // Menunggu feedback user / vendor
    public const STATUS_SOLVED         = 5; // Solved (Menunggu approval user)
    public const STATUS_CLOSED         = 6; // Closed (Selesai permanen)

    // ========================================================
    // KONSTANTA TIPE TIKET
    // ========================================================
    public const TYPE_INCIDENT = 1; // Gangguan / Sesuatu yang rusak
    public const TYPE_REQUEST  = 2; // Permintaan Layanan / Sesuatu yang baru

    // ========================================================
    // RELASI DATABASE
    // ========================================================
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function observer()
    {
        return $this->belongsTo(User::class, 'observer_id');
    }

    // ========================================================
    // OTAK GLPI: AUTO-CALCULATE PRIORITY MATRIX
    // ========================================================
    protected static function booted()
    {
        // Event 'saving' berjalan setiap kali tiket akan di-Create atau di-Update
        static::saving(function ($ticket) {
            $ticket->priority = self::computePriority($ticket->urgency, $ticket->impact);
        });
    }

    public static function computePriority($urgency, $impact)
    {
        // Matrix Array [Urgency][Impact]
        $matrix = [
            5 => [1 => 3, 2 => 4, 3 => 5, 4 => 5, 5 => 5],
            4 => [1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 5],
            3 => [1 => 2, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
            2 => [1 => 1, 2 => 2, 3 => 2, 4 => 3, 5 => 4],
            1 => [1 => 1, 2 => 1, 3 => 2, 4 => 2, 5 => 3],
        ];

        // Fallback aman jika value di luar jangkauan 1-5
        if (!isset($matrix[$urgency]) || !isset($matrix[$urgency][$impact])) {
            return 3; // Kembalikan ke Medium (3)
        }

        return $matrix[$urgency][$impact];
    }
}
