$(document).ready(function(){
    
       $(document).on('click','.change_categories',function(){
           $(this).parent().hide();
           $(this).parent().parent().find('.categories_checkbox').show();
       });
       $(document).on('click','.hide_categories',function(){
           $(this).parent().hide();
           $(this).parent().parent().find('.categories_list').show();
       });
       
    
//    $(document).on('click','.add-new-cross',add_new_cross);
//    
//    $('.crosscat-autocomplete').autocomplete(autocomplete_options);
});

//function add_new_cross() {
//    var cat_id = $(this).attr('rel');
//    var num = $(this).parent().find('.new-cross').length + 1;
//    var data = {
//        cat_id : cat_id,
//        num : num,
//    }
//    $('#add_cross').tmpl(data).insertBefore($(this));
//}
//
//var autocomplete_options = {
//    source: '?action=autocomplete',
//    minLength: 3,
//    delay: 300,
//    select: function(event, ui) {
//        console.log()
////        var menu_item_id = $(this).attr('rel');
////        $('#item_' + menu_item_id ).find('.field-url input').val(ui.item.id);
////        $(this).val(ui.item.value);
//        return false;
//    }
//};