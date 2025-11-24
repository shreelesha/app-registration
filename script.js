// Simple client-side validation using jQuery
$(function () {
  const showError = (fieldId, message) => {
    $(`small.error[data-for="${fieldId}"]`).text(message);
    $(`#${fieldId}`).attr("aria-invalid", !!message);
  };

  const clearErrors = () => {
    $("small.error").text("");
    $("[aria-invalid]").attr("aria-invalid", false);
  };

  const validators = {
    fullName: (val) => val.trim().length >= 3 || "Enter at least 3 characters.",
    email: (val) => /\S+@\S+\.\S+/.test(val) || "Enter a valid email.",
    phone: (val) => /^\d{10}$/.test(val) || "Enter a 10-digit number.",
    dob: (val) => !!val || "Select your date of birth.",
    gender: (val) => !!val || "Please select a gender.",
    program: (val) => !!val || "Please select a program.",
    address: (val) => val.trim().length >= 10 || "Add a more detailed address.",
    statement: (val) => val.trim().length >= 10 || "Write at least 10 characters."
  };

  $("#registrationForm").on("submit", function (e) {
    clearErrors();
    let hasError = false;

    // Validate inputs
    $(this)
      .find("input[type=text], input[type=email], input[type=tel], input[type=date], select, textarea")
      .each(function () {
        const id = $(this).attr("id");
        const val = $(this).val() || "";
        if (validators[id]) {
          const res = validators[id](val);
          if (res !== true) {
            showError(id, res);
            hasError = true;
          }
        }
      });

    if (hasError) {
      e.preventDefault(); // stop submission
      // Smooth scroll to first error
      const firstError = $(".error").filter(function () { return $(this).text().length > 0; }).first();
      if (firstError.length) {
        firstError.prev().get(0).scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });
});