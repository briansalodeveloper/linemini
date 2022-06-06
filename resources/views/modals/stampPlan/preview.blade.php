<div class="modal fade" id="stampPreview">
    <div class="modal-dialog">
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
                                <a class="nav-link active" id="custTabFront-tab" data-toggle="pill" href="#custTabFront"
                                     role="tab" aria-controls="custTabFront" aria-selected="true">{{ __('words.StampImage') }}
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="custTabBack-tab" data-toggle="pill" href="#custTabBack" 
                                    role="tab" aria-controls="custTabBack" aria-selected="false">{{ __('words.Details') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mobile">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade  show active" id="custTabFront" role="tabpanel" aria-labelledby="custTabFront-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <div class="overlay">

                                            </div>
                                            <img class="card-img-top rounded-top h-100" role-name="stampImage">
                                        
                                            <button type="button" class="btn btn-danger btn-lg btn-block mt-1" role-name="imgButton">Entry</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custTabBack" role="tabpanel" aria-labelledby="custTabBack-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <img class="card-img-top rounded-top h-100" role-name="stampImage">
                                        <div class="title d-flex justify-content-center mt-3 mb-3" role-name="title"></div>
                                        <div style="border-top:2px solid rgba(10, 10, 10, 0.267)">
                                            <h4 class="mt-3 mb-3 font-weight-bold" font style="vertical-align: inherit;">Campaign period</h4>
                                            <div class="d-flex flex-row">
                                                <p font style="vertical-align: inherit;" role-name="stampStartDate">&nbsp</p>
                                                <p class="ml-1 mr-1"> - </p>
                                                <p font style="vertical-align: inherit;" role-name="stampEndDate">&nbsp</p>
                                            </div>
                                        </div>
                                        <div class="title d-flex justify-content-center mt-3 mb-3" style="color: blue">Stamp Details</div>
                                        <div role-name="stampDetail"> </div>
                                        <div class="title d-flex justify-content-center mt-3 mb-3" style="color: red">Notes</div>
                                        <div>
                                            ・ If "Entry" is displayed, be sure to press the "Entry" button on the screen to participate in the campaign.<br>
                                            ・ During the campaign period, stamps will be given from the day of shopping when the "Entry" button is pressed.<br>
                                            ・ However, capital increase, garbage bags designated by local governments, cigarettes, stamps / postcards, Takkyubin fees, shopping bags, gift certificate purchases, card issuance fees, etc. are not applicable.<Br>
                                            ・ The stamp will be reflected on or after the day after the purchase date.<Br>
                                            ・ If the gift when the stamp is accumulated is a point, the corresponding point will be given. Points will be awarded after 2 days.
                                        </div>
                                        <button type="button" class="btn btn-danger btn-lg btn-block mt-3">Entry</button>
                                        <button type="button" class="btn btn-danger btn-lg btn-block mt-3">back to the list</button>
                                        <div class="">
                                            <div class="overlay">
                                                <i class="fas fa-2x fa-sync fa-spin"></i>
                                            </div>
                                            <img class="card-img-top rounded-top h-100" role-name="flyerUraImg">
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
