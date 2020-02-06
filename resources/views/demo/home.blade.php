@extends('demo.layouts.app')

@section('content')
    <!-- ========================= SECTION INTRO ========================= -->
    <section id="intro" class="section-intro bg-secondary pt-5">
        <div class="container">
            <div class="row d-flex" style="min-height:600px;">
                <div class="col-sm-6 d-flex align-items-center">
                    <header class="intro-wrap text-white">
                        <h2 class="display-3"> Умное решение для поиска </h2>
                        <p class="lead">REST Api для решение задач поиска на проектах <br> Индексация, поиск, анализ данных</p>
                        <a href="{{ route('demo_catalog') }}" class="btn btn-warning">Начать!</a>
                        <a href="{{ route('swagger_page') }}" class="btn btn-light">Документация</a>
                    </header>  <!-- intro-wrap .// -->
                </div> <!-- col.// -->
                <div class="col-sm-6 text-right">
                    <img src="/frontend/images/items/comp.png" class="img-fluid my-5" width="500">
                </div> <!-- col.// -->
            </div> <!-- row.// -->
        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION INTRO END// ========================= -->


    <!-- ========================= SECTION FEATURES ========================= -->
    <section id="features" class="section-features bg2 padding-y-lg">
        <div class="container">

            <header class="section-heading text-center">
                <h2 class="title-section">Как это работает?</h2>
                <p class="lead">Все очень просто. Посмотрите на схему ниже</p>
            </header><!-- sect-heading -->

            <div class="row">
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary white"><i class="fa fa-envelope-open"></i></span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Индексация данных</h4>
                            <p>Необходим файл специального формата, его надо загрузить в форму на странице по ссылке "Обновить индекс" в верхнем меню</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary  white"><i class="fa fa-heart"></i></span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Фильтр и Поиск</h4>
                            <p>На базе REST API сервиса можно очень легко написать свой клиент. Сервис поддерживает зависимость фильтров и ранжирование поиска</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary  white"><i class="fa fa-users"></i> </span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Анализ данных</h4>
                            <p>Сервис фиксирует действия пользователя и полученные результаты по ним. Полученные данные влияют на его работу</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
            </div> <!-- row.// -->

            <p class="text-center">
                <br>
                <a href="{{ route('demo_catalog') }}" class="btn btn-warning">Начать!</a>
            </p>

        </div><!-- container // -->
    </section>
    <!-- ========================= SECTION FEATURES END// ========================= -->

    <!-- ========================= SECTION CONTENT  ========================= -->
    <section id="content" class="section-content padding-y-lg">
        <div class="container">

            <header class="section-heading text-center">
                <h2 class="title-section">Немного подробностей</h2>
                <p class="lead"> Elasticsearch / Sphinx / Lucene </p>
            </header><!-- sect-heading -->

            <div class="row justify-content-center">
                <article class="col-md-6 text-center">
                    <p>В основе сервиса лежит идея того, что разработав алгоритмы поиска и фильтрации, можно их переиспользовать в разных инструментах.
                        Основной инструмент на сегодняшний день - это Elasticsearch, но мы постоянно в поиске новых решений.
                        Работа с инструментом отделена от бизнес-логики проекта, поэтому легко можно подключать новые решения.</p>
                    <p>Качество и простота в разработке также очень важны. Сервис построен на docker-контецнерах, в которых уже есть тестовые наботы данных.
                        Также постоянно улучшаются инструменты для автоматического тестирования.</p>
                </article> <!-- col.// -->
            </div> <!-- row.// -->

        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION CONTENT  END// ========================= -->


    <!-- ========================= SECTION CONTENT ASIDE ========================= -->
    <section id="more" class="bg section-content padding-y-lg">
        <div class="container">

            <header class="section-heading text-center">
                <h2 class="title-section"> Планы на будущее</h2>
                <p class="lead"> Развитие - наше все </p>
            </header><!-- sect-heading -->

            <div class="row justify-content-center">
                <article class="col-md-6 text-center">
                    <p>
                        Планов очень много: собрать команду тех, кому хочется пробовать новое.
                        Постоянно улучшать продукт, внедрять в него новые возможности и технологии.
                        Важно, чтобы это был удобный продукт, решающий задачи бизнеса.
                        Но с другой стороны сервис всегда должен оставаться надежным и современным инструментом.
                    </p>
                </article> <!-- col.// -->
            </div> <!-- row.// -->

        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION CONTENT ASIDE END// ========================= -->
@endsection
