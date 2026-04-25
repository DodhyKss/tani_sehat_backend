<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageDetail;
use App\Models\Warga;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Message::with(['kader:id,nama_lengkap,foto', 'warga:id,nama_lengkap,foto', 'latestDetail']);

        if ($user->role === 'warga') {
            $query->where('warga_id', $user->id);
        } elseif ($user->role === 'kader') {
            $query->where('kader_id', $user->id);
        }

        $messages = $query->orderBy('updated_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function show(Request $request, $id)
    {
        $message = Message::with(['details.sender:id,nama_lengkap,foto', 'kader:id,nama_lengkap', 'warga:id,nama_lengkap'])->findOrFail($id);
        $user = $request->user();

        if ($user->role === 'warga' && $message->warga_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }
        if ($user->role === 'kader' && $message->kader_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        return response()->json(['success' => true, 'data' => $message]);
    }

    public function startConversation(Request $request)
    {
        $user = $request->user();
        $request->validate(['receiver_id' => 'required|exists:users,id']);

        if ($user->role === 'warga') {
            $wargaRelasi = Warga::where('warga_id', $user->id)->first();
            if (!$wargaRelasi || $wargaRelasi->kader_id != $request->receiver_id) {
                return response()->json(['success' => false, 'message' => 'Anda hanya bisa chat dengan kader Anda.'], 422);
            }
            $kaderId = $request->receiver_id;
            $wargaId = $user->id;
        } else {
            $kaderId = $user->id;
            $wargaId = $request->receiver_id;
        }

        $existing = Message::where('kader_id', $kaderId)->where('warga_id', $wargaId)->first();
        if ($existing) {
            return response()->json(['success' => true, 'message' => 'Conversation sudah ada', 'data' => $existing]);
        }

        $message = Message::create(['kader_id' => $kaderId, 'warga_id' => $wargaId]);
        return response()->json(['success' => true, 'message' => 'Conversation berhasil dibuat', 'data' => $message], 201);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate(['message' => 'required|string']);
        $user = $request->user();
        $conversation = Message::findOrFail($conversationId);

        if ($conversation->kader_id !== $user->id && $conversation->warga_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $detail = MessageDetail::create([
            'message_id' => $conversationId,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        $conversation->touch();
        return response()->json(['success' => true, 'data' => $detail->load('sender:id,nama_lengkap')], 201);
    }
}
