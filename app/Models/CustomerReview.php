<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    use HasFactory;

    protected $table = 'customer_reviews';

    protected $fillable = [
        'order_id',
        'order_type',
        'user_id',
        'customer_name',
        'customer_contact',
        'product_name',
        'rating',
        'review_text',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get partially masked email or contact for public credibility (e.g. gab***7@gmail.com)
     */
    public function getMaskedContactAttribute(): string
    {
        // 1. Check direct customer_contact or registered user email
        $contact = $this->customer_contact ?: ($this->user?->email ?? null);

        // 2. Fallback: check target_id from TopupTransaction
        if (empty($contact) && !empty($this->order_id)) {
            $trx = \App\Models\TopupTransaction::where('order_id', $this->order_id)->first();
            if ($trx && !empty($trx->target_id)) {
                $contact = $trx->target_id;
            }
        }

        // 3. Mask valid Email
        if ($contact && filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $contact);
            $name = $parts[0];
            $domain = $parts[1] ?? 'gmail.com';
            $len = strlen($name);

            if ($len <= 2) {
                $masked = substr($name, 0, 1) . '***';
            } elseif ($len <= 4) {
                $masked = substr($name, 0, 2) . '***';
            } else {
                $masked = substr($name, 0, 3) . '***' . substr($name, -1);
            }

            return $masked . '@' . $domain;
        }

        // 4. Mask Phone Number (e.g. 0812****889)
        if ($contact && preg_match('/^[0-9+]+$/', $contact)) {
            $digits = preg_replace('/[^0-9]/', '', $contact);
            $len = strlen($digits);
            if ($len >= 9) {
                return substr($digits, 0, 4) . '****' . substr($digits, -3);
            }
            return substr($digits, 0, 2) . '***' . substr($digits, -1);
        }

        // 5. Existing non-empty contact string
        if (!empty($contact)) {
            $len = strlen($contact);
            if ($len > 4) {
                return substr($contact, 0, 3) . '***' . substr($contact, -1);
            }
            return substr($contact, 0, 1) . '***';
        }

        // 6. Fallback based on customer name (e.g. joh***e@gmail.com)
        $clean = preg_replace('/[^a-z0-9]/', '', strtolower($this->customer_name ?: 'user'));
        if (strlen($clean) < 3) {
            $clean = 'user' . (($this->id ?? 1) % 900 + 100);
        }
        $prefix = substr($clean, 0, 3);
        $suffix = substr($clean, -1);
        $domain = (($this->id ?? 1) % 3 === 0) ? 'yahoo.com' : 'gmail.com';

        return $prefix . '***' . $suffix . '@' . $domain;
    }
}
