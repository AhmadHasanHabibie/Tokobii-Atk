<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OwnerController extends Controller
{
    /**
     * Tampilkan form pembuatan akun Owner
     * sekaligus daftar akun Owner yang sudah ada.
     */
    public function create()
    {
        $owners = User::where('role', 'owner')
            ->latest()
            ->get();

        return view('admin.owners.create', compact('owners'));
    }

    /**
     * Simpan akun Owner baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama Owner wajib diisi.',
            'name.max' => 'Nama Owner maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.owners.create')
            ->with('success', 'Akun Owner berhasil dibuat.');
    }

    /**
     * Tampilkan halaman edit akun Owner.
     */
    public function edit(User $owner)
    {
        // Pastikan user yang dibuka benar-benar Owner.
        abort_unless($owner->role === 'owner', 404);

        return view('admin.owners.edit', compact('owner'));
    }

    /**
     * Perbarui akun Owner.
     */
    public function update(Request $request, User $owner)
    {
        // Pastikan user yang diedit benar-benar Owner.
        abort_unless($owner->role === 'owner', 404);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($owner->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'banned',
                ]),
            ],
        ], [
            'name.required' => 'Nama Owner wajib diisi.',
            'name.max' => 'Nama Owner maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            'status.required' => 'Status akun wajib dipilih.',
            'status.in' => 'Status akun tidak valid.',
        ]);

        $owner->name = $validated['name'];
        $owner->email = $validated['email'];
        $owner->status = $validated['status'];

        /*
         * Password hanya diubah kalau user
         * memang mengisi password baru.
         */
        if (!empty($validated['password'])) {
            $owner->password = Hash::make($validated['password']);
        }

        $owner->save();

        return redirect()
            ->route('admin.owners.create')
            ->with('success', 'Akun Owner berhasil diperbarui.');
    }
}