<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all(); // Barcha foydalanuvchilarni olish
            return response()->json([
                'data' => $users         // Foydalanuvchilar ro'yxati
            ], 200);                     // HTTP 200 status kodi
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);                     // HTTP 500 status kodi (Server xatosi)
        }
    }

}
