<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function markAsRead($id)
    {
        // // Misalnya contoh logika sederhana:
        // auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        // return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }
}
