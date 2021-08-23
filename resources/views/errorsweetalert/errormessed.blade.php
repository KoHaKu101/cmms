<script>
var has_errors = {{ $errors->count() > 0 ?'true' : 'false' }} ;
if (has_errors) {
  Swal.fire({
    title: 'Error',
    icon: 'error',
    html: jQuery('#Errorsystem').html(),
    showCloseButton: true,
  });
}
</script>
