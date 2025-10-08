<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function feed(): JsonResponse
    {
        $notifications = NotificationService::getNotificationsForUser(auth()->id());
        
        // If no notifications in database, fallback to old system
        if ($notifications->isEmpty()) {
            return $this->fallbackFeed();
        }
        
        return response()->json($notifications);
    }

    private function fallbackFeed(): JsonResponse
    {
        $activities = collect();
        $isAdmin = auth()->user()->role === 'admin';

        if ($isAdmin) {
            // Admin sees everything
            $activities = $activities->merge(\App\Models\Donation::with('user')->latest()->take(10)->get()->map(fn($d) => [
                'id' => 'donation_' . $d->id,
                'type' => 'donation',
                'message' => ($d->user->name ?? 'Someone')." donated ₱".number_format($d->amount,2),
                'time' => $d->created_at,
                'is_read' => true, // Fallback notifications are considered read
                'time_human' => $d->created_at->diffForHumans()
            ]));
            
            $activities = $activities->merge(\App\Models\Comment::with('user')->latest()->take(10)->get()->map(fn($c) => [
                'id' => 'comment_' . $c->id,
                'type' => 'comment',
                'message' => ($c->user->name ?? 'User')." commented: ".str($c->content)->limit(50),
                'time' => $c->created_at,
                'is_read' => true,
                'time_human' => $c->created_at->diffForHumans()
            ]));
            
            $activities = $activities->merge(\App\Models\Like::with('user')->latest()->take(10)->get()->map(fn($l) => [
                'id' => 'like_' . $l->id,
                'type' => 'like',
                'message' => ($l->user->name ?? 'User')." liked a post",
                'time' => $l->created_at,
                'is_read' => true,
                'time_human' => $l->created_at->diffForHumans()
            ]));
        }

        // Both admin and users see events/posts
        $activities = $activities->merge(\App\Models\AlumniPost::with('comments')->latest()->take(10)->get()->map(fn($p) => [
            'id' => 'event_' . $p->id,
            'type' => 'event',
            'message' => 'New event/post: '.str($p->content)->limit(60),
            'time' => $p->created_at,
            'is_read' => true,
            'time_human' => $p->created_at->diffForHumans()
        ]));

        // Users only see messages addressed to them, admins see all messages
        if ($isAdmin) {
            $messages = \App\Models\Message::with('sender')->latest()->take(10)->get();
        } else {
            $messages = \App\Models\Message::with('sender')
                ->where('receiver_id', auth()->id())
                ->latest()
                ->take(10)
                ->get();
        }
        
        $activities = $activities->merge($messages->map(fn($m) => [
            'id' => 'message_' . $m->id,
            'type' => 'message',
            'message' => ($m->sender->name ?? 'User').": ".str($m->message)->limit(50),
            'time' => $m->created_at,
            'is_read' => true,
            'time_human' => $m->created_at->diffForHumans()
        ]));

        // Both see news
        $activities = $activities->merge(\App\Models\News::latest()->take(10)->get()->map(fn($n) => [
            'id' => 'news_' . $n->id,
            'type' => 'news',
            'message' => 'News: '.str($n->title)->limit(60),
            'time' => $n->created_at,
            'is_read' => true,
            'time_human' => $n->created_at->diffForHumans()
        ]));

        $activities = $activities->sortByDesc('time')->take(25)->values();

        return response()->json($activities);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required'
        ]);

        $notificationId = $request->notification_id;
        
        // Check if it's a fallback notification (string ID) or database notification (integer ID)
        if (is_string($notificationId) && strpos($notificationId, '_') !== false) {
            // This is a fallback notification, just return success
            return response()->json([
                'success' => true,
                'unread_count' => 0 // Fallback notifications don't have unread count
            ]);
        }

        // This is a database notification
        $success = NotificationService::markAsRead(
            (int)$notificationId,
            auth()->id()
        );

        return response()->json([
            'success' => $success,
            'unread_count' => NotificationService::getUnreadCount(auth()->id())
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $count = NotificationService::markAllAsRead(auth()->id());

        return response()->json([
            'success' => true,
            'marked_count' => $count,
            'unread_count' => 0
        ]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $count = NotificationService::getUnreadCount(auth()->id());

        return response()->json([
            'unread_count' => $count
        ]);
    }
}
