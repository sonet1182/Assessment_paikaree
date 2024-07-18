import Swal from "sweetalert2";
$(function () {
    $(document).on("submit", "#ajax-form, .ajax-form", function (e) {
        e.preventDefault();
        if ($("form").hasClass("custom-validation"))
            $(".custom-validation").parsley().reset();
        let form = $(this);
        let method = form.attr("method");
        let button = form.find("button[type=submit]");
        let buttonText = 'Create New';
        button.text("Loading...");
        button.attr("disabled", true);
        let data = new FormData($(this)[0]);
        $.ajax({
            type: method,
            url: form.attr("action"),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (output) {
                button.html(buttonText);
                button.removeAttr("disabled");
                $('.modal').addClass("hidden");
                if (output.status) {
                    successMessage(output.message, output.button);
                } else {
                    errorMessage(output.message);
                }
                loadProducts();
            },
            error: function (response) {
                button.html(buttonText);
                button.removeAttr("disabled");
                errorMessage(getError(response));
                if (response.responseJSON.errors) {
                    $(".alert-danger").show();
                    $(".alert-danger ul").html("");
                    for (var error in response.responseJSON.errors) {
                        if ($("select[name=" + error + "]").length) {
                            $("select[name=" + error + "]").addClass(
                                "custom-invalid"
                            );

                            $("select[name=" + error + "]").after(
                                '<p class="help-block text-danger" style="color:red;">' +
                                    response.responseJSON.errors[error] +
                                    "</p>"
                            );
                        }

                        if (
                            $("input[name=" + error + "]")
                                .next()
                                .find(".help-block")
                        ) {
                            $("input[name=" + error + "]")
                                .next(".help-block")
                                .remove();
                        }
                        $("input[name=" + error + "]").addClass(
                            "custom-invalid"
                        );

                        $("input[name=" + error + "]").after(
                            '<p class="help-block text-danger" style="color:red;">' +
                                response.responseJSON.errors[error] +
                                "</p>"
                        );
                        $(".alert-danger ul").append(
                            "<li>" +
                                response.responseJSON.errors[error] +
                                "</li>"
                        );
                    }
                }
            },
        });
    });



    $(document).on("click", ".ajax-delete, #ajax-delete", function (e) {
        e.preventDefault();

        Swal.fire({
            icon: "warning",
            text: "Do you want to delete this?",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#e3342f",
        }).then((result) => {
            if (result.isConfirmed) {
                let self = $(this);
                let buttonText = self.html();
                self.html("wait...");
                $.ajax({
                    url: $(this).attr("href"),
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (output) {
                        self.html(buttonText);
                        if (output.status) {
                            successMessage(output.message, output.button);
                        } else {
                            errorMessage(output.message);
                        }
                        loadProducts();
                    },
                    error: function (response) {
                        self.html(buttonText);
                        errorMessage(getError(response));
                    },
                });
            }
        });
    });

    $(document).on("click", ".ajax-click-page, #ajax-click-page", function (e) {
        e.preventDefault();
        let self = $(this);
        let modal = $(".modal");
        let buttonText = self.html();
        console.log(buttonText);
        self.text("wait...");
        modal.removeClass("hidden");
        $.ajax({
            url: $(this).attr("href"),
            success: function (output) {
                self.html(buttonText);
                modal.find(".modal-dialog").html(output);
            },
            error: function (response) {
                self.html(buttonText);
                errorMessage(getError(response));
                modal.addClass('hidden');
            },
        });
    });

    $(document).on("click", ".close-modal", function (e) {
        $(".modal").addClass('hidden');
    });
});

function getError(response) {
    // console.log(response);
    let message = response.responseJSON.message;
    message += response.responseJSON.file
        ? " on " + response.responseJSON.file
        : "";
    message += response.responseJSON.line
        ? " on Line : " + response.responseJSON.line
        : "";
    return message;
}

function successMessage(message = null, button = false) {
    Swal.fire({
        icon: "success",
        html: message,
        showConfirmButton: button ? true : false,
        timer: button ? 100000 : 1000,
    });
}

function errorMessage(message = null) {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        html: message === null ? "Something went Wrong" : message,
    });
}
