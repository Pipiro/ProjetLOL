// déplace le rectangle 1 avec un autre aléatoire

$('#imageChamp1').on('click', function(){

	do {
	var random = "#champ" + (Math.floor(Math.random() * 6))
	if (random == "#champ0")
		random = "#champ1"
	
    var $champ1 = $(random);
    var $champRandom = $('#champ1');

    var champ1Offset = $champ1.offset();
    var champRandomOffset = $champRandom.offset();   
    
    var t1 = champ1Offset.top;
    var l1 = champ1Offset.left;

    var trandom = champRandomOffset.top;
    var lrandom = champRandomOffset.left; 
	
	}
    while (l1 == lrandom)

    $(champ1).animate({
        'top': t1+'px',
        'left': l1+'px'
    }, 'slow');

    $(random).animate({
        'top': trandom+'px',
        'left': lrandom+'px'
    }, 'slow');


});

//au clic sur le bouton on va réorganiser

$('#buttonClasser').on('click', function(){
    var l1 = $('#champ1').offset().left;
    var l2 = $('#champ2').offset().left;
    var l3 = $('#champ3').offset().left;
    var l4 = $('#champ4').offset().left;
    var l5 = $('#champ5').offset().left;

    var indice = "2";
    //pour champ1
    while (l1 != 100 && indice < 6){
        reorder(indice);
        indice++;
        l1 = $('#champ1').offset().left;
    }
});

function reorder(indice){

        var l1 = $('#champ1').offset().left;
        var champToMove = ('#champ' + indice);
        var lnew = $(champToMove).offset().left;
        $(champ1).animate({
            'top': 20+'px',
            'left': lnew+'px'
        }, 'slow');

        $(champToMove).animate({
            'top': 20+'px',
            'left': l1+'px'
        }, 'slow');

        
        
}

