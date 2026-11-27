<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'borrow_date',
        'due_date',
        'pickup_deadline',
        'return_date',
        'status',
        'is_extended',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date'     => 'date',
            'due_date'        => 'date',
            'pickup_deadline' => 'datetime',
            'return_date'     => 'date',
            'is_extended'     => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    /**
     * Memeriksa apakah booking online sudah kedaluwarsa (> 1 hari / lewat pickup_deadline)
     */
    public function isExpired(): bool
    {
        return $this->status === 'booked'
            && $this->pickup_deadline
            && $this->pickup_deadline->isPast();
    }

    /**
     * Membatalkan seluruh booking yang kedaluwarsa (> 1 hari) dan mengembalikan stok buku
     *
     * @return int Jumlah booking yang berhasil dibatalkan
     */
    public static function cancelExpiredBookings(): int
    {
        $expiredList = self::where('status', 'booked')
            ->whereNotNull('pickup_deadline')
            ->where('pickup_deadline', '<', now())
            ->with('details.book')
            ->get();

        $count = 0;
        foreach ($expiredList as $borrowing) {
            foreach ($borrowing->details as $detail) {
                if ($detail->book) {
                    $detail->book->increment('stock', $detail->quantity);
                }
            }
            $borrowing->status = 'cancelled';
            $borrowing->save();
            $count++;
        }

        return $count;
    }
}
