$(document).ready(function(){
	width = $(window).width();
	if(width > 991){
		resizeMenu();
	}
	$(window).on("resize", function(){
		width = $(this).width();
		if(width > 991){
			resizeMenu();
		}		
	});
	
	function resizeMenu(){
		var menu = $('.top_menu');
		var menuHeight = menu.height();
		var targetHeight = $('.top_menu > li').height();
		var itemWidth = 0;
		var elem;
		var i = 0;
		
		if(menuHeight > targetHeight){
			if($(".dots").length == 0){
				menu.append($('<div>', {
					'class': 'dots',
					'text': "...",
				}));
			}
			if($(".hidden-menu-items").length == 0){
				$(".dots").append($('<div>', {
					'class': 'hidden-menu-items',
				}));
			}
		}
		
		while(menuHeight > targetHeight){
			itemWidth = menu.children("li:last-of-type").width();
			menu.children("li:last-of-type").attr("data-width", itemWidth);
			elem = menu.children("li:last-of-type");
			menu.children("li:last-of-type").remove();
			$(".hidden-menu-items").prepend(elem);
			menuHeight = menu.height();
			
		}
		
		var menuWidth = menu.width();
		var elemsWidth = 0;
		
		menu.children().each(function(){
			elemsWidth += $(this).width() + 20;
		});
		
		i = 0;
		var widthDif = menuWidth - elemsWidth;		
		while(widthDif > parseInt($(".hidden-menu-items").children("li:first-of-type").attr("data-width")) + 60){
			elem = $(".hidden-menu-items").children("li:first-of-type");
			$(".hidden-menu-items").children("li:first-of-type").remove();
			menu.children("li:last-of-type").after(elem);
			widthDif -= $(".hidden-menu-items").children("li:first-of-type").attr("data-width");
		}
		if($(".hidden-menu-items").children().length == 0){
			$(".dots").remove();
		}
	}
});
