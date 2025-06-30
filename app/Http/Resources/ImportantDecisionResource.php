<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportantDecisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "text" => $this->text,
            "add_by" => [
                "name" => $this->user->getFullName(),
                "id" => $this->add_by
            ],
            "date" => $this->getDate(),
            "attachments" => AttachmentResource::collection($this->getAttachments())
        ];
    }
}
