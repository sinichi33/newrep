// JavaScript Document

 $(document).ready(function () {

                //validation rules
                $("#form").validate({
                    //set this to false if you don't what to set focus on the first invalid input
                    focusInvalid: false,
                    //by default validation will run on input keyup and focusout
                    //set this to false to validate on submit only
                    onkeyup: false,
                    onfocusout: false,
                    //by default the error elements is a <label>
                    errorElement: "div",
                    //place all errors in a <div id="errors"> element
                    errorPlacement: function(error, element) {
                        error.appendTo("div#errors");
                    },
                    rules: {
                        "email": {
                            required: true,
                        },
                        "nip": {
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        "email": {
                            required: "Mohon masukan Email anda",
                        },
                        "nip": {
                            required: "Mohon masukan NIP anda",
                            number : "NIP hanya berisi karakter numerik"
                        }
                    }
                });

            });