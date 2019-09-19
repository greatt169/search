@extends('demo.layouts.app')

@section('content')
    <!-- ========================= SECTION INTRO ========================= -->
    <section id="intro" class="section-intro bg-secondary pt-5">
        <div class="container">
            <div class="row d-flex" style="min-height:600px;">
                <div class="col-sm-6 d-flex align-items-center">
                    <header class="intro-wrap text-white">
                        <h2 class="display-3"> Amazing place for  hero title </h2>
                        <p class="lead">Bootstrap ecommerce is more then template - also framework. <br> It is modern and fully customizable websites, WebApp and Mobile template for Your Project</p>
                        <a href="#" class="btn btn-warning">Download</a>
                        <a href="#" class="btn btn-light">Learn more</a>
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
                <h2 class="title-section">How it works </h2>
                <p class="lead"> Good sub heading this sounded nonsense to Alice </p>
            </header><!-- sect-heading -->

            <div class="row">
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary white"><i class="fa fa-envelope-open"></i></span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Sync across all devices</h4>
                            <p>This sounded nonsense to Alice, so she said nothing, but set off at once toward the Red Queen. To her surprise.</p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary  white"><i class="fa fa-heart"></i></span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Easy to Customize</h4>
                            <p>This sounded nonsense to Alice, so she said nothing, but set off at once toward the Red Queen. To her surprise. </p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
                <aside class="col-sm-4">
                    <figure class="itembox text-center">
                        <span class="icon-wrap icon-lg bg-secondary  white"><i class="fa fa-users"></i> </span>
                        <figcaption class="text-wrap">
                            <h4 class="title">Unique Interface Design</h4>
                            <p>This sounded nonsense to Alice, so she said nothing, but set off at once toward the Red Queen. To her surprise. </p>
                        </figcaption>
                    </figure> <!-- iconbox // -->
                </aside> <!-- col.// -->
            </div> <!-- row.// -->

            <p class="text-center">
                <br>
                <a href="#" class="btn btn-warning">Some action button</a>
            </p>

        </div><!-- container // -->
    </section>
    <!-- ========================= SECTION FEATURES END// ========================= -->

    <!-- ========================= SECTION CONTENT  ========================= -->
    <section id="content" class="section-content padding-y-lg">
        <div class="container">

            <header class="section-heading text-center">
                <h2 class="title-section">Section name</h2>
                <p class="lead"> Good sub heading this sounded nonsense to Alice </p>
            </header><!-- sect-heading -->

            <div class="row justify-content-center">
                <article class="col-md-6 text-center">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    <p>Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </article> <!-- col.// -->
            </div> <!-- row.// -->

        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION CONTENT  END// ========================= -->


    <!-- ========================= SECTION CONTENT ASIDE ========================= -->
    <section id="more" class="bg section-content padding-y-lg">
        <div class="container">

            <header class="section-heading text-center">
                <h2 class="title-section"> Another Section</h2>
                <p class="lead"> Good sub heading this sounded nonsense to Alice </p>
            </header><!-- sect-heading -->

            <div class="row justify-content-center">
                <article class="col-md-6 text-center">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    <p>Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </article> <!-- col.// -->
            </div> <!-- row.// -->

        </div> <!-- container .//  -->
    </section>
    <!-- ========================= SECTION CONTENT ASIDE END// ========================= -->
@endsection
