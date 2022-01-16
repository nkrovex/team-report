<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'size' => count($this->accounts),
            'accounts' => $this->accounts->makeHidden('team_id')
        ];
    }
}
