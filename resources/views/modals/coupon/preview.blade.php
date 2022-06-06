<div class="modal fade" id="couponPreview">
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
                                <a class="nav-link active" id="custTabFront-tab" data-toggle="pill" href="#custTabFront" role="tab" aria-controls="custTabFront" aria-selected="true">{{ __('words.CouponDetails') }}</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="custTabBack-tab" data-toggle="pill" href="#custTabBack" role="tab" aria-controls="custTabBack" aria-selected="false">{{ __('words.CouponCard') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mobile">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade  show active" id="custTabFront" role="tabpanel" aria-labelledby="custTabFront-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div>
                                            <div class="overlay">
                                                <i class="fas fa-2x fa-sync fa-spin"></i>
                                            </div>
                                            <div class="container">
                                                <div class="row justify-content-center mb-4 img-holder">
                                                    <img class="card-img-top" role-name="opening-image">
                                                </div>
                                                <span role-name="end-date"></span>
                                                <h3 role-name="cupon-name"></h3>
                                                <div class="row d-flex justify-content-between mb-3 pt-2">
                                                    <span class="p-2"><button class="btn btn-default rounded-pill" role-name="use-limit"></button></span>
                                                    <span class="p-2  ml-auto"><button class="btn btn-custom-primary" role-name="grant-point"></button></span>
                                                </div>
                                                <div role-name="coupon-text"></div>
                                                <div class="row pt-5">
                                                    <button class="btn btn-custom-primary btn-lg w-100"> {{ __('words.Entry') }} </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custTabBack" role="tabpanel" aria-labelledby="custTabBack-tab">
                                <center>
                                    <div class="card w-75">
                                        <div class="card-body">
                                            <div >
                                                <div class="overlay">
                                                    <i class="fas fa-2x fa-sync fa-spin"></i>
                                                </div>
                                            </div>
                                            <div class="container">
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <img id="openingImg" role-name="opening-image">
                                                    </div>
                                                    <div id="card-details" class="col-6">
                                                        <h3 role-name="cupon-name"></h3>
                                                        <button class="btn btn-custom-primary" role-name="grant-point"></button>
                                                        <h6 role-name="end-date"></h6>
                                                        <button class="btn btn-default rounded-pill" role-name="use-limit"></button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button class="btn btn-custom-secondary btn-lg w-100">{{ __('words.ViewTheDetails') }}</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button class="btn btn-custom-primary btn-lg w-100"> {{ __('words.Entry') }} </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
