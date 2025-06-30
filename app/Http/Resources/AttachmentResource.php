<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            "link" => asset('/uploads') . '/' . $this->attachment,
            "filename" => substr($this->getFileName(), 0, 20),
            "size" => $this->getSizeMB(),
            "filetype" => $this->getFileType()
        ];
    }
}
