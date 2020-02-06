@extends('demo.layouts.app')
@php
    //dd($result);
@endphp
@section('content')
    <!-- ========================= SECTION CONTENT ========================= -->

    <section class="section-content bg padding-y">
        <div class="container">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3-24"><strong>Вы здесь:</strong></div> <!-- col.// -->
                        <nav class="col-md-18-24">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Главная</a></li>
                                <li class="breadcrumb-item"><a href="#">Каталог</a></li>
                            </ol>
                        </nav> <!-- col.// -->
                    </div> <!-- row.// -->
                </div> <!-- col.// -->
            </div> <!-- row.// -->


            <section class="section-pagetop">
                <h2 class="title-doc">Каталог продукции</h2>
            </section>

            <div id="catalog-component" data-api="{{$apiUrl}}" data-filter="{{$filterParams}}" data-references="{{$references}}"></div>

        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION CONTENT END// ========================= -->
    <script type="text/babel" src="/frontend/js/catalog.js"></script>
@endsection
