<div class="w-100">
    <input id="{{ $name }}" name="{{ $name }}" placeholder="Maximum {{ $maxTags }} items">
    <button id="tagify-disable-{{$name}}" class="d-none"></button>
</div>

@once
    <script src="{{URL::asset('assets/js/jquery-3.5.1.min.js')}}"></script>
@endonce
@once
    @push("tagify")

        <script src="{{URL::asset('assets/plugins/tagify/tagify.min.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/tagify/tagify.polyfills.min.js')}}"></script>
        <link href="{{URL::asset('assets/plugins/tagify/tagify.css')}}" rel="stylesheet" type="text/css" />
    @endpush
@endonce
@push("search-input")
<script type="text/javascript" src="{{ asset('assets/plugins/drag-and-sort/dist/dragsort.js') }}"></script>
<script>
    $(document).ready(function() {

        function initTagify() {
            let inputName = "{{ $name }}";
            let inputElm = document.getElementById(inputName);

            let pattern = "{{ $pattern }}";
            pattern = pattern == "" ? "" : {{ $pattern }};

            let maxTags = "{{ $maxTags }}";

            let allowCustom = "{{ $allowcustom }}";
            allowCustom = allowCustom == "false" ? false : true;

            let tagify = new Tagify(inputElm, {
                whitelist: [],
                pattern: pattern,
                maxTags: maxTags,
                enforceWhitelist: !allowCustom,
                dropdown : {
                    enabled       : 0,              // show the dropdown immediately on focus
                    maxItems      : 8,
                    closeOnSelect : true,          // keep the dropdown open after selecting a suggestion
                    highlightFirst: true,
                    sortby:"startsWith"
                },
                templates: {
                    tag: function(tagData) {
                        let label="";
                        if(!tagData.label){
                            label = "External";
                        }else{
                            label = tagData.label;
                        }
                        return `<tag title='${label}' spellcheck='false' contenteditable='false' tabindex='-1' value='${tagData.value}' class='tagify__tag'>
                                    <x title="" class="tagify__tag__removeBtn" role="button" aria-label="remove tag"></x>
                                    <div><span class="tagify__tag-text">${tagData.value} (${label})</span></div>
                                </tag>`;
                    },
                    dropdownItem: function(tagData) {
                        return `<div value="${tagData.value}" class='tagify__dropdown__item'>${tagData.value} (${tagData.label})</div>`;
                    }
                },
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(";")
            });
            // Drag & Sort
            const dragsort = new DragSort(tagify.DOM.scope, {
                selector: '.' + tagify.settings.classNames.tag,
                callbacks: {
                    dragEnd: onDragEnd
                }
            });

            function onDragEnd(elm){
                tagify.updateValueByDOMTags()
            }

            tagify.on("input",onInput);


            function onInput(e){
                let controller;

                let value = e.detail.value;
                tagify.whiteList = null;
                controller && controller.abort();
                controller = new AbortController();

                tagify.loading(true).dropdown.hide();

                fetch('/tagAndSearch/getOptions',{signal:controller.signal}).
                then(RES => RES.json()).
                then(function (newWhitelist){
                    tagify.whitelist = newWhitelist.map(item => ({ value: item.email, label: item.name }));

                    tagify.loading(false).dropdown.show(value);
                });

            }

            let values = "{{$values}}";
            tagify.addTags(values);

            let isReadonly = false;
            $("#tagify-disable-" + inputName).click(function(){
                isReadonly = !isReadonly;
                tagify.setReadonly(isReadonly);
            })
        }


        initTagify();
    })
</script>
@endpush
