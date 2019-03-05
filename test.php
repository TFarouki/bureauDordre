<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include 'includes/include_head.php';?>

    <title></title>
  </head>
  <body>
    <div class="container">


      <div class="text-right">
        <div class="form-group row">
          <div class="col-2">
            <input type="checkbox" class="toogle-switch" checked id="example-switch-input1" data-width="100" data-toggle="toggle" data-on="وارد" data-off="صادر" data-onstyle="success" data-offstyle="warning">
          </div>
          <div class="col-5">
            <input class="form-control" type="text" placeholder="اسم المرسل" id="example-text-input2">
          </div>
          <div class="col-5">
            <input class="form-control" type="text" placeholder="اسم المرسل اليه" id="example-search-input3">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-4">
            <input class="form-control" type="text" placeholder="نوعها" id="example-search-input4">
          </div>
          <div class="col-4">
            <input class="form-control" type="text" placeholder="موضوعها" id="example-search-input5">
          </div>
          <div class="col-4">
            <input class="form-control" type="text" placeholder="مرتبطة بملف" id="example-search-input6">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-4">
            <input class="form-control" type="text" placeholder="تاريخ الوصول" id="example-search-input7">
          </div>
          <div class="col-4">
            <input class="form-control" type="file" placeholder="تاريخ الوصول" id="example-search-input8">
          </div>
          <div class="form-group row">
            <div class="col-7">
              <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                  Dropdown button
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">Link 1</a>
                  <a class="dropdown-item" href="#">Link 2</a>
                  <a class="dropdown-item" href="#">Link 3</a>
                </div>
              </div>
            </div>
            <div class="col-1">
              <button class="btn btn-primary">إضافة</button>
            </div>
            <div class="col-4">
              <button class="btn btn-danger">الغاء</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </body>
</html>
