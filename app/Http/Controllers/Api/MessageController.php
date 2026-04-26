<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageDetail;
use App\Models\Warga;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Message::with(['kader:id,nama_lengkap,foto', 'warga:id,nama_lengkap,foto', 'admin:id,nama_lengkap,foto', 'latestDetail']);

        if ($user->role === 'warga') {
            $query->where('warga_id', $user->id);
        } elseif ($user->role === 'kader') {
            $query->where('kader_id', $user->id);
        } elseif ($user->role === 'admin') {
            $query->where('admin_id', $user->id)
                  ->orWhere(function($q) {
                      // Admin also might want to see all or specific ones? 
                      // Let's say Admin as a participant sees their own, but can also see others.
                      // For now, let's allow Admin to see all if they want, or just where they are participants.
                      // Usually Admin is a participant.
                  });
        }

        $messages = $query->orderBy('updated_at', 'desc')->get();

        // Map to include unread count and partner info
        $data = $messages->map(function ($msg) use ($user) {
            $unreadCount = MessageDetail::where('message_id', $msg->id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();

            // Identify partner
            $partner = null;
            if ($user->role === 'warga') {
                $partner = $msg->kader ?? $msg->admin;
            } elseif ($user->role === 'kader') {
                $partner = $msg->warga ?? $msg->admin;
            } elseif ($user->role === 'admin') {
                $partner = $msg->warga ?? $msg->kader;
            }

            return [
                'id' => $msg->id,
                'kader_id' => $msg->kader_id,
                'warga_id' => $msg->warga_id,
                'admin_id' => $msg->admin_id,
                'updated_at' => $msg->updated_at,
                'latest_detail' => $msg->latestDetail,
                'unread_count' => $unreadCount,
                'partner' => $partner
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $message = Message::with(['details.sender:id,nama_lengkap,foto', 'kader:id,nama_lengkap', 'warga:id,nama_lengkap', 'admin:id,nama_lengkap'])->findOrFail($id);

        // Authorization
        $isParticipant = ($message->warga_id === $user->id || $message->kader_id === $user->id || $message->admin_id === $user->id);
        
        if (!$isParticipant && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        // Mark messages as read
        MessageDetail::where('message_id', $id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'data' => $message]);
    }

    public function startConversation(Request $request)
    {
        $user = $request->user();
        $request->validate(['receiver_id' => 'required|exists:users,id']);
        
        $receiver = User::findOrFail($request->receiver_id);
        
        if ($user->id == $receiver->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak bisa memulai chat dengan diri sendiri.'], 422);
        }

        $kaderId = null;
        $wargaId = null;
        $adminId = null;

        // Logic Participant
        if ($user->role === 'admin') {
            $adminId = $user->id;
            if ($receiver->role === 'warga') $wargaId = $receiver->id;
            elseif ($receiver->role === 'kader') $kaderId = $receiver->id;
            else {
                // Admin chat with another admin? 
                // Let's use warga_id as second participant if it's admin-admin for now, 
                // but usually not needed. Let's just say not supported for now to keep schema.
                return response()->json(['success' => false, 'message' => 'Chat antar admin belum didukung.'], 422);
            }
        } elseif ($receiver->role === 'admin') {
            $adminId = $receiver->id;
            if ($user->role === 'warga') $wargaId = $user->id;
            else $kaderId = $user->id;
        } elseif ($user->role === 'kader') {
            if ($receiver->role !== 'warga') {
                return response()->json(['success' => false, 'message' => 'Kader hanya bisa memulai chat dengan warga yang dibina.'], 422);
            }
            $wargaRelasi = Warga::where('warga_id', $receiver->id)->where('kader_id', $user->id)->first();
            if (!$wargaRelasi) {
                return response()->json(['success' => false, 'message' => 'Warga ini bukan binaan Anda.'], 422);
            }
            $kaderId = $user->id;
            $wargaId = $receiver->id;
        } elseif ($user->role === 'warga') {
            if ($receiver->role !== 'kader') {
                return response()->json(['success' => false, 'message' => 'Warga hanya bisa memulai chat dengan kader pembimbing atau admin.'], 422);
            }
            $wargaRelasi = Warga::where('warga_id', $user->id)->where('kader_id', $receiver->id)->first();
            if (!$wargaRelasi) {
                return response()->json(['success' => false, 'message' => 'Kader ini bukan pembimbing Anda.'], 422);
            }
            $kaderId = $receiver->id;
            $wargaId = $user->id;
        }

        $query = Message::query();
        $kaderId ? $query->where('kader_id', $kaderId) : $query->whereNull('kader_id');
        $wargaId ? $query->where('warga_id', $wargaId) : $query->whereNull('warga_id');
        $adminId ? $query->where('admin_id', $adminId) : $query->whereNull('admin_id');
        
        $existing = $query->first();
            
        if ($existing) {
            return response()->json(['success' => true, 'message' => 'Percakapan sudah ada', 'data' => $existing]);
        }

        $message = Message::create([
            'kader_id' => $kaderId, 
            'warga_id' => $wargaId,
            'admin_id' => $adminId
        ]);
        
        return response()->json(['success' => true, 'message' => 'Percakapan berhasil dibuat', 'data' => $message->load(['kader', 'warga', 'admin'])], 201);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate(['message' => 'required|string']);
        $user = $request->user();
        $conversation = Message::findOrFail($conversationId);

        $isParticipant = ($conversation->kader_id === $user->id || $conversation->warga_id === $user->id || $conversation->admin_id === $user->id);

        if (!$isParticipant) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $detail = MessageDetail::create([
            'message_id' => $conversationId,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->touch();
        return response()->json(['success' => true, 'data' => $detail->load('sender:id,nama_lengkap')], 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $conversation = Message::findOrFail($id);

        // Hanya partisipan yang bisa hapus (atau admin)
        $isParticipant = ($conversation->kader_id === $user->id || $conversation->warga_id === $user->id || $conversation->admin_id === $user->id);

        if (!$isParticipant && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        // Hapus detail dulu
        MessageDetail::where('message_id', $id)->delete();
        $conversation->delete();

        return response()->json(['success' => true, 'message' => 'Percakapan berhasil dihapus']);
    }

    public function destroyDetail(Request $request, $id)
    {
        $user = $request->user();
        $detail = MessageDetail::findOrFail($id);

        // Hanya pengirim yang bisa hapus pesannya sendiri, atau admin
        if ($detail->sender_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $detail->delete();

        return response()->json(['success' => true, 'message' => 'Pesan berhasil dihapus']);
    }
}
