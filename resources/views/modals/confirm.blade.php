<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmLabel">{{ isset($title) ?? '' }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {{ isset($content) ?? '' }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="modal-btn-cancel" data-dismiss="modal">{{ __('words.Cancel') }}</button>
          <button type="button" class="btn btn-primary"id="modal-btn-confirm" >{{ __('words.Confirm') }}</button>
        </div>
      </div>
    </div>
</div>
