
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>quicktech</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('public/assets/images/fav.svg')}}
    " type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
    display: flex;
    min-height: 100%;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: 'Open Sans', sans-serif;
  }
 .cardStyle {
    width: 500px;
    border-color: white;
    background: #fff;
    padding: 36px 0;
    border-radius: 4px;
    margin: 30px 0;
    box-shadow: 0px 0 2px 0 rgba(0,0,0,0.25);
  }
#signupLogo {
  max-height: 100px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.formTitle{
  font-weight: 600;
  margin-top: 20px;
  color: #2F2D3B;
  text-align: center;
}
.inputLabel {
  font-size: 12px;
  color: #555;
  margin-bottom: 6px;
  margin-top: 24px;
}
  .inputDiv {
    width: 70%;
    display: flex;
    flex-direction: column;
    margin: auto;
  }
input {
  height: 40px;
  font-size: 16px;
  border-radius: 4px;
  border: none;
  border: solid 1px #ccc;
  padding: 0 11px;
}
input:disabled {
  cursor: not-allowed;
  border: solid 1px #eee;
}
.buttonWrapper {
  margin-top: 40px;
}
  .submitButton {
    width: 70%;
    height: 40px;
    margin: auto;
    display: block;
    color: #fff;
    background-color: #065492;
    border-color: #065492;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
  }
.submitButton:disabled,
button[disabled] {
  border: 1px solid #cccccc;
  background-color: #cccccc;
  color: #666666;
}

#loader {
  position: absolute;
  z-index: 1;
  margin: -2px 0 0 10px;
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #666666;
  width: 14px;
  height: 14px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.error-message {
            color: red;
        }
.custom-error-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color:  #CC0000;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}
.alert-success{
     position: fixed;
    top: 20px;
    right: 20px;
    background-color:  #5cb85c;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}
.with-icon{ position: relative;}

 .with-icon input{ padding-right:45px}



 span.input-group-text {
  position: absolute;
  right: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 0;
  bottom: 0;
  background: transparent;
  border: 0;
}

</style>
</head>
<body>
@if(session('error'))
    <div id="error-message"  class="custom-error-message">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success" id="success-message">
        {{ session('success') }}
    </div>
@endif

<div class="mainDiv">

  <div class="cardStyle">
  @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif


  <form action="{{ route('admin.access.changePasswordProcess') }}" method="post" id="changePassword">
            @csrf
            <input type="hidden" name="email" value=" ">
        
            <img src="{{ asset('public/assets/images/door.jpeg')}}" id="signupLogo" />
            <h2 class="formTitle">Reset Password</h2>
            <div class="inputDiv">
                <label class="inputLabel" for="password">OTP</label>
                <input type="text" name="otp" class="form-control" id="otp"  maxlength="4"  placeholder="Enter OTP">
                @if ($errors->has('otp'))
                    <span class="error invalid-feedback">{{ $errors->first('otp') }}</span>
                @endif
            </div>
            <div class="inputDiv">
                <label class="inputLabel" for="password">New Password</label>
                <div class="with-icon">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password" value="" onkeydown="return /[a-z0-9]/i.test(event.key) && event.key !== ' '">
                <div class="input-group-append pwd-show-tgl" data-show="false">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="i-showPwd"></i>
                        <i class="fas fa-eye-slash" id="i-hidePwd" style="display: none;"></i>
                    </span>
                </div>
                </div>
                @if ($errors->has('password'))
                    <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="inputDiv">
                <label class="inputLabel" for="confirmPassword">Confirm Password</label>
                <div class="with-icon">
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Enter password" value="" onkeydown="return /[a-z0-9]/i.test(event.key) && event.key !== ' '">
                <div class="input-group-append cpwd-show-tgl" data-show="false">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="i-cshowPwd"></i>
                        <i class="fas fa-eye-slash" id="i-chidePwd" style="display: none;"></i>
                    </span>
                </div>
                </div>
                @if ($errors->has('password_confirmation'))

                    <span class="error invalid-feedback error-message">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
            <div class="buttonWrapper">
                <button type="submit" id="submitButton" class="submitButton pure-button pure-button-primary">
                    <span>Continue</span>
                </button>
            </div>
        </form>
  </div>
</div>

</body>
<script>
        setTimeout(function() {
            let successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 2000); // 5 seconds
    </script>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>
const inputField = document.getElementById('otp');

// Add an event listener for the 'input' event
inputField.addEventListener('input', function(event) {
    // Get the current value of the input field
    let value = event.target.value;

    // Replace all spaces with an empty string
    value = value.replace(/\s/g, '');

    // Update the input field with the new value
    event.target.value = value;
});



     window.onload = function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 2000); // 2 seconds
        }
    };

    $(document).ready(function(){
        // @if(session('warning'))
        //     $.notify("{{ session('warning') }}", "error");
        //     setTimeout(function() {
        //         $('.notifyjs-corner').empty(); // Remove error message after 5 seconds
        //     }, 5000);
        // @endif

        // @if(session('success'))
        //     $.notify("{{ session('success') }}", "success");
        //     setTimeout(function() {
        //         $('.notifyjs-corner').empty(); // Remove success message after 5 seconds
        //     }, 5000);
        // @endif

        // Function to remove notifications after 5 seconds
        // setTimeout(function() {
        //     $('.notifyjs-container').empty();
        // }, 5000);

        // Remove error message after 5 seconds
        setTimeout(function() {
            $('.custom-error-message').empty();
        }, 5000);
    });


</script>
<script>

$(function () {
        $("#changePassword").validate({
            rules: {
                otp: {
                    required: true,
                    digits: true,


                },
                password: {
                    required: true,
                    minlength: 6,

                },
                password_confirmation: {
                    // required: true,
                    // minlength: 6,

                    equalTo: "#password",
                }
            },
            messages: {
                otp: {
                    required: "Please enter   OTP",
                    digits: "Please enter only digits",


                },
                password: {
                    required: "Please enter password",
                    minlength: "Password must be at least 6 characters long",

                },
                password_confirmation: {
                    required: "Please enter your confirm password",
                    minlength: "Password must be at least 6 characters long",
                    equalTo: "Password must be same",

                }
            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback error-message");
                element.closest(".inputDiv").append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });

        $('.pwd-show-tgl').click(function() {
        if ($(this).data('show')) {
            $('#password').attr('type', 'password')
            $('#i-hidePwd').hide()
            $('#i-showPwd').show()
            $(this).data('show', false)
        } else {
            $('#password').attr('type', 'text')
            $('#i-showPwd').hide()
            $('#i-hidePwd').show()
            $(this).data('show', true)
        }
    })
    $('.cpwd-show-tgl').click(function() {
        if ($(this).data('show')) {
            $('#password_confirmation').attr('type', 'password')
            $('#i-chidePwd').hide()
            $('#i-cshowPwd').show()
            $(this).data('show', false)
        } else {
            $('#password_confirmation').attr('type', 'text')
            $('#i-cshowPwd').hide()
            $('#i-chidePwd').show()
            $(this).data('show', true)
        }
    })
    });

    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 3000);

</script>



</body>
</html>
