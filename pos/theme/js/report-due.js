$("#view,#view_all").click(function(){
    var from_date=document.getElementById("from_date").value.trim();
    var to_date=document.getElementById("to_date").value.trim();
    var view_all='no';
  	if(from_date == ""){
        toastr["warning"]("Select From Date!");
        document.getElementById("from_date").focus();
        return;
    }
  	 
  	if(to_date == ""){
        toastr["warning"]("Select To Date!");
        document.getElementById("to_date").focus();
        return;
    }
    
    if(this.id=="view_all")
        view_all='yes';
        

    $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    
});

$(window).on("load", function() {
    $.post("show_due_report", {}, function(result) {
        setTimeout(function() {
            $("#tbodyid").empty().append(result);     
            $(".overlay").remove();
        }, 0);
    });
});
