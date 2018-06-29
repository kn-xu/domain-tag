<!doctype html>
<html lang="{{ app()->getLocale() }}" ng-app="domain">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Domain Tag</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Scripts -->
    <!-- Imported Assets via bower/npm -->
    <script src="/js/library.js"></script>

    <!-- Self-wrote JS code on Angular -->
    <script src="/js/custom.js"></script>
</head>
<body>
<div class="container main-container">
    <div class="jumbotron">
        <h1>Domain Tag</h1>
        <p>This application entails a list of domains and a description of said domain along with the flagged status of
            each domain retrieved from google's safe browsing api.
        </p>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div ui-view></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
