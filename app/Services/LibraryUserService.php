<?php

namespace App\Services;

use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LibraryUserService
{
    public function syncFromLibrary(Library $library): array
    {
        $phone = trim((string) $library->phone);

        if ($phone === '') {
            throw ValidationException::withMessages([
                'phone' => ['Library phone number is required to create login credentials.'],
            ]);
        }

        $user = User::where('phone', $phone)->first();
        $passwordGenerated = false;

        $data = [
            'library_id' => $library->id,
            'name' => $library->name ?: 'Library User',
            'email' => $user?->email ?: $this->emailForLibrary($library, $phone),
            'phone' => $phone,
            'role' => 'owner',
        ];

        if (! $user) {
            $data['password'] = Hash::make($phone);
            $user = User::create($data);
            $passwordGenerated = true;
        } else {
            // Keep password as phone if we want to ensure they can login with phone
            $data['password'] = Hash::make($phone);
            $passwordGenerated = true;

            $user->update($data);
        }

        return [
            'user' => $user->fresh(),
            'default_password' => $phone,
            'password_generated' => $passwordGenerated,
        ];
    }

    private function emailForLibrary(Library $library, string $phone): string
    {
        $phonePart = preg_replace('/[^0-9]/', '', $phone) ?: Str::slug($phone);
        $base = 'library'.$library->id.'_'.$phonePart;
        $email = $base.'@library.local';
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $base.'_'.$counter.'@library.local';
            $counter++;
        }

        return $email;
    }
}
