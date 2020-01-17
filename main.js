(function($){

	$('.addPanier').click(function(event){
		event.preventDefault();
		$.get($(this).attr('href'),{},function(data){
			if (data.error) {
				alert(data.message);
			}else{
				if (confirm(data.message +'\n \n"OK" POUR VOIR LE CONTENU DE VOTRE PANIER .')) {
					location.href = 'panier.php';
				}else{
					$('#totaljs').empty().append(data.totaljs);
					$('#countjs').empty().append(data.countjs);
				}
			}
		},'json');
		return false;
	});

})(jQuery);