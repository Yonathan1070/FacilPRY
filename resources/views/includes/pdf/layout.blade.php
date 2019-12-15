<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>@yield('titulo', 'InkBrutalPRY') | {{session()->get('Rol_Nombre')}} | InkBrutalPRY</title>

  <style>
    table {
      border-spacing: 0;
      border-collapse: collapse;
    }

    td,
    th {
      padding: 0;
    }

    * {
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }

    *:before,
    *:after {
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }

    body {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 1.42857143;
      color: #333;
      background-color: #fff;
    }

    a {
      color: #337ab7;
      text-decoration: none;
    }


    h3,
    h4,
    .h3,
    .h4 {
      font-family: inherit;
      font-weight: 500;
      line-height: 1.1;
      color: inherit;
    }

    table {
      background-color: transparent;
    }

    th {
      text-align: left;
    }

    .table {
      width: 100%;
      max-width: 100%;
      margin-bottom: 20px;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }

    .table-hover>tbody>tr:hover {
      background-color: #f5f5f5;
    }

    .table-responsive {
      min-height: .01%;
      overflow-x: auto;
    }

    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
    }

    .alert-success {
      color: #3c763d;
      background-color: #dff0d8;
      border-color: #d6e9c6;
    }

    .alert-warning {
      color: #8a6d3b;
      background-color: #fcf8e3;
      border-color: #faebcc;
    }

    .panel {
      margin-bottom: 20px;
      background-color: #fff;
      border: 1px solid transparent;
      border-radius: 4px;
      -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
      box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .panel-body {
      padding: 15px;
    }

    .panel-title {
      margin-top: 0;
      margin-bottom: 0;
      font-size: 16px;
      color: inherit;
    }

    .panel-title>a,
    .panel-title>small,
    .panel-title>.small,
    .panel-title>small>a,
    .panel-title>.small>a {
      color: inherit;
    }

    .panel-group .panel {
      margin-bottom: 0;
      border-radius: 4px;
    }

    .panel-group .panel+.panel {
      margin-top: 5px;
    }

    .card {
      background: #fff;
      min-height: 50px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
      margin-bottom: 30px;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      -ms-border-radius: 2px;
      border-radius: 2px;
    }

    .card .header {
      color: #555;
      padding: 20px;
      position: relative;
      border-bottom: 1px solid rgba(204, 204, 204, 0.35);
    }

    .card .header .header-dropdown {
      position: absolute;
      top: 20px;
      right: 15px;
      list-style: none;
    }

    .card .header .header-dropdown .dropdown-menu li {
      display: block !important;
    }

    .card .header .header-dropdown li {
      display: inline-block;
    }

    .card .header .header-dropdown i {
      font-size: 20px;
      color: #999;
      -moz-transition: all 0.5s;
      -o-transition: all 0.5s;
      -webkit-transition: all 0.5s;
      transition: all 0.5s;
    }

    .card .header .header-dropdown i:hover {
      color: #000;
    }

    .card .header h2 {
      margin: 0;
      font-size: 18px;
      font-weight: normal;
      color: #111;
    }

    .card .header h2 small {
      display: block;
      font-size: 12px;
      margin-top: 5px;
      color: #999;
      line-height: 15px;
    }

    .card .header h2 small a {
      font-weight: bold;
      color: #777;
    }

    .card .header .col-xs-12 h2 {
      margin-top: 5px;
    }

    .card .body {
      font-size: 14px;
      color: #555;
      padding: 20px;
    }

    .card .body .col-xs-12,
    .card .body .col-sm-12,
    .card .body .col-md-12,
    .card .body .col-lg-12 {
      margin-bottom: 20px;
    }

    .panel-group .panel-col-red {
      border: 1px solid #F44336;
    }

    .panel-group .panel-col-red .panel-title {
      background-color: #F44336 !important;
      color: #fff;
    }

    .panel-group .panel-col-red .panel-body {
      border-top-color: transparent !important;
    }

    .panel-group .panel-col-cyan {
      border: 1px solid #00BCD4;
    }

    .panel-group .panel-col-cyan .panel-title {
      background-color: #00BCD4 !important;
      color: #fff;
    }

    .panel-group .panel-col-cyan .panel-body {
      border-top-color: transparent !important;
    }

    .panel-group .panel-col-orange {
      border: 1px solid #FF9800;
    }

    .panel-group .panel-col-orange .panel-title {
      background-color: #FF9800 !important;
      color: #fff;
    }

    .panel-group .panel-col-orange .panel-body {
      border-top-color: transparent !important;
    }

    .panel-group .panel {
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      -ms-border-radius: 0;
      border-radius: 0;
    }

    .panel-group .panel .panel-title .material-icons {
      float: left;
      line-height: 16px;
      margin-right: 8px;
    }

    .panel-group .panel .panel-heading {
      padding: 0;
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      -ms-border-radius: 0;
      border-radius: 0;
    }

    .panel-group .panel .panel-heading a {
      display: block;
      padding: 10px 15px;
    }

    .panel-group .panel .panel-heading a:hover,
    .panel-group .panel .panel-heading a:focus,
    .panel-group .panel .panel-heading a:active {
      text-decoration: none;
    }

    .panel-group .panel .panel-body {
      color: #555;
    }

    .table-bordered {
      border-top: 1px solid #eee;
    }

    .table-bordered tbody tr td,
    .table-bordered tbody tr th {
      padding: 10px;
      border: 1px solid #eee;
    }

    .table-bordered thead tr th {
      padding: 10px;
      border: 1px solid #eee;
    }

    .alert {
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      -ms-border-radius: 0;
      border-radius: 0;
      -webkit-box-shadow: none;
      -moz-box-shadow: none;
      -ms-box-shadow: none;
      box-shadow: none;
      border: none;
      color: #fff !important;
    }

    .alert-success {
      background-color: #2b982b;
    }

    .alert-warning {
      background-color: #ff9600 !important;
    }
  </style>



  @yield('styles')
</head>

<body class="theme-cyan">
  @yield('contenido')
</body>

</html>