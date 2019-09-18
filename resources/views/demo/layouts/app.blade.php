<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="max-age=604800"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Bootstrap-ecommerce by Vosidiy">

    <title>UI KIT - Marketplace and Ecommerce html template</title>

    <link href="/frontend/images/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <!-- jQuery -->
    <script src="/frontend/js/jquery-2.0.0.min.js" type="text/javascript"></script>

    <!-- Bootstrap4 files-->
    <script src="/frontend/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <link href="/frontend/css/bootstrap.css" rel="stylesheet" type="text/css"/>

    <!-- Font awesome 5 -->
    <link href="/frontend/fonts/fontawesome/css/fontawesome-all.min.css" type="text/css" rel="stylesheet">

    <!-- custom style -->
    <link href="/frontend/css/ui.css" rel="stylesheet" type="text/css"/>
    <link href="/frontend/css/responsive.css" rel="stylesheet" media="only screen and (max-width: 1200px)"/>

    <!-- custom javascript -->
    <script src="/frontend/js/script.js" type="text/javascript"></script>

    <script type="text/javascript">
        /// some script

        // jquery ready start
        $(document).ready(function () {
            // jQuery code

        });
        // jquery end


    </script>

</head>
<body>
<header class="section-header">
    <nav class="navbar navbar-top navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/demo/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/demo/section/">Фильтрация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/demo/search/">Поиск</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/demo/update/">Обновить индекс</a>
                    </li>
                </ul>
            </div>
        </div> <!-- container //  -->
    </nav>
    <section class="header-main shadow">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-sm-4">
                    <div class="brand-wrap">
                        <h2 class="logo-text">Search Point</h2>
                    </div> <!-- brand-wrap.// -->
                </div>
                <div class="col-lg-9 col-sm-8">
                    <form action="#" class="search-wrap">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" style="width:55%;"
                                   placeholder="Введите поисковый запрос">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form> <!-- search-wrap .end// -->
                </div> <!-- col.// -->
            </div> <!-- row.// -->
        </div> <!-- container.// -->
    </section> <!-- header-main .// -->
</header> <!-- section-header.// -->


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
                            <li class="breadcrumb-item"><a href="#">Фильтрация</a></li>
                        </ol>
                    </nav> <!-- col.// -->
                </div> <!-- row.// -->
            </div> <!-- col.// -->
        </div> <!-- row.// -->


        <section class="section-pagetop">
            <h2 class="title-doc">Каталог продукции</h2>
        </section>

        <div class="row">
            <aside class="col-sm-3">

                <div class="card card-filter">
                    <article class="card-group-item">
                        <header class="card-header">
                            <a class="" aria-expanded="true" href="#" data-toggle="collapse" data-target="#collapse22">
                                <i class="icon-action fa fa-chevron-down"></i>
                                <h6 class="title">By Category</h6>
                            </a>
                        </header>
                        <div style="" class="filter-content collapse show" id="collapse22">
                            <div class="card-body">
                                <form class="pb-3">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search" type="text">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <ul class="list-unstyled list-lg">
                                    <li><a href="#">Cras justo odio <span
                                                    class="float-right badge badge-light round">142</span>
                                        </a></li>
                                    <li><a href="#">Dapibus ac facilisis <span
                                                    class="float-right badge badge-light round">3</span> </a></li>
                                    <li><a href="#">Morbi leo risus <span
                                                    class="float-right badge badge-light round">32</span> </a></li>
                                    <li><a href="#">Another item <span
                                                    class="float-right badge badge-light round">12</span> </a></li>
                                </ul>
                            </div> <!-- card-body.// -->
                        </div> <!-- collapse .// -->
                    </article> <!-- card-group-item.// -->
                    <article class="card-group-item">
                        <header class="card-header">
                            <a href="#" data-toggle="collapse" data-target="#collapse33">
                                <i class="icon-action fa fa-chevron-down"></i>
                                <h6 class="title">By Price </h6>
                            </a>
                        </header>
                        <div class="filter-content collapse show" id="collapse33">
                            <div class="card-body">
                                <input type="range" class="custom-range" min="0" max="100" name="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Min</label>
                                        <input class="form-control" placeholder="$0" type="number">
                                    </div>
                                    <div class="form-group text-right col-md-6">
                                        <label>Max</label>
                                        <input class="form-control" placeholder="$1,0000" type="number">
                                    </div>
                                </div> <!-- form-row.// -->
                                <button class="btn btn-block btn-outline-primary">Apply</button>
                            </div> <!-- card-body.// -->
                        </div> <!-- collapse .// -->
                    </article> <!-- card-group-item.// -->
                    <article class="card-group-item">
                        <header class="card-header">
                            <a href="#" data-toggle="collapse" data-target="#collapse44">
                                <i class="icon-action fa fa-chevron-down"></i>
                                <h6 class="title">By Feature </h6>
                            </a>
                        </header>
                        <div class="filter-content collapse show" id="collapse44">
                            <div class="card-body">
                                <form>
                                    <label class="form-check">
                                        <input class="form-check-input" value="" type="checkbox">
                                        <span class="form-check-label">
				  	<span class="float-right badge badge-light round">5</span>
				    Samsung
				  </span>
                                    </label>  <!-- form-check.// -->
                                    <label class="form-check">
                                        <input class="form-check-input" value="" type="checkbox">
                                        <span class="form-check-label">
				  	<span class="float-right badge badge-light round">13</span>
				    Mersedes Benz
				  </span>
                                    </label> <!-- form-check.// -->
                                    <label class="form-check">
                                        <input class="form-check-input" value="" type="checkbox">
                                        <span class="form-check-label">
				  	<span class="float-right badge badge-light round">12</span>
				    Nissan Altima
				  </span>
                                    </label>  <!-- form-check.// -->
                                    <label class="form-check">
                                        <input class="form-check-input" value="" type="checkbox">
                                        <span class="form-check-label">
				  	<span class="float-right badge badge-light round">32</span>
				    Another Brand
				  </span>
                                    </label>  <!-- form-check.// -->
                                </form>
                            </div> <!-- card-body.// -->
                        </div> <!-- collapse .// -->
                    </article> <!-- card-group-item.// -->
                </div> <!-- card.// -->


            </aside> <!-- col.// -->
            <main class="col-sm-9">


            </main> <!-- col.// -->
        </div>

    </div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->

<!-- ========================= FOOTER ========================= -->
<footer class="section-footer bg-secondary">
    <div class="container">
        <section class="footer-bottom row border-top-white">
            <div class="col-sm-6">
                <p class="text-white-50"> Made with <3 <br> by Vosidiy M.</p>
            </div>
            <div class="col-sm-6 text-right">
                <p class="text-sm-right text-white-50">
                    Copyright &copy 2018 <br>
                    <a href="http://bootstrap-ecommerce.com" class="text-white-50">Bootstrap-ecommerce UI kit</a>
                </p>
            </div>
        </section> <!-- //footer-top -->
    </div><!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->


</body>
</html>