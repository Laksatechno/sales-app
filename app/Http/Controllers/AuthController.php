<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // return redirect()->route('dashboard');
            return view('dashboard');
        }

        return back()->with('error', 'Email atau Password Salah');
    }

    public function showRegister()
    {
        $marketings = User::where('role', 'marketing')->get();
        return view('auth.register' , compact('marketings'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'tipe_pelanggan' => 'required|string|max:50',
            'jenis_institusi' => 'required|string|max:50',
            // 'marketing_id' => 'required|exists:marketings,id', // Pastikan marketing_id valid
        ]);
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'address' => $request->alamat,
                'role' => 'customer',
                'tipe_pelanggan' => $request->tipe_pelanggan,
                'jenis_institusi' => $request->jenis_institusi,
                'marketing_id' => $request->marketing_id,
                'password' => Hash::make($request->password),
            ]);
    
            Auth::login($user);
    
            return redirect()->route('dashboard')->with('success', 'Registration successful');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    public function showresetpassword(){
        return view('auth.passwords.reset');
    }

    public function reset(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255',
            ]);
            $user = User::where('email', $request->email)->first();
            if($user){
                $user->update([
                    'password' => Hash::make($request->password),
                    ]);
                    return redirect()->route('login')->with('success', 'Password reset successful');
                    }else{
                        return redirect()->back()->withErrors(['error' => 'Email not found']);
                        }
                        
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }


    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        try {
            // Validate the inputs
            $request->validate([
                'name' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
            ]);
    
            // Get the current user
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }
    
            // Update user details
            $user->no_hp = $request->input('no_hp');
            $user->name = $request->input('name');
    
            // Save user
            $user->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully.',
                'data' => $user // Return updated user data
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    

    public function updatepassword(Request $request)
    {
        try {
            // Validasi input password
            $request->validate([
                'password' => 'required|min:8' // Bisa ditambahkan validasi panjang password jika perlu
            ]);
    
            // Cari user berdasarkan ID yang sedang login
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }
    
            // Hash password sebelum disimpan
            $user->password = Hash::make($request->input('password'));
        
            // Simpan data user yang sudah diperbarui
            $user->save();
        
            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully.',
                'data' => $user // Mengembalikan data user yang diperbarui
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            // Validasi file foto
            $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048', // Aturan validasi
            ]);
    
            $users = User::find(Auth::user()->id);
            if (!$users) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }
    
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
    
                if ($file->isValid()) {
                    // Generate nama file unik
                    $filename = $users->name.'_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    
                    // Path penyimpanan
                    $destinationPath = public_path('photo');
    
                    // Memastikan direktori penyimpanan ada
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
    
                    // Pindahkan file ke direktori penyimpanan
                    $file->move($destinationPath, $filename);
    
                    // Hapus foto lama jika ada
                    if ($users->foto && file_exists(public_path('photo/' . $users->foto))) {
                        unlink(public_path('photo/' . $users->foto));
                    }
    
                    // Simpan nama file ke database
                    $users->foto = $filename;
                    $users->save();
    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Foto updated successfully.',
                        'data' => $users // Menyertakan data user yang diperbarui
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Uploaded file is not valid.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No file uploaded.'
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->validator->errors() // Menyertakan kesalahan validasi
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the foto.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
}
