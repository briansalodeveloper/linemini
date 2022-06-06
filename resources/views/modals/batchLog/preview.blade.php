<div class="modal fade" id="batchLogPreview">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-file mr-2"></i>{{ __('words.Preview') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="mb-0">{{ __('words.LogFile') }}: </label>
                    <div class="d-flex pl-3">
                        <div role-name="location"></div>
                    </div>
                </div>
                <label class="mr-2">{{ __('words.Content') }}: </label>
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="overlay">
                            <i class="fas fa-2x fa-sync fa-spin"></i>
                        </div>
                        <div class="content" role-name="contents"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
