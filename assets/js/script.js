document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function (event) {
      if (!confirm('Delete this expense?')) {
        event.preventDefault();
      }
    });
  });
});
