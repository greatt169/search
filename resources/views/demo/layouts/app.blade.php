<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="max-age=604800"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>UI KIT - Marketplace and Ecommerce html template</title>
    <link href="/frontend/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="/frontend/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <!-- Font awesome 5 -->
    <link href="/frontend/fonts/fontawesome/css/fontawesome-all.min.css" type="text/css" rel="stylesheet">
    <!-- custom style -->
    <link href="/frontend/css/ui.css" rel="stylesheet" type="text/css"/>
    <link href="/frontend/css/responsive.css" rel="stylesheet" media="only screen and (max-width: 1200px)"/>

    <script src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
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

@yield('content')

<!--
<div id="root"></div>
<script type="text/babel">

    /*class Hello extends React.Component {

        constructor(){
            super();
            this.state = {
                message: "my friend (from state)!"
            };
            this.updateMessage = this.updateMessage.bind(this);
        }

        updateMessage() {
            this.setState({
                message: "my friend (from changed state)!"
            });
        }

        render() {
            return (
                <div>
                    <h1>Hello {this.state.message}!</h1>
                    <button onClick={this.updateMessage}>Click me!</button>
                </div>
            )
        }
    }

    ReactDOM.render(
        <Hello message="my friend" />,
        document.getElementById("root")
    );*/

</script>
-->
<!-- ========================= FOOTER ========================= -->
<footer class="section-footer bg-secondary">
    <div class="container">
        <section class="footer-bottom row border-top-white">
            <div class="col-sm-6">
                <p class="text-white-50"> Дизайн и разработка<br> A E R O</p>
            </div>
            <div class="col-sm-6 text-right">
                <p class="text-sm-right text-white-50">
                    Все права защищены &copy @php echo date('Y')@endphp <br>
                </p>
            </div>
        </section> <!-- //footer-top -->
    </div><!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->
</body>
</html>