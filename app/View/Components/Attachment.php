<?php

namespace App\View\Components;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Attachment extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $type;
    public $buttonclass;
    public $formid;
    public $private;
    public $maxsize;
    public $maxfiles;
    public $header;
    public $acceptedTypes;
    public $showheader;
    public $style;

    public function __construct(
        $type,
        $buttonclass,
        $formid,
        $private = "false",
        $maxsize = "100",
        $maxfiles = "5",
        $header = "Add Attachment",
        $acceptedTypes = "image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar",
        $showheader = "true",
        $style = "normal"
    ) {
        $this->type = $type;
        $this->buttonclass = $buttonclass;
        $this->formid = $formid;
        $this->private = $private;
        $this->maxsize = $maxsize;
        $this->maxfiles = $maxfiles;
        $this->header = $header;
        $this->acceptedTypes = $acceptedTypes;
        $this->showheader = $showheader;
        $this->style = $style;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.attachment');
    }
}
