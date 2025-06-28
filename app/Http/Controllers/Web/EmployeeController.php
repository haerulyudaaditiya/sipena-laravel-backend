<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\EmployeeCredentials;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Menampilkan daftar semua karyawan
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    // Menampilkan form untuk membuat karyawan baru
    public function create(): mixed
    {
        return view('employees.create');
    }

    // Menyimpan karyawan baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'npwp' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'hire_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Buat karyawan
        $employee = Employee::create([
            'employee_id' => $validated['employee_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'npwp' => $validated['npwp'],
            'position' => $validated['position'],
            'status' => $validated['status'],
            'hire_date' => $validated['hire_date'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'department' => $validated['department'],
            'photo' => $photoPath,
        ]);

        // Password yang digunakan untuk login
        $password = Str::random(8); // Menghasilkan password acak sepanjang 8 karakter

        // Buat user untuk karyawan
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password), // Simpan password yang sudah di-hash
            'role' => 'employee',
            'employee_id' => $employee->id,
            'email_verified_at' => now(),
        ]);

        // Kirim email ke karyawan baru
        Mail::to($employee->email)->send(new EmployeeCredentials($employee, $password));

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    // Menampilkan data karyawan berdasarkan ID
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    // Menampilkan form untuk mengedit data karyawan
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Memperbarui data karyawan
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $user = User::where('employee_id', $id)->first();

        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . $id,
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($id),
                Rule::unique('users', 'email')->ignore($user ? $user->id : null),
            ],
            'npwp' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'hire_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses untuk menghapus foto lama jika ada foto baru yang di-upload
        if ($request->hasFile('photo')) {
            // Menghapus foto lama jika ada
            if ($employee->photo) {
                $oldPhotoPath = 'public/' . $employee->photo;
                if (\Storage::exists($oldPhotoPath)) {
                    \Storage::delete($oldPhotoPath);
                }
            }

            // Proses upload foto baru
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $photoPath; // Menyimpan path foto baru ke dalam array validated
        }

        // Update data karyawan
        $employee->update($validated);

        // Update associated user if exists
        if ($user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    // Menghapus karyawan dari database
    public function destroy(Employee $employee)
    {
        // Hapus foto jika ada
        if ($employee->photo) {
            $photoPath = 'public/' . $employee->photo;
            if (\Storage::exists($photoPath)) {
                \Storage::delete($photoPath);
            }
        }

        // Hapus data user terkait
        $user = User::where('employee_id', $employee->id)->first();
        if ($user) {
            $user->delete();
        }

        // Hapus data karyawan
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $employee->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('employees.index')->with('success', 'Status karyawan berhasil diperbarui.');
    }
}
