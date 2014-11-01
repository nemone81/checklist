$( document ).ready(function() {
	
    $('.todo-list').on('click', 'a.update-todo', function (e) {
		e.preventDefault();
        var column = $(this).parent('li');
		var status_info = $(this).siblings('span');
        var ajax = $.post($(this).attr('href'));
        ajax.done(function() {
			var val = parseInt(status_info.data('status'));
			console.log((val == 0) ? 'Done' : 'To Do');
            status_info.html('<span class="todo-status">'+((val == 0) ? 'Done' : 'To Do')+'</span>');
			status_info.data('status', (1 - val));
        });

    });

	$('#todo-form').on('submit',function(e){
    	e.preventDefault();
    	$.ajax({
        	type     : "POST",
        	cache    : false,
        	url      : $(this).attr('action'),
        	data     : $(this).serialize(),
        	success  : function(data) {
				//console.log(data);					
				var $li = '<li><a class="update-todo" href="/todo/'+data.id+'">'+data.title+'</a><span class="todo-status todo" data-status="0">To Do</span></li>';
            	$('.todo-list').append($li);
				$('#todo-form')[0].reset();
        	}
    	});

	});
	
    /*$('.delete-link').click(function () {
        var row = $(this).parent('td[class="center"]').parent('tr');
        var ajax = $.post($(this).attr('href'));
        ajax.done(function() { row.remove(); });
        return false;
    });*/
});