@extends('admin/layout/app')


@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section>
        <div class="page-header-content py-3">

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.device.index') }}">Dispositivos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Novo Dispositivo</li>
            </ol>

        </div>
        <div class="row">
            <div class="col-md-6 ">
                <div class="card-header">
                    <h4>{{ __('How To Scan?') }}</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-sm btn-neutral">
                            <i class="fas fa-lightbulb"></i>&nbsp{{ __('Guide') }}
                        </a>
                    </div>
                </div>
                <div class="card qr-code">
                    
                    <div id="preload">
                        <div class="loader"></div>
                    </div>
                    <img id="qrcode-img" src="{{ $qrcodeImgSrc }}" alt="QR Code" />
                    <div class="card-footer server_connect " id="footer-qr-code" style="display: none">
                        <div class=" ">
                            {{ __('Conectado  😎 ') }}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('How To Scan?') }}</h4>
                            <div class="card-header-action">
                                <a href="#" class="btn btn-sm btn-neutral">
                                    <i class="fas fa-lightbulb"></i>&nbsp{{ __('Guide') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <img src="{{ asset('assets/images/scan-demo.gif') }}" class="w-100">
                        </div>
                        <div class="card-footer">
                            <div class="activities">
                                <div class="activity">
                                    <div class="activity-icon bg-primary text-white shadow-primary">
                                        <i class="ni ni-mobile-button"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">{{ __('Step 1') }}</span>
                                            <span class="bullet"></span>
                                        </div>
                                        <p>{{ __('Open WhatsApp on your phone') }}</p>
                                    </div>
                                </div>
                                <div class="activity">
                                    <div class="activity-icon bg-primary text-white shadow-primary">
                                        <i class="ni ni-active-40"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">{{ __('Step 2') }}</span>
                                            <span class="bullet"></span>
                                        </div>
                                        <p>{{ __('Tap Menu or Settings and select Linked Devices') }}</p>
                                    </div>
                                </div>
                                <div class="activity">
                                    <div class="activity-icon bg-primary text-white shadow-primary">
                                        <i class="ni ni-active-40"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">{{ __('Step 3') }}</span>
                                            <span class="bullet"></span>
                                        </div>
                                        <p>{{ __('Tap on Link a Device') }}</p>
                                    </div>
                                </div>
                                <div class="activity">
                                    <div class="activity-icon bg-primary text-white shadow-primary">
                                        <i class="fa fa-qrcode"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">{{ __('Step 4') }}</span>
                                            <span class="bullet"></span>
                                        </div>
                                        <p>{{ __('Point your phone to this screen to capture the code') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" name="id" id="id_device" value="{{ $device->id }}">
                <input type="hidden" name="session" id="session_device" value="{{ $device->session }}">


            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/js/device/create.js') }}"></script>
@endsection
