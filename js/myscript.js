function set_element(id, id_parent, text_question, text_answer) {
            $('#textarea_edit_question').text(text_question);
            $('#textarea_edit_answer').text(text_answer);
            $('#select_parent_edit').val(id_parent);
        }

        function menu_action() {
            $('#my-menu ul li').next().find('ul').hide();
            var slide_speed = 100;
            $('ul#my-menu').find('ul').each(function(index) {
                if ($(this).find('li').length) {
                    //
                } else {
                    $(this).remove(); // пустые li
                }
            });
            $('ul#my-menu').find('li').each(function(index) {
                if ($(this).next('ul').length) {
                    $(this).find('.span_question').after('<i class="fa fa-arrow-right" style="margin-left:10px"></i>');
                    $(this).addClass('cursor_pointer');
                } else {
                    //
                }
            });
            $('ul#my-menu ul').each(function(index) {
                $(this).prev().addClass('collapsible').click(function() {
                    var id_for_save = $(this).attr('id');
                    if ($(this).next().css('display') == 'none') {
                        //добавить стрелку вниз
                        // $(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down fa-lg');
                        // $(this).find('span').removeClass('widget-category-list_span').addClass('widget-category-list_span_large');
                        $(this).next().slideDown(slide_speed, function() {
                            $(this).prev().removeClass('collapsed').addClass('expanded');
                        });
                    } else {
                        //добавить стрелку вправо
                        //  $(this).find('i').removeClass('fa-chevron-down fa-lg').addClass('fa-chevron-right');
                        //  $(this).find('span').removeClass('widget-category-list_span_large');
                        //  $(this).find('span.span_2').addClass('widget-category-list_span');
                        $(this).next().slideUp(slide_speed, function() {
                            $(this).prev().removeClass('expanded').addClass('collapsed');
                            $(this).find('ul').each(function() {
                                $(this).hide().prev().removeClass('expanded').addClass('collapsed');
                            });
                        });
                    }
                    return true;
                });
            });

            $('ul#my-menu ul ul li').each(function(index) {
                $(this).addClass('collapsible');
            });
        }
        $(document).ready(function() {
            var value_public = '';
            menu_action();
            document.querySelector('.ul_models').addEventListener('click', function(e) {
                value_public = $("#search_input").val().toLowerCase();
                var selected;
                if (e.target.tagName === 'LI') {
                    selected = document.querySelector('li.selected');
                    if (selected) selected.className = '';
                    e.target.className = 'selected';
                }
                ajax_situations(value_public);
                ajax_search(value_public);
                ajax_associations(value_public);
                ajax_synonyms(value_public);
            });
        });
        