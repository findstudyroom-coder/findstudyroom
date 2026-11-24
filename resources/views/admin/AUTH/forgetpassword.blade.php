<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- <link rel="icon" href="/assets/images/fav.svg" type="image/x-icon"> -->
        <link rel="icon" href="{{ asset('public/assets/images/fav.svg')}}" type="image/x-icon">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


        <title>Quicktech</title>
        <style>
                body{
                    margin-top:20px;
                    background-color: #f2f3f8;
                }
                .card {
                    margin-bottom: 1.5rem;
                    box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);
                }
                .card {
                    position: relative;
                    display: -ms-flexbox;
                    display: flex;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    min-width: 0;
                    word-wrap: break-word;
                    background-color: #fff;
                    background-clip: border-box;
                    border: 1px solid #e5e9f2;
                    border-radius: .2rem;
                }

    /* Custom CSS for arrow icon */
    .arrow-icon {
        font-size: 12px; /* Adjust the size as needed */
        margin-right: 5px; /* Add some space between the icon and text */
    }
    .custom-error-message {
    position: fixed;
     /* Adjust as needed */
    right: 20px; /* Adjust as needed */
    background-color:  #CC0000;/* Error message background color */
    color: #fff; /* Error message text color */
    padding: 10px 30px; /* Adjust padding as needed */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
     /* Ensure the message appears on top of other elements */
}

        </style>
    </head>
    <body>


@if(session('error'))
    <div id="error-message" class="custom-error-message">
        {{ session('error') }}
    </div>
@endif


    <div class="container h-100">
    		<div class="row h-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<!-- <h1 class="h2">Forget password</h1> -->
                            <img src="{{ asset('public/assets/images/door.jpeg')}}" alt="homepage" class="dark-logo" height="50px" width="50px" />
                            <h1 class="h2">Forget password</h1>
							<p class="lead">
								Enter your email to reset your password.
							</p>
						</div>

						<div class="card">
							<div class="card-body">
                            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                         @endif

								<div class="m-sm-4">
                                <form method="POST" action="{{ route('admin.access.forgetPasswordProcess') }}" id="form">
                                         @csrf

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" placeholder="please enter email" maxlength="61">

                                                    @error('email')
                                                    <span class="error invalid-feedback error-message">{{ $message }}</span>
                                                        @enderror
                                            </div>

                                        <div class="text-center">  <!-- Center the content -->
                                            <button type="submit" class="btn btn-lg btn-primary">Submit</button>

                                        </div>

									</form>
                                    <div class="text-center mt-3"> <!-- Center the content -->

                                            <a href="{{ route('admin.access.login') }}" class="btn btn-link"><i class="fa fa-arrow-left arrow-icon"></i>Back to login</a>
                                        </div>
								</div>

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

                <!-- Optional JavaScript -->
                <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>


$(function () {
        $("#form").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    maxlength:60


                },


            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address",
                    maxlength: "Email should not exceed 60 characters"
                 //   digits: "Please enter only digits",

                },


            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback error-message");
                element.closest(".form-group").append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>

<script>
      $(document).ready(function(){
        @if(session('warning'))
            $.notify("{{ session('warning') }}", "error");
            setTimeout(function() {
                $('.notifyjs-corner').empty(); // Remove error message after 5 seconds
            }, 5000);
        @endif

        @if(session('success'))
            $.notify("{{ session('success') }}", "success");
            setTimeout(function() {
                $('.notifyjs-corner').empty(); // Remove success message after 5 seconds
            }, 5000);
        @endif

        // Function to remove custom error message after 2 seconds
        setTimeout(function() {
            $('.custom-error-message').empty();
        }, 2000);

        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 2000); // 2 seconds
        }
    });


    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 2000);


</script>

            </div>
    </body>
</html>
