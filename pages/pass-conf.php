<html>

<head>
  <meta charset="utf-8">
  <title>How To Validate Password And Confirm Password Using jQuery</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
        <form>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" required class="form-control" id="password" placeholder="Enter a password">
          </div>
          <div class="form-group">
            <label for="pwd">Confirm Password:</label>
            <input type="password" required class="form-control" id="confirm_password" placeholder="Enter a Confirm Password">
          </div>
          <div style="margin-top: 7px;" id="CheckPasswordMatch"></div>
          <br>

          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $("#confirm_password").on('keyup', function() {
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();
        if (password != confirmPassword)
          $("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
        else
          $("#CheckPasswordMatch").html("Password match !").css("color", "green");
      });
    });
  </script>
  <body>
</html>