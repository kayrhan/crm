<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TagAndSearchInput extends Component {
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name; // Input name and id for requests
    public $id; // If different id wanted to be gived
    public $tablename; // Database table name (etc. users)
    public $tablerow; // Table row for option texts (etc email)
    public $allowcustom; // Allow custom values, (true or false)
    public $pattern; // Accepted regex pattern, (etc email pattern)
    public $maxTags; // Accepted regex pattern, (etc email pattern)
    public $values; // Önceden tanımlanmış değerler, string şeklinde olmalı (ör: "item1, item2, item3)

    // Tüm parametreler email için default value olarak atandı

    public function __construct($name, $tablename = "users", $tablerow = "email", $allowcustom = true, $pattern = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $maxTags = 5, $id = null, $values = "") {
        $this->name = $name;
        $this->tablename = $tablename;
        $this->tablerow = $tablerow;
        $this->allowcustom = $allowcustom;
        $this->pattern = $pattern;
        $this->maxTags = $maxTags;
        $this->id = $id;
        $this->values = str_replace(";", ",", $values);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {
        return view('components.tag-and-search-input');
    }
}
