function removeBlackhole(){
	var table = $("#grid")[0];
        for (row = 0; row < window.size; row++) {
            for (col = 0; col < window.size; col++) {
                var cell = table.rows[row].cells[col];
                if($(cell).hasClass('vortex')){
                	$(cell).empty();
                }
                
            }
        }        
}