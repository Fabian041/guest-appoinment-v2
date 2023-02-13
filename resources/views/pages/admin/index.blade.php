@extends('layout.master')

@push('plugin-styles')
<!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin">
        @if (session('approved'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('approved') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (session('reject'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('reject') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Ticket list <small class="text-muted"> / チケット一覧</small></h4>
                <table class="table table-responsive-lg" id="allTicket">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Visitor Name <small class="text-muted"> / 訪問者名</small></th>
                            <th class="text-center">Visit Purpose <small class="text-muted"> / 訪問目的</small></th>
                            <th class="text-center">Visit Plan<small class="text-muted"> / 見学プラン</small></th>
                            <th class="text-center">Visit Date <small class="text-muted"> / 訪問日</small></th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @if(!$appointments->isEmpty())
                        @foreach ($appointments as $appointment)
                        
                        <tr>
                            <td class="display-4">{{ $loop->iteration }}</td>
                            <td class="display-4">{{ $appointment->name }}</td>
                            <td class="display-4">{{ $appointment->purpose }}</td>
                            <td class="display-4">{{ $appointment->frequency }}</td>
                            <td class="display-4">{{ Carbon\Carbon::parse($appointment->start_date)->toFormattedDateString() }} - {{ Carbon\Carbon::parse($appointment->end_date)->toFormattedDateString() }}</td>
                            {{-- <td class="display-4">{{ $appointment->guest }}</td> --}}
                            <td><span class="badge badge-pill badge-warning p-2 text-light">{{ $appointment->status }}</span></td>
                            <td>
                                
                                {{-- detail --}}
                                <button data-toggle="modal" data-target="#detailModal-{{ $appointment->id }}"  class="btn btn-icons btn-inverse-info" data-toggle="tooltip" title="Detail">
                                    <i class="mdi mdi-information"></i>
                                </button>
                                
                                {{-- apporval --}}
                                <button data-toggle="modal" data-target="#approveModal-{{ $appointment->id }}" type="submit" class="btn btn-icons btn-inverse-success" data-toggle="tooltip" title="Approve">
                                    <i class="mdi mdi-check-circle"></i>
                                </button>
                                
                                {{-- reject --}}
                                <button data-toggle="modal" data-target="#rejectModal-{{ $appointment->id }}" type="submit" class="btn btn-icons btn-inverse-danger" data-toggle="tooltip" title="Reject">
                                    <i class="mdi mdi-close-circle"></i>
                                </button>
                                
                            </td>
                        </tr>
                        
                        @endforeach
                        @endif
                    </tbody>
                </table>
                
                <!-- Modal -->
                {{-- Detail Modal --}}
                @foreach ($appointments as $appointment)   
                <div class="modal fade" id="detailModal-{{ $appointment->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body ">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ticket Details</h5>
                                    <button type="button px-4" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                
                                <div class="px-4 py-1">
                                    
                                    <div class="text-center">
                                        
                                        <img class="rounded" src="{{ asset('uploads/selfie/'. $appointment->selfie) }}" width="300" height="200">
                                    </div>
                                    {{-- <span class="theme-color font-weight-bold">Ticket Detail</span> --}}
                                    <div class="mb-3">
                                        <hr class="new1">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold h4">Personal Data</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visitor Name</span>
                                        <span class="font-weight-bold">{{ $appointment->name }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visitor Company</span>
                                        <span class="font-weight-bold">{{ $appointment->user->company }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between pt-4">
                                        <span class="font-weight-bold h4">Plan Visit</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visit Purpose</span>
                                        <span class="font-weight-bold">{{ $appointment->purpose }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visit Frequency</span>
                                        <span class="font-weight-bold">{{ $appointment->frequency}}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visit Date</span>
                                        <span class="font-weight-bold">{{ Carbon\Carbon::parse($appointment->start_date)->toFormattedDateString() }} - {{ Carbon\Carbon::parse($appointment->end_date)->toFormattedDateString() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Visit Time</span>
                                        <span class="font-weight-bold">{{ $appointment->time }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Total Visitor</span>
                                        <span class="font-weight-bold">{{ $appointment->guest }}</span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <hr class="new1">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold">PIC</span>
                                        <span class="font-weight-bold">{{ $appointment->pic->name }}</span>
                                    </div>
                                    
                                    <div class="text-center mt-5">
                                        <a class="btn btn-primary py-3" href="{{ asset('uploads/doc/' . $appointment->doc) }}" download="">
                                            <i class="mdi mdi-cloud-check pr-2"></i>Download Document
                                        </a>   
                                    </div>                   
                                    
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Modal Ends -->
                
                <!-- Modal -->
                {{-- Approval Modal --}}
                @foreach ($appointments as $appointment)   
                <div class="modal fade auto-off" id="approveModal-{{ $appointment->id }}" tabindex="-1" role="dialog" aria-labelledby="demoModal-{{ $appointment->id }}" aria-hidden="true">
                    <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Approval confirmation</h5>
                                <button type="button px-4" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Next, you will fill in the details of the <strong> facilities </strong> needed by guests</p>
                            </div>
                            <div class="modal-footer">
                                <button data-toggle="modal" data-target="#facilityModal" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Approve" data-dismiss="modal">
                                    Continue
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Modal Ends -->
                
                <!-- Modal -->
                {{-- Approval Modal --}}
                <div class="modal fade auto-off" id="facilityModal" tabindex="-1" role="dialog"  aria-hidden="true">
                    <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Facility Needs</h5>
                                <button type="button px-4" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="row d-flex justify-content-center my-5">
                                <div class="col-md-12">
                                    <div class="wizard">
                                        <div class="wizard-inner">
                                            <div class="connecting-line"></div>
                                            <ul class="nav nav-tabs pr-3" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab">1 </span> <i>Step 1</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab">2</span> <i>Step 2</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab"><span class="round-tab">3</span> <i>Step 3</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab"><span class="round-tab">4</span> <i>Step 4</i></a>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <form role="form" action="index.html" class="login-box">
                                            <div class="tab-content" id="main_form">
                                                <div class="tab-pane active" role="tabpanel" id="step1">
                                                    <h4 class="text-center">Food</h4>
                                                    <div class="container ml-5 mt-4">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="boxes">
                                                                    <input type="checkbox" id="dry-food" name="dry-food">
                                                                    <label for="dry-food">Dry Food</label>

                                                                    <input type="text" class="form-control mb-3" id="dry-food-quantity" name="dry-food-quantity" placeholder="Quantity...">
                                                                    
                                                                    <input type="checkbox" id="wet-food" name="wet-food">
                                                                    <label for="wet-food">Wet Food</label>

                                                                    <input type="text" class="form-control mb-3" id="wet-food-quantity" name="wet-food-quantity" placeholder="Quantity...">

                                                                    <input type="checkbox" id="lunch" name="lunch">
                                                                    <label for="lunch">Lunch</label>

                                                                    <input type="text" class="form-control mb-3" id="lunch-quantity" name="lunch-quantity" placeholder="Quantity...">

                                                                    <input type="checkbox" id="candy" name="candy">
                                                                    <label for="candy">Candy</label>

                                                                    <input type="text" class="form-control mb-3" id="candy-quantity" name="candy-quantity" placeholder="Quantity....">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step2">
                                                    <h4 class="text-center">Drink</h4>
                                                    <div class="container ml-5 mt-4">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="boxes">
                                                                    <input type="checkbox" id="coffee" name="coffee">
                                                                    <label for="coffee">Coffee</label>

                                                                    <input type="text" class="form-control mb-3" id="coffee" name="coffee" placeholder="Quantity....">

                                                                    <input type="checkbox" id="tea" name="tea">
                                                                    <label for="tea">Tea</label>

                                                                    <input type="text" class="form-control mb-3" id="tea" name="tea" placeholder="Quantity....">

                                                                    <input type="checkbox" id="soft-drink" name="soft-drink">
                                                                    <label for="soft-drink">Soft Drink</label>

                                                                    <input type="text" class="form-control mb-3" id="soft-drink" name="soft-drink" placeholder="Quantity....">

                                                                    <input type="checkbox" id="mineral-water" name="mineral-water">
                                                                    <label for="mineral-water">Mineral Water</label>

                                                                    <input type="text" class="form-control mb-3" id="mineral-water" name="mineral-water" placeholder="Quantity....">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step3">
                                                    <h4 class="text-center">Plant Tour</h4>
                                                    <div class="container ml-5 mt-4">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="boxes">
                                                                    <input type="checkbox" id="helmet" name="helmet">
                                                                    <label for="helmet">Helmet</label>

                                                                    <input type="text" class="form-control mb-3" id="helmet" name="helmet" placeholder="Quantity....">

                                                                    <input type="checkbox" id="towel" name="towel">
                                                                    <label for="towel">Towel</label>

                                                                    <input type="text" class="form-control mb-3" id="towel" name="towel" placeholder="Quantity....">

                                                                    <input type="checkbox" id="speaker" name="speaker">
                                                                    <label for="speaker">Speaker</label>

                                                                    <input type="text" class="form-control mb-3" id="speaker" name="speaker" placeholder="Quantity....">

                                                                    <input type="checkbox" id="wireless-speaker" name="wireless-speaker">
                                                                    <label for="wireless-speaker">Wireless Speaker</label>

                                                                    <input type="text" class="form-control mb-3" id="wireless-speaker" name="wireless-speaker" placeholder="Quantity....">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step4">
                                                    <h4 class="text-center">Parking</h4>
                                                    <div class="container ml-5 mt-4">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="boxes">
                                                                    <input type="checkbox" id="motor-cycle" name="motor-cycle">
                                                                    <label for="motor-cycle">Motor Cycle</label>

                                                                    <input type="text" class="form-control mb-3" id="motor-cycle" name="motor-cycle" placeholder="Quantity....">

                                                                    <input type="checkbox" id="car" name="car">
                                                                    <label for="car">Car</label>

                                                                    <input type="text" class="form-control mb-3" id="car" name="car" placeholder="Quantity....">

                                                                    <input type="checkbox" id="mini-bus" name="mini-bus">
                                                                    <label for="mini-bus">Mini Bus</label>

                                                                    <input type="text" class="form-control mb-3" id="mini-bus" name="mini-bus" placeholder="Quantity....">

                                                                    <input type="checkbox" id="bus" name="bus">
                                                                    <label for="bus">Bus</label>

                                                                    <input type="text" class="form-control mb-3" id="bus" name="bus" placeholder="Quantity....">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-toggle="modal" data-target="#facilityModal" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Approve" data-dismiss="modal">
                                    Continue
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Ends -->
                
                <!-- Modal -->
                {{-- rejection Modal --}}
                @foreach ($appointments as $appointment)   
                <div class="modal fade auto-off" id="rejectModal-{{ $appointment->id }}" tabindex="-1" role="dialog" aria-labelledby="demoModal-{{ $appointment->id }}" aria-hidden="true">
                    <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Rejection confirmation</h5>
                                <button type="button px-4" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/approval/reject/{{ $appointment->id }}" method="post" class="d-inline">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <p>Are you sure want to <strong>reject</strong> this ticket?</p>
                                    <input type="text" class="form-control mt-2" id="nama" name="note" placeholder="Insert Reason or note...">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Modal Ends -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
{!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
{!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
{!! Html::script('/assets/js/dashboard.js') !!}
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        
        $('#allTicket').DataTable({
            "lengthChange": false
        });
        
        $('.nav-tabs > li a[title]').tooltip();
        
        //Wizard
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            
            var target = $(e.target);
            
            if (target.parent().hasClass('disabled')) {
                return false;
            }
        });
        
        $(".next-step").click(function (e) {
            
            var active = $('.wizard .nav-tabs li.active');
            active.next().removeClass('disabled');
            nextTab(active);
            
        });
        $(".prev-step").click(function (e) {
            
            var active = $('.wizard .nav-tabs li.active');
            prevTab(active);
            
        });
        
        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }
        
        
        $('.nav-tabs').on('click', 'li', function() {
            $('.nav-tabs li.active').removeClass('active');
            $(this).addClass('active');
        });
        

        const dry_food = document.getElementById('dry-food');
        const wet_food = document.getElementById('wet-food');
        const lunch = document.getElementById('lunch');
        const candy = document.getElementById('candy');
        // const checkbox = document.getElementById('dry-food');
        // const checkbox = document.getElementById('dry-food');

        $('#dry-food-quantity').hide();
        $('#wet-food-quantity').hide();
        $('#lunch-quantity').hide();
        $('#candy-quantity').hide();
        $("input[type='checkbox']").on("change", function() {
            if(this.checked) {
                if(this == dry_food){
                    $('#dry-food-quantity').show();
                }
                else if(this == wet_food){
                    $('#wet-food-quantity').show();
                }
                else if(this == lunch){
                    $('#lunch-quantity').show();
                }
                else if(this == candy){
                    $('#candy-quantity').show();
                }
            }else{
                if(this == dry_food){
                    $('#dry-food-quantity').hide();
                }
                else if(this == wet_food){
                    $('#wet-food-quantity').hide();
                }
                else if(this == lunch){
                    $('#lunch-quantity').hide();
                }
                else if(this == candy){
                    $('#candy-quantity').hide();
                }
            }
        });
        
    });
</script>
@endpush