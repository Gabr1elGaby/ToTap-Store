<?php

namespace App\Helpers;

class QrisHelper
{
    /**
     * Master Static QRIS Payload for ToTap Store, Gaming
     * NMID: ID1026577601523
     */
    public static function getStoreStaticQris(): string
    {
        return '00020101021126610014COM.GO-JEK.WWW01189360091434644339750210G4644339750303UMI51440014ID.CO.QRIS.WWW0215ID10265776015230303UMI5204581653033605802ID5919ToTap Store, Gaming6009SUKOHARJO61055755262070703A0163043061';
    }

    /**
     * Get dynamic QRIS string for a specific transaction amount
     */
    public static function getDynamicQrisForAmount(int|float $amount): string
    {
        return self::convertToDynamic(self::getStoreStaticQris(), $amount);
    }

    /**
     * Parse EMVCo QRIS string into associative Tag => Value array
     */
    public static function parseQris(string $raw): array
    {
        $tags = [];
        $i = 0;
        $len = strlen($raw);

        while ($i < $len - 4) {
            $tag = substr($raw, $i, 2);
            $valLen = (int)substr($raw, $i + 2, 2);
            if ($valLen <= 0 || $i + 4 + $valLen > $len) {
                break;
            }
            $val = substr($raw, $i + 4, $valLen);
            
            // Stop before CRC tag 63
            if ($tag === '63') {
                break;
            }

            $tags[$tag] = $val;
            $i += 4 + $valLen;
        }

        return $tags;
    }

    /**
     * Convert static QRIS string into dynamic QRIS with exact injected amount
     */
    public static function convertToDynamic(string $rawQris, int|float $amount): string
    {
        $rawQris = trim($rawQris);
        if (empty($rawQris)) {
            return '';
        }

        $tags = self::parseQris($rawQris);
        if (empty($tags)) {
            return $rawQris;
        }

        // 1. Tag 01: Set Point of Initiation Method to '12' (Dynamic QR)
        $tags['01'] = '12';

        // 2. Tag 54: Set Transaction Amount
        $amountStr = (string)(int)$amount;
        $tags['54'] = $amountStr;

        // 3. Build ordered payload without Tag 63
        $orderedKeys = ['00', '01'];
        foreach (array_keys($tags) as $k) {
            if (!in_array($k, ['00', '01', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63']) && $k < '54') {
                $orderedKeys[] = $k;
            }
        }
        if (isset($tags['52'])) $orderedKeys[] = '52';
        if (isset($tags['53'])) $orderedKeys[] = '53';
        $orderedKeys[] = '54'; // Amount
        foreach (['55', '56', '57', '58', '59', '60', '61', '62'] as $k) {
            if (isset($tags[$k])) {
                $orderedKeys[] = $k;
            }
        }
        foreach (array_keys($tags) as $k) {
            if (!in_array($k, $orderedKeys) && $k !== '63') {
                $orderedKeys[] = $k;
            }
        }

        // Unique ordered keys
        $orderedKeys = array_unique($orderedKeys);

        $payload = '';
        foreach ($orderedKeys as $k) {
            if (isset($tags[$k])) {
                $v = $tags[$k];
                $payload .= $k . sprintf('%02d', strlen($v)) . $v;
            }
        }

        // 4. Append Tag 63 and calculate CRC16
        $payloadWithTag63 = $payload . '6304';
        $crc = self::crc16($payloadWithTag63);

        return $payloadWithTag63 . $crc;
    }

    /**
     * Calculate CRC16-CCITT (0xFFFF, 0x1021)
     */
    public static function crc16(string $data): string
    {
        $crc = 0xFFFF;
        $len = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return strtoupper(sprintf('%04X', $crc));
    }
}
