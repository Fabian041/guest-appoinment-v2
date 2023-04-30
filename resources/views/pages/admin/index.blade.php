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
                    <table class="table table-responsive" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">PIC</th>
                                <th class="text-center">Visitor Name <small class="text-muted"> / 訪問者名</small></th>
                                <th class="text-center">Visitor Company <small class="text-muted"> / 合計ゲスト</small></th>
                                <th class="text-center">Total Guest <small class="text-muted"> / 会社</small></th>
                                <th class="text-center">Visit Purpose <small class="text-muted"> / 訪問目的</small></th>
                                <th class="text-center">Visit Date <small class="text-muted"> / 訪問日</small></th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$appointments->isEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="display-4">{{ $appointment->pic->name }}</td>
                                        <td class="display-4">{{ $appointment->name }}</td>
                                        <td class="display-4">{{ $appointment->user->company }}</td>
                                        <td class="display-4">{{ $appointment->guest }}</td>
                                        <td class="display-4">{{ $appointment->purpose }}</td>
                                        <td class="display-4">
                                            {{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</td>
                                        <td>

                                            {{-- detail --}}
                                            <button data-toggle="modal" data-target="#detailModal-{{ $appointment->id }}"
                                                class="btn btn-icons btn-inverse-info" data-toggle="tooltip" title="Detail">
                                                <i class="mdi mdi-information"></i>
                                            </button>

                                            {{-- apporval --}}
                                            {{-- if the pic of ticket is spv down (1) and the auth user is manager up, then show the approval button modal, because the ticket is appear when already approved by the pic , when the ticket not approved yet by the pic, the ticket should not appear in the list --}}
                                            @if ($appointment->pic->occupation == 1 && auth()->user()->occupation == 2)
                                                <button data-toggle="modal"
                                                    data-target="#approveModal-{{ $appointment->id }}" type="submit"
                                                    class="btn btn-icons btn-inverse-success" data-toggle="tooltip"
                                                    title="Approve">
                                                    <i class="mdi mdi-check-circle"></i>
                                                </button>
                                                {{-- if the pic of the ticket is spv down and the auth user is spv (the pic itself) then show the facility button modal --}}
                                            @elseif($appointment->pic->occupation == 1 && auth()->user()->occupation == 1)
                                                <button data-toggle="modal"
                                                    data-target="#facilityModal-{{ $appointment->id }}"
                                                    onclick="getRoom({{ $appointment->id }})" type="submit"
                                                    class="btn btn-icons btn-inverse-success" data-toggle="tooltip"
                                                    title="Approve">
                                                    <i class="mdi mdi-check-circle"></i>
                                                </button>
                                                {{-- if the pic of the ticket is manager up and the auth user is manager (the pic itself) then show the facility button modal --}}
                                            @elseif($appointment->pic->occupation == 2 && auth()->user()->occupation == 2)
                                                <button data-toggle="modal"
                                                    data-target="#facilityModal-{{ $appointment->id }}"
                                                    onclick="getRoom({{ $appointment->id }})" type="submit"
                                                    class="btn btn-icons btn-inverse-success" data-toggle="tooltip"
                                                    title="Approve">
                                                    <i class="mdi mdi-check-circle"></i>
                                                </button>
                                            @endif

                                            {{-- reject --}}
                                            <button data-toggle="modal" data-target="#rejectModal-{{ $appointment->id }}"
                                                type="submit" class="btn btn-icons btn-inverse-danger"
                                                data-toggle="tooltip" title="Reject">
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
                        <div class="modal fade" id="detailModal-{{ $appointment->id }}" data-backdrop="static"
                            data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body ">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ticket Details</h5>
                                            <button type="button px-4" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="px-4 py-1">

                                            <div class="text-center">

                                                <img class="rounded"
                                                    src="{{ asset('uploads/selfie/' . $appointment->selfie) }}"
                                                    width="300" height="200">
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
                                                <span class="text-muted">Visit Date</span>
                                                <span
                                                    class="font-weight-bold">{{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</span>
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



                                        </div>
                                        <hr />
                                        <span class="mx-4 my-1 font-weight-bold h4">Facility</span>
                                        <div class="row d-flex justify-content-center my-5">

                                            <div class="col-md-12">
                                                <div class="wizard">
                                                    <div class="wizard-inner">
                                                        <div class="connecting-line"></div>
                                                        <ul class="nav nav-tabs pr-3" role="tablist">
                                                            <li role="presentation" class="active">
                                                                <a href="#step1-non" data-toggle="tab"
                                                                    aria-controls="step1-non" role="tab"
                                                                    aria-expanded="true"><span class="round-tab">1 </span>
                                                                    <i class="pl-4">Makanan</i></a>
                                                            </li>
                                                            <li role="presentation" class="disabled">
                                                                <a href="#step2-non" data-toggle="tab"
                                                                    aria-controls="step2-non" role="tab"
                                                                    aria-expanded="false"><span class="round-tab">2</span>
                                                                    <i class="pl-4">Minuman</i></a>
                                                            </li>
                                                            <li role="presentation" class="disabled">
                                                                <a href="#step3-non" data-toggle="tab"
                                                                    aria-controls="step3-non" role="tab"><span
                                                                        class="round-tab">3</span> <i class="pl-4">Plan
                                                                        Tour</i></a>
                                                            </li>
                                                            <li role="presentation" class="disabled">
                                                                <a href="#step4-non" data-toggle="tab"
                                                                    aria-controls="step4-non" role="tab"><span
                                                                        class="round-tab">4</span> <i
                                                                        class="pl-4">Parkir</i></a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                    <div class="tab-content" id="main_form">
                                                        <div class="tab-pane active" role="tabpanel" id="step1-non">
                                                            <h4 class="text-center">Makanan</h4>
                                                            <div class="container ml-5 mt-5">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="boxes">
                                                                            <input type="checkbox" id="dry-food-non"
                                                                                name="dry-food-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->snack_kering > 0 ? 'checked' : '' ?>
                                                                                disabled>

                                                                            <label for="dry-food-non">Snack Kering :
                                                                                <span>{{ $appointment->facility_detail->snack_kering ?? '0' }}</label>


                                                                            <input type="checkbox" id="wet-food-non"
                                                                                name="wet-food-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->snack_basah > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="wet-food-non">Snack Basah:
                                                                                <span>{{ $appointment->facility_detail->snack_basah ?? '0' }}</label>

                                                                            <input type="checkbox" id="lunch-non"
                                                                                name="lunch-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->makan_siang > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="lunch-non">Makan Siang :
                                                                                <span>{{ $appointment->facility_detail->makan_siang ?? '0' }}</label>

                                                                            <input type="checkbox" id="candy-non"
                                                                                name="candy-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->permen > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="candy-non">Permen :
                                                                                <span>{{ $appointment->facility_detail->permen ?? '0' }}</label>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" role="tabpanel" id="step2-non">
                                                            <h4 class="text-center">Minuman</h4>
                                                            <div class="container ml-5 mt-5">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="boxes">
                                                                            <input type="checkbox" id="coffee-non"
                                                                                name="coffee-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->kopi > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="coffee-non">Coffee :
                                                                                <span>{{ $appointment->facility_detail->kopi ?? '0' }}</label>

                                                                            <input type="checkbox" id="tea-non"
                                                                                name="tea-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->teh > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="tea-non">Tea :
                                                                                <span>{{ $appointment->facility_detail->teh ?? '0' }}</label>

                                                                            <input type="checkbox" id="soft-drink-non"
                                                                                name="soft-drink-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->soft_drink > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="soft-drink-non">Soft Drink :
                                                                                <span>{{ $appointment->facility_detail->soft_drink ?? '0' }}</label>

                                                                            <input type="checkbox" id="mineral-water-non"
                                                                                name="mineral-water-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->air_mineral > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="mineral-water-non">Mineral Water :
                                                                                <span>{{ $appointment->facility_detail->air_mineral ?? '0' }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" role="tabpanel" id="step3-non">
                                                            <h4 class="text-center">Plant Tour</h4>
                                                            <div class="container ml-5 mt-5">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="boxes">
                                                                            <input type="checkbox" id="helm-non"
                                                                                name="helm-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->helm > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="helm-non">Helm :
                                                                                <span>{{ $appointment->facility_detail->helm ?? '0' }}</label>

                                                                            <input type="checkbox" id="handuk-non"
                                                                                name="handuk-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->handuk > 0 ? 'checked' : '' ?>
                                                                                disabled>

                                                                            <label for="handuk-non">Handuk :
                                                                                <span>{{ $appointment->facility_detail->handuk ?? '0' }}</label>

                                                                            <input type="checkbox" id="speaker-non"
                                                                                name="speaker-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->speaker > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="speaker-non">Speaker :
                                                                                <span>{{ $appointment->facility_detail->speaker ?? '0' }}</label>


                                                                            <input type="checkbox"
                                                                                id="speaker-wireless-non"
                                                                                name="speaker-wireless-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->speaker_wireless > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="speaker-wireless-non">Speaker
                                                                                Wireless :
                                                                                <span>{{ $appointment->facility_detail->speaker_wireless ?? '0' }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" role="tabpanel" id="step4-non">
                                                            <h4 class="text-center">Parkir</h4>
                                                            <div class="container ml-5 mt-5">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="boxes">
                                                                            <input type="checkbox" id="motor-non"
                                                                                name="motor-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->motor > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="motor-non">Motor :
                                                                                <span>{{ $appointment->facility_detail->motor ?? '0' }}</label>


                                                                            <input type="checkbox" id="mobil-non"
                                                                                name="mobil-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->mobil > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="mobil-non">Mobil :
                                                                                <span>{{ $appointment->facility_detail->mobil ?? '0' }}</label>



                                                                            <input type="checkbox" id="mini-bus-non"
                                                                                name="mini-bus-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->mini_bus > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="mini-bus-non">Mini Bus :
                                                                                <span>{{ $appointment->facility_detail->mini_bus ?? '0' }}</label>

                                                                            <input type="checkbox" id="bus-non"
                                                                                name="bus-non"
                                                                                <?= $appointment->facility_detail != null && $appointment->facility_detail->bus > 0 ? 'checked' : '' ?>
                                                                                disabled>
                                                                            <label for="bus-non">Bus :
                                                                                <span>{{ $appointment->facility_detail->bus ?? '0' }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="mx-4 mt-2">
                                            <div>Other :</div>
                                            <div>
                                                {{ $appointment->facility_detail != null ? $appointment->facility_detail->other : '' }}
                                            </div>
                                        </div>
                                        <div class="text-center mt-5">
                                            <a class="btn btn-primary py-3"
                                                href="{{ asset('uploads/doc/' . $appointment->doc) }}" download="">
                                                <i class="mdi mdi-cloud-check pr-2"></i>Download Document
                                            </a>
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
                        <div class="modal fade auto-off" id="approveModal-{{ $appointment->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="approveModal-{{ $appointment->id }}" aria-hidden="true">
                            <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approval confirmation</h5>
                                        <button type="button px-4" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure want to <strong>approve</strong> this ticket?</p>
                                    </div>
                                    <form action="/approval/approve/{{ $appointment->id }}" method="post"
                                        class="d-inline">
                                        {{ csrf_field() }}
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Modal Ends -->

                    <!-- Modal -->
                    {{-- Facility Modal --}}
                    @foreach ($appointments as $appointment)
                        <div class="modal fade auto-off" id="facilityModal-{{ $appointment->id }}" tabindex="-1"
                            role="dialog" aria-hidden="true" aria-labelledby="facilityModal-{{ $appointment->id }}">
                            <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Facility Needs</h5>
                                        <button type="button px-4" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form method="POST" action="/approval/approve/{{ $appointment->id }}"
                                        class="login-box">
                                        <h6 class="mx-3 text-sm text-muted">Facility is not necessary, only if needed</h6>
                                        {{ csrf_field() }}
                                        @if ($appointment->facility_eligble)
                                            <div class="row d-flex justify-content-center my-5">
                                                <div class="col-md-12">
                                                    <div class="wizard">
                                                        <div class="wizard-inner">
                                                            <div class="connecting-line"></div>
                                                            <ul class="nav nav-tabs pr-3" role="tablist">
                                                                <li role="presentation" class="active">
                                                                    <a href="#step1" data-toggle="tab"
                                                                        aria-controls="step1" role="tab"
                                                                        aria-expanded="true"><span class="round-tab">1
                                                                        </span> <i class="pl-4">Makanan</i></a>
                                                                </li>
                                                                <li role="presentation" class="disabled">
                                                                    <a href="#step2" data-toggle="tab"
                                                                        aria-controls="step2" role="tab"
                                                                        aria-expanded="false"><span
                                                                            class="round-tab">2</span> <i
                                                                            class="pl-4">Minuman</i></a>
                                                                </li>
                                                                <li role="presentation" class="disabled">
                                                                    <a href="#step3" data-toggle="tab"
                                                                        aria-controls="step3" role="tab"><span
                                                                            class="round-tab">3</span> <i
                                                                            class="pl-4">Plan Tour</i></a>
                                                                </li>
                                                                <li role="presentation" class="disabled">
                                                                    <a href="#step4" data-toggle="tab"
                                                                        aria-controls="step4" role="tab"><span
                                                                            class="round-tab">4</span> <i
                                                                            class="pl-4">Parkir</i></a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                        <div class="tab-content" id="main_form">
                                                            <div class="tab-pane active" role="tabpanel" id="step1">
                                                                <h4 class="text-center">Makanan</h4>
                                                                <div class="container ml-5 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <div class="boxes">
                                                                                <input type="checkbox" id="dry-food"
                                                                                    name="dry-food">
                                                                                <label for="dry-food">Snack Kering</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="dry-food-quantity"
                                                                                    name="dry-food-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="wet-food"
                                                                                    name="wet-food">
                                                                                <label for="wet-food">Snack Basah</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="wet-food-quantity"
                                                                                    name="wet-food-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="lunch"
                                                                                    name="lunch">
                                                                                <label for="lunch">Makan Siang</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="lunch-quantity"
                                                                                    name="lunch-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="candy"
                                                                                    name="candy">
                                                                                <label for="candy">Permen</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="candy-quantity"
                                                                                    name="candy-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" role="tabpanel" id="step2">
                                                                <h4 class="text-center">Minuman</h4>
                                                                <div class="container ml-5 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <div class="boxes">
                                                                                <input type="checkbox" id="coffee"
                                                                                    name="coffee">
                                                                                <label for="coffee">Coffee</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="coffee-quantity"
                                                                                    name="coffee-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="tea"
                                                                                    name="tea">
                                                                                <label for="tea">Tea</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="tea-quantity" name="tea-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="soft-drink"
                                                                                    name="soft-drink">
                                                                                <label for="soft-drink">Soft Drink</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="soft-drink-quantity"
                                                                                    name="soft-drink-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="mineral-water"
                                                                                    name="mineral-water">
                                                                                <label for="mineral-water">Mineral
                                                                                    Water</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="mineral-water-quantity"
                                                                                    name="mineral-water-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" role="tabpanel" id="step3">
                                                                <h4 class="text-center">Plant Tour</h4>
                                                                <div class="container ml-5 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <div class="boxes">
                                                                                <input type="checkbox" id="helm"
                                                                                    name="helm">
                                                                                <label for="helm">Helm</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="helm-quantity"
                                                                                    name="helm-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="handuk"
                                                                                    name="handuk">
                                                                                <label for="handuk">Handuk</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="handuk-quantity"
                                                                                    name="handuk-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="speaker"
                                                                                    name="speaker">
                                                                                <label for="speaker">Speaker</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="speaker-quantity"
                                                                                    name="speaker-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox"
                                                                                    id="speaker-wireless"
                                                                                    name="speaker-wireless">
                                                                                <label for="speaker-wireless">Wireless
                                                                                    Speaker</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="speaker-wireless-quantity"
                                                                                    name="speaker-wireless-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" role="tabpanel" id="step4">
                                                                <h4 class="text-center">Parkir</h4>
                                                                <div class="container ml-5 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <div class="boxes">
                                                                                <input type="checkbox" id="motor"
                                                                                    name="motor">
                                                                                <label for="motor">Motor</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="motor-quantity"
                                                                                    name="motor-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="mobil"
                                                                                    name="mobil">
                                                                                <label for="mobil">mobil</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="mobil-quantity"
                                                                                    name="mobil-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="mini-bus"
                                                                                    name="mini-bus">
                                                                                <label for="mini-bus">Mini Bus</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="mini-bus-quantity"
                                                                                    name="mini-bus-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">

                                                                                <input type="checkbox" id="bus"
                                                                                    name="bus">
                                                                                <label for="bus">Bus</label>

                                                                                <input type="number"
                                                                                    class="form-control mb-3"
                                                                                    id="bus-quantity" name="bus-quantity"
                                                                                    placeholder="Quantity..."
                                                                                    min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-4 mt-2">
                                                <div>Other :</div>
                                                <div>
                                                    <textarea name="other-value" class="form-control" rows="3">{{ $appointment->facility_detail != null ? $appointment->facility_detail->other : '  ' }}</textarea>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row d-flex justify-content-center my-5">
                                                <p class="badge badge-secondary">Facility only allowed on Appointment H-2
                                                </p>
                                            </div>
                                        @endif
                                        <div class="container">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="inputEmail3" class="col-form-label">Select Room <small
                                                            class="text-muted pl-0">/ Pilih Ruangan / 部屋を選択</small></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select class="form-control mt-1" id="room-{{$appointment->id}}" name="room">
                                                        <option value="null" selected>-- Select Room --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit & Approve</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Modal Ends -->

                    <!-- Modal -->
                    {{-- rejection Modal --}}
                    @foreach ($appointments as $appointment)
                        <div class="modal fade auto-off" id="rejectModal-{{ $appointment->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="demoModal-{{ $appointment->id }}" aria-hidden="true">
                            <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Rejection confirmation</h5>
                                        <button type="button px-4" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="/approval/reject/{{ $appointment->id }}" method="post"
                                        class="d-inline">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <p>Are you sure want to <strong>reject</strong> this ticket?</p>
                                            <input type="text" class="form-control mt-2" id="nama"
                                                name="note" placeholder="Insert Reason or note...">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
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
        function getRoom(id) {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            const day = currentDate.getDate().toString().padStart(2, '0');

            $.ajax({
                url: '/get-room',
                type: 'GET',
                data: {
                    date: `${year}-${month}-${day}`,
                },
                success: function(room) {

                    $('#room-' + id).empty();
                    $('#room-' + id).append(`<option value='null'>-- Select Room --</option>`);
                    $.each(room, function(key, value) {
                        $('#room-' + id).append(`<option value='${value.id}'> ${value.name}</option>`);
                    });

                }
            });

        }
        $(document).ready(function() {

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });

            $('#allTicket').DataTable({
                "lengthChange": false
            });

            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

                var target = $(e.target);

                if (target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function(e) {

                var active = $('.wizard .nav-tabs li.active');
                active.next().removeClass('disabled');
                nextTab(active);

            });
            $(".prev-step").click(function(e) {

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
            const coffee = document.getElementById('coffee');
            const tea = document.getElementById('tea');
            const mineral_water = document.getElementById('mineral-water');
            const soft_drink = document.getElementById('soft-drink');
            const helm = document.getElementById('helm');
            const handuk = document.getElementById('handuk');
            const speaker = document.getElementById('speaker');
            const speaker_wireless = document.getElementById('speaker-wireless');
            const motor = document.getElementById('motor');
            const mobil = document.getElementById('mobil');
            const mini_bus = document.getElementById('mini-bus');
            const bus = document.getElementById('bus');
            // const checkbox = document.getElementById('dry-food');
            // const checkbox = document.getElementById('dry-food');

            // food
            $('#dry-food-quantity').hide();
            $('#wet-food-quantity').hide();
            $('#lunch-quantity').hide();
            $('#candy-quantity').hide();
            $("input[type='checkbox']").on("change", function() {
                if (this.checked) {
                    if (this == dry_food) {
                        $('#dry-food-quantity').show();
                    } else if (this == wet_food) {
                        $('#wet-food-quantity').show();
                    } else if (this == lunch) {
                        $('#lunch-quantity').show();
                    } else if (this == candy) {
                        $('#candy-quantity').show();
                    }
                } else {
                    if (this == dry_food) {
                        $('#dry-food-quantity').hide();
                    } else if (this == wet_food) {
                        $('#wet-food-quantity').hide();
                    } else if (this == lunch) {
                        $('#lunch-quantity').hide();
                    } else if (this == candy) {
                        $('#candy-quantity').hide();
                    }
                }
            });

            // drink
            $('#coffee-quantity').hide();
            $('#tea-quantity').hide();
            $('#soft-drink-quantity').hide();
            $('#mineral-water-quantity').hide();
            $("input[type='checkbox']").on("change", function() {
                if (this.checked) {
                    if (this == coffee) {
                        $('#coffee-quantity').show();
                    } else if (this == tea) {
                        $('#tea-quantity').show();
                    } else if (this == soft_drink) {
                        $('#soft-drink-quantity').show();
                    } else if (this == mineral_water) {
                        $('#mineral-water-quantity').show();
                    }
                } else {
                    if (this == coffee) {
                        $('#coffee-quantity').hide();
                    } else if (this == tea) {
                        $('#tea-quantity').hide();
                    } else if (this == soft_drink) {
                        $('#soft-drink-quantity').hide();
                    } else if (this == mineral_water) {
                        $('#mineral-water-quantity').hide();
                    }
                }
            });

            // Plant tour
            $('#helm-quantity').hide();
            $('#handuk-quantity').hide();
            $('#speaker-quantity').hide();
            $('#speaker-wireless-quantity').hide();
            $("input[type='checkbox']").on("change", function() {
                if (this.checked) {
                    if (this == helm) {
                        $('#helm-quantity').show();
                    } else if (this == handuk) {
                        $('#handuk-quantity').show();
                    } else if (this == speaker) {
                        $('#speaker-quantity').show();
                    } else if (this == speaker_wireless) {
                        $('#speaker-wireless-quantity').show();
                    }
                } else {
                    if (this == helm) {
                        $('#helm-quantity').hide();
                    } else if (this == handuk) {
                        $('#handuk-quantity').hide();
                    } else if (this == speaker) {
                        $('#speaker-quantity').hide();
                    } else if (this == speaker_wireless) {
                        $('#speaker-wireless-quantity').hide();
                    }
                }
            });

            // Parkir
            $('#motor-quantity').hide();
            $('#mobil-quantity').hide();
            $('#mini-bus-quantity').hide();
            $('#bus-quantity').hide();
            $("input[type='checkbox']").on("change", function() {
                if (this.checked) {
                    if (this == motor) {
                        $('#motor-quantity').show();
                    } else if (this == mobil) {
                        $('#mobil-quantity').show();
                    } else if (this == mini_bus) {
                        $('#mini-bus-quantity').show();
                    } else if (this == bus) {
                        $('#bus-quantity').show();
                    }
                } else {
                    if (this == motor) {
                        $('#motor-quantity').hide();
                    } else if (this == mobil) {
                        $('#mobil-quantity').hide();
                    } else if (this == mini_bus) {
                        $('#mini-bus-quantity').hide();
                    } else if (this == bus) {
                        $('#bus-quantity').hide();
                    }
                }
            });

        });
    </script>
@endpush
