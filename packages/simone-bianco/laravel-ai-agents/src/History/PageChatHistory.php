<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\History;

use LarAgent\Context\Storages\ChatHistoryStorage;
use LarAgent\Core\Contracts\ChatHistory as ChatHistoryInterface;

class PageChatHistory extends ChatHistoryStorage implements ChatHistoryInterface
{
    protected array $defaultDrivers = [PageChatStorageDriver::class];
}
