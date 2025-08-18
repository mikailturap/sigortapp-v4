<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIdentityNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        // Sadece 10 (VKN) veya 11 (TCKN) hane kabul edilir
        if (!in_array(strlen($digits), [10, 11], true)) {
            return false;
        }

        if (strlen($digits) === 11) {
            return $this->isValidTCKN($digits);
        }

        return $this->isValidVKN($digits);
    }

    public function message(): string
    {
        return 'TC/Vergi numarası geçersiz.';
    }

    private function isValidTCKN(string $tckn): bool
    {
        if (!preg_match('/^[1-9][0-9]{9}[02468]$/', $tckn)) {
            return false;
        }

        $d = array_map('intval', str_split($tckn));
        $oddSum = $d[0] + $d[2] + $d[4] + $d[6] + $d[8];
        $evenSum = $d[1] + $d[3] + $d[5] + $d[7];
        $c1 = (10 - (($oddSum * 3 + $evenSum) % 10)) % 10;
        $c2 = (10 - (((($evenSum + $c1) * 3) + $oddSum) % 10)) % 10;

        return $d[9] === $c1 && $d[10] === $c2;
    }

    private function isValidVKN(string $vkn): bool
    {
        if (!preg_match('/^\d{10}$/', $vkn)) {
            return false;
        }

        $digits = array_map('intval', str_split($vkn));
        $lastDigit = $digits[9];
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $tmp = ($digits[$i] + 10 - ($i + 1)) % 10;
            if ($tmp === 9) {
                $sum += $tmp;
            } else {
                $pow2 = pow(2, 9 - $i);
                $sum += ($tmp * $pow2) % 9;
            }
        }
        $check = (10 - ($sum % 10)) % 10;
        return $lastDigit === $check;
    }
}


