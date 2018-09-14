$(function(){
    $('.checkall').on('click', function() {
        $('.child').prop('checked', this.checked)
    });
});
