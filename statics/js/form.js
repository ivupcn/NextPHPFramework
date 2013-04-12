//validate form
$(document).ready(function() {
	$("form.required-validate").each(function(){
		var $form = $(this);
		$form.validate();
	});
});