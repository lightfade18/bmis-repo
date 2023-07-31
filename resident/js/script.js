$(function(){
    'use strict';
    $(".single_post").slice(0, 3).show();
    $(".load").on("click", function(){
        $(".single_post:hidden").slice(0, 3).slideDown("slow");
        if($(".single_post:hidden").length == 0){
            $(".loadless:hidden").slice(0, 3).slideDown("slow");
            $(".load").hide()
        }
    });
    $(".loadless").on("click", function(){
        $(".single_post").hide()
        $(".single_post").slice(0, 3).show();
        $(".load:hidden").slice(0, 1).slideDown("slow");
        $(".loadless").hide()
    });

    
});
$(function(){
    'use strict';
    $(".ansingle_post").slice(0, 1).show();
    $(".anload").on("click", function(){
        $(".ansingle_post:hidden").slice(0, 1).slideDown("slow");
        if($(".ansingle_post:hidden").length == 0){
            $(".anloadless:hidden").slice(0, 1).slideDown("slow");
            $(".anload").hide()
        }
    });
    $(".anloadless").on("click", function(){
        $(".ansingle_post").hide()
        $(".ansingle_post").slice(0, 1).show();
        $(".anload:hidden").slice(0, 1).slideDown("slow");
        $(".anloadless").hide()
    });

    
});



