<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\History;

use LarAgent\Context\Abstract\StorageDriver;
use LarAgent\Context\Contracts\SessionIdentity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use SimoneBianco\PageChat\Models\ChatMessage;
use SimoneBianco\PageChat\Models\ChatSession;

/**
 * LarAgent storage driver backed by the `chat_messages` table from
 * simone-bianco/laravel-page-chat.
 */
class PageChatStorageDriver extends StorageDriver
{
    public function __construct()
    {
        if (!class_exists(\SimoneBianco\PageChat\Models\ChatMessage::class)) {
            throw new RuntimeException(
                'PageChatStorageDriver requires simone-bianco/laravel-page-chat. '.
                'Install it with: composer require simone-bianco/laravel-page-chat'
            );
        }
    }

    /**
     * @return array<int, array{role: string, content: string}>|null
     */
    public function readFromMemory(SessionIdentity $identity): ?array
    {
        $chatKey = $identity->getChatName();

        if (! $chatKey) {
            return null;
        }

        $sessionId = self::sessionIdFromChatKey((string) $chatKey);

        $messages = ChatMessage::query()
            ->where('chat_session_id', $sessionId)
            ->whereIn('role', ['user', 'assistant'])
            ->where('status', 'completed')
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['role', 'content']);

        if ($messages->isEmpty()) {
            return null;
        }

        return $messages
            ->map(fn ($msg) => ['role' => $msg->role, 'content' => $msg->content])
            ->all();
    }

    public function writeToMemory(SessionIdentity $identity, array $data): bool
    {
        try {
            $chatKey = $identity->getChatName();
            if (! $chatKey) {
                return false;
            }

            $sessionId = self::sessionIdFromChatKey((string) $chatKey);
            $session = ChatSession::query()->find($sessionId);

            if ($session === null) {
                $session = new ChatSession();
                $session->id = $sessionId;
                $session->title = 'Playground chat';
                $session->save();
            }

            $messages = [];

            foreach ($data as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $role = (string) ($item['role'] ?? '');
                $rawContent = $item['content'] ?? '';

                if (is_array($rawContent)) {
                    $rawContent = json_encode($rawContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }

                if (is_object($rawContent)) {
                    $rawContent = json_encode($rawContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }

                $content = trim((string) ($rawContent ?? ''));

                if (($role !== 'user' && $role !== 'assistant') || $content === '') {
                    continue;
                }

                $messages[] = [
                    'id' => (string) Str::uuid(),
                    'chat_session_id' => $sessionId,
                    'role' => $role,
                    'content' => $content,
                    'status' => 'completed',
                    'metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ChatMessage::query()
                ->where('chat_session_id', $sessionId)
                ->delete();

            if ($messages !== []) {
                ChatMessage::query()->insert($messages);
            }

            return true;
        } catch (\Throwable $e) {
            Log::channel('agents')->error('page_chat_storage_write_failed', [
                'chat_key' => $identity->getChatName(),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function removeFromMemory(SessionIdentity $identity): bool
    {
        $chatKey = $identity->getChatName();
        if (! $chatKey) {
            return false;
        }

        $sessionId = self::sessionIdFromChatKey((string) $chatKey);

        ChatSession::query()
            ->where('id', $sessionId)
            ->delete();

        return true;
    }

    public static function sessionIdFromChatKey(string $chatKey): string
    {
        $hash = md5('playground-chat:' . $chatKey);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12),
        );
    }
}
