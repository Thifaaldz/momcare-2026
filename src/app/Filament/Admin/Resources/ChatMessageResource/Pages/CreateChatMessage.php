<?php

namespace App\Filament\Admin\Resources\ChatMessageResource\Pages;

use App\Filament\Admin\Resources\ChatMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChatMessage extends CreateRecord
{
    protected static string $resource = ChatMessageResource::class;
}

