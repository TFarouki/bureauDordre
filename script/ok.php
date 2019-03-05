
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include '../includes/include_head.php';?>
    <title></title>
  </head>
  <body id="LoginForm">
    <div class="" style="min-height:600px;">
    </div>
    <p>taoufik farouki</p>
    <script type=text/javascript>
  $.ajax({
    type: "POST",
    data: {
      invoiceno:jobid
    },
    url: "animal/getName",
    beforeSend: function() {
    },
    dataType: "html",
    async: false,
    success: function(data) {
      result=data;
    }
  });
</script>
  </body>
</html>
