<?php

namespace App\View\Components;

use App\Attachment;
use Illuminate\View\Component;

class AttachmentList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $type;
    public $ownerid;
    public $private;
    public $header;

    public function __construct($type, $ownerid, $private = "false", $header = "Attached Files")
    {
        $this->type = $type;
        $this->ownerid = $ownerid;
        $this->private = $private;
        $this->header = $header;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $attachments = Attachment::where("owner_id", $this->ownerid)->where("type", $this->type);
        $count = $attachments->count();
        $attachments = $attachments->get();

        return view('components.attachment-list', compact("attachments", "count"));
    }
}
