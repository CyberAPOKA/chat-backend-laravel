<?php

namespace App\Services;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationService
{
    public function findOrCreateDirectConversation(User $user): Conversation
    {
        $authUserId = Auth::id();

        $conversation = Conversation::where('type', 'private')
            ->whereHas('users', function ($q) use ($authUserId) {
                $q->where('user_id', $authUserId);
            })
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'type' => 'private',
                'created_by' => $authUserId,
            ]);

            $conversation->users()->attach([
                $authUserId => ['joined_at' => now()],
                $user->id => ['joined_at' => now()],
            ]);
        }

        return $conversation;
    }

    public function getMessages(Conversation $conversation)
    {
        return $conversation->messages()
            ->with('user')
            ->orderBy('sent_at', 'asc')
            ->take(50)
            ->get();
    }

    public function getRecentConversations()
    {
        $user = auth()->user();
        $userId = $user->id;

        $contacts = $user->contacts()->pluck('users.id');

        $conversations = Conversation::whereHas('users', fn ($q) => $q->where('user_id', $userId))
            ->where(function ($q) use ($contacts) {
                $q->whereHas('users', fn ($q2) => $q2->whereIn('user_id', $contacts))
                ->orWhereHas('messages');
            })
            ->with([
                'users' => fn ($q) => $q->select('users.id', 'users.name', 'users.email', 'users.profile_photo_path'),
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->get()
            ->sortByDesc(fn ($conv) => optional($conv->messages->first())->sent_at)
            ->values();

        $existingIds = $conversations->pluck('users')->flatten()->pluck('id')->toArray();
        $missingContacts = $user->contacts()->whereNotIn('users.id', $existingIds)->get();

        foreach ($missingContacts as $contact) {
            $conversations->push((object)[
                'id' => null,
                'users' => collect([$contact]),
                'messages' => collect(),
            ]);
        }

        return $conversations->map(function ($conversation) use ($userId) {
            $participant = $conversation->users->firstWhere('id', '!=', $userId);
            $lastMessage = $conversation->messages->first();

            $meInConversation = $conversation->users->firstWhere('id', $userId);
            $lastRead = optional($meInConversation?->pivot)->last_read_at;

            $hasUnread = $lastMessage && $lastRead
                ? $lastMessage->sent_at > $lastRead
                : (bool) $lastMessage;

            return [
                'id' => $participant?->id,
                'name' => $participant?->name,
                'email' => $participant?->email,
                'profile_photo_url' => $participant?->profile_photo_url,
                'last_message' => optional($lastMessage)->content,
                'last_message_at' => optional($lastMessage)->sent_at,
                'conversation_id' => $conversation->id,
                'has_unread' => $hasUnread,
            ];
        });
    }


    public function getConversationParticipants(Conversation $conversation)
    {
        $conversation->load([
            'users' => fn ($q) => $q->select('users.id', 'name', 'email', 'profile_photo_path'),
        ]);

        return [
            'id' => $conversation->id,
            'users' => $conversation->users->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'profile_photo_url' => $u->profile_photo_url,
            ]),
        ];
    }

}
