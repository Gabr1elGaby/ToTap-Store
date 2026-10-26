<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIdentity extends Model
{
    protected $fillable = [
        'type',
        'value',
        'user_id',
        'reason',
    ];

    /**
     * Check if an email is blocked.
     */
    public static function isEmailBlocked(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        return static::where('type', 'email')
            ->where('value', strtolower(trim($email)))
            ->exists();
    }

    /**
     * Check if a phone number is blocked.
     */
    public static function isPhoneBlocked(?string $phone): bool
    {
        if (empty($phone)) {
            return false;
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);
        if (empty($cleanPhone)) {
            return false;
        }

        return static::where('type', 'phone')
            ->where('value', $cleanPhone)
            ->exists();
    }

    /**
     * Block a user and record email and phone in blocked_identities.
     */
    public static function blockUser(User $user, ?string $reason = null): void
    {
        $user->is_blocked = true;
        $user->save();

        if (!empty($user->email)) {
            static::firstOrCreate(
                ['type' => 'email', 'value' => strtolower(trim($user->email))],
                ['user_id' => $user->id, 'reason' => $reason]
            );
        }

        if (!empty($user->phone_number)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$user->phone_number);
            if (!empty($cleanPhone)) {
                static::firstOrCreate(
                    ['type' => 'phone', 'value' => $cleanPhone],
                    ['user_id' => $user->id, 'reason' => $reason]
                );
            }
        }
    }

    /**
     * Unblock a user and remove email and phone from blocked_identities.
     */
    public static function unblockUser(User $user): void
    {
        $user->is_blocked = false;
        $user->save();

        if (!empty($user->email)) {
            static::where('type', 'email')
                ->where('value', strtolower(trim($user->email)))
                ->delete();
        }

        if (!empty($user->phone_number)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$user->phone_number);
            if (!empty($cleanPhone)) {
                static::where('type', 'phone')
                    ->where('value', $cleanPhone)
                    ->delete();
            }
        }

        // Also clean up any records specifically linked to this user_id
        static::where('user_id', $user->id)->delete();
    }
}
