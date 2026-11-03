// Bootstrap dropdown fix for admin navbar if Bootstrap JS is not loaded elsewhere
// This script ensures dropdowns work on all admin pages

document.addEventListener("DOMContentLoaded", function () {
  var dropdowns = document.querySelectorAll(".dropdown-toggle");
  dropdowns.forEach(function (dropdownToggleEl) {
    dropdownToggleEl.addEventListener("click", function (e) {
      if (typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
        var dropdown = bootstrap.Dropdown.getOrCreateInstance(dropdownToggleEl);
        dropdown.toggle();
      }
    });
  });
});
