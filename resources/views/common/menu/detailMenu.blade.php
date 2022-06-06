<div class="edit-menu-container btn-group-vertical">
    @if(!empty($data->getAttr('id', false)))
        @if(!in_array($page, ['admin']))
            <button class="btn btn-02 mb-2" id="duplicateBtn">{{ $page == 'message' ? __('words.Duplicate') : __('words.DuplicateThisProject') }}</button>
        @endif
        @if(!in_array($page, ['message']) || ($page == 'message' && $data->getAttr('isStatusNotSend', true)))
            @if(in_array($page, ['admin']))
                @if($data->getAttr('id', 0) != auth()->user()->id)
                    <button class="btn btn-02 mb-2" id="deleteBtn">{{ __('words.Delete') }}</button>
                @endif
            @else
                <button class="btn btn-02 mb-2" id="deleteBtn">{{ __('words.DeleteThisProject') }}</button>
            @endif
            <button class="btn btn-02 mb-2" id="undoEdit">{{ __('words.UndoEditing') }}</button>
        @endif
    @else
        <button class="btn btn-02" id="clearBtn">{{ __('words.ClearInputItems') }}</button>
    @endif
</div>
