<div class="modal fade" id="flyerPreview">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('words.Preview') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-default card-tabs col-12">
                    <div class="card-header">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custTabFront-tab" data-toggle="pill" href="#custTabFront" role="tab" aria-controls="custTabFront" aria-selected="true">{{ __('words.FlyerImgTable') }}</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="custTabBack-tab" data-toggle="pill" href="#custTabBack" role="tab" aria-controls="custTabBack" aria-selected="false">{{ __('words.FlyerImgBack') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mobile">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade  show active" id="custTabFront" role="tabpanel" aria-labelledby="custTabFront-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title" role-name="title"></div>
                                        <div class="">
                                            <div class="overlay">
                                                <i class="fas fa-2x fa-sync fa-spin"></i>
                                            </div>
                                            <img class="card-img-top rounded-top w-100" role-name="flyerImg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custTabBack" role="tabpanel" aria-labelledby="custTabBack-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title" role-name="title"></div>
                                        <div class="">
                                            <div class="overlay">
                                                <i class="fas fa-2x fa-sync fa-spin"></i>
                                            </div>
                                            <img class="card-img-top rounded-top w-100" role-name="flyerUraImg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
