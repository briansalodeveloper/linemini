
<!-- Modal -->

  <div class="modal" tabindex="-1" role="dialog"  id="stampDuplicateProject" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('words.final confirmation') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p></p>{{ __('words.Do you want to continue duplication?') }}
        </div>
        <div class="modal-footer">
          <a class="btn btn-primary mb-2"  href="{{route('stamp.duplicate', $data->id)}}">{{ __('words.Confirm') }}</a> 
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('words.Cancel') }}</button>
        </div>
      </div>
    </div>
  </div>