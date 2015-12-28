/*
Name: 			View - Home
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		2.1.0
*/

(function($) {

	'use strict';

	if($(".main-carousel").get(0)) {

		var i = 0,
			max = 0,
			min = 0,
			maxScale = 1,
			minScale = 0,
			blurEnabled = true,
			mainCarouselEl = $(".main-carousel"),
			homeTop = $(".home-top"),
			overlayTimeout = false,
			slideSpeed = 600,
			touchEnable = 'ontouchstart' in window || navigator.msMaxTouchPoints,
			owlCarouselEl = mainCarouselEl.find(".owl-carousel");

		// Random Positions
		owlCarouselEl.find("> div").each(function() {

			if (i%2 == 0) {
				max = 120;
				min = 50;
				minScale = 0.9;
			} else {
				max = -50;
				min = -120;
				minScale = 0.55;
			}

			var top = Math.random() * (max - min) + min;
			var scale = (Math.random() * (maxScale - minScale) + minScale).toFixed(2);

			$(this).css({
				"top": top,
				'-moz-transform': 'scale(' + scale + ')',
				'-webkit-transform': 'scale(' + scale + ')',
				'transform': 'scale(' + scale + ')',
			}).find("a").attr("data-scale", scale);

			i++;
		});

		// Nav
		mainCarouselEl.prepend(
			$("<a />")
				.addClass("carousel-nav prev")
				.attr({
					"href": "#",
					"id": "mainCarouselPrev"
				})
				.append(
					$("<i />")
						.addClass("fa fa-chevron-left")
				)
		);

		mainCarouselEl.prepend(
			$("<a />")
				.addClass("carousel-nav next")
				.attr({
					"href": "#",
					"id": "mainCarouselNext"
				})
				.append(
					$("<i />")
						.addClass("fa fa-chevron-right")
				)
		);

		// Nav Overlays
		mainCarouselEl.prepend(
			$("<div />")
				.addClass("carousel-nav-overlay prev")
		);

		mainCarouselEl.prepend(
			$("<div />")
				.addClass("carousel-nav-overlay next")
		);

		// Clone
		mainCarouselEl.prepend(
			$("<div />")
				.attr("id", "mainCarouselClone")
				.addClass("carousel-clone")
		);

		var mainCarouselClone = $("#mainCarouselClone");

		owlCarouselEl.clone(false).prependTo( "#mainCarouselClone" );

		// Slider Init
		var slider = owlCarouselEl.owlCarousel({
			items: 6,
			slideSpeed : slideSpeed,
			navigation: true,
			mouseDrag: false,
			rewindSpeed: 2000,
			afterInit: function(slider) {
				slider.addClass("owl-carousel-init");
			},
			beforeMove: function(slider) {
				$("#mainCarouselTooltip").stop().animate({
					top: "+=30",
					opacity: 0
				}, 200);
			},
			startDragging: function(slider) {
				$("#mainCarouselTooltip").stop().animate({
					top: "+=30",
					opacity: 0
				}, 200);
			}
		});

		// Overlay
		owlCarouselEl.after(
			$("<div />")
				.attr("id", "mainCarouselOverlay")
				.addClass("carousel-overlay")
		);

		mainCarouselEl.prepend(
			$("<div />")
				.addClass("carousel-clone-overlay")
		);

		// Blur Start
		if(!blurEnabled) {
			mainCarouselEl.css("visibility", "visible");
			Home.revolutionSlider();
			return false;
		}

		// Init Clone Slider
		var cloneOwlCarouselEl = mainCarouselClone.find(".owl-carousel");

		var cloneSlider = cloneOwlCarouselEl.owlCarousel({
			items: 6,
			slideSpeed : slideSpeed,
			navigation: true,
			mouseDrag: false,
			rewindSpeed: 2000
		});

		var cloneSliderInstance = cloneOwlCarouselEl.data().owlCarousel;

		cloneSliderInstance.jumpTo(cloneSliderInstance.$owlItems.length);

		cloneSliderInstance.$owlItems.slice(0,6).clone(false).prependTo( "#mainCarouselClone .owl-wrapper" );

		setTimeout(function() {
			cloneOwlCarouselEl.addClass("owl-carousel-init");
		}, slideSpeed);

		// Custom Navigation Events
		$("#mainCarouselNext").click(function(e) {
			e.preventDefault();
			slider.trigger("owl.next");
			cloneSlider.trigger("owl.prev");
		});

		$("#mainCarouselPrev").click(function(e) {
			e.preventDefault();
			slider.trigger("owl.prev");
			cloneSlider.trigger("owl.next");
		});

		// Blur Images
		mainCarouselEl.css("visibility", "visible");

		// Rotate Clone Elements
		mainCarouselEl.find(".flex-next").on("click", function() {
			cloneOwlCarouselEl.OwlCarousel("prev");
		});

		mainCarouselEl.find(".flex-prev").on("click", function() {
			cloneOwlCarouselEl.OwlCarousel("next");
		});

		// Tooltip
		$("body").append(
			$("<div />")
				.attr("id", "mainCarouselTooltip")
				.addClass("main-carousel-tooltip")
		);

		var mainCarouselTooltip = $("#mainCarouselTooltip");

		var carouselElements = mainCarouselEl.find(".thumb-info");

		carouselElements.append($("<span />").addClass("thumb-info-touch-hover"));

		carouselElements.hover(function() {

			var $el = $(this),
				top = $el.offset().top,
				left = $el.offset().left,
				title = $el.attr("data-tooltip"),
				scale = $el.attr("data-scale");

			if(left < 10 || (left + mainCarouselTooltip.width()) > $(window).width()) {
				return;
			}

			mainCarouselTooltip.stop().html(title).css({
				opacity: 0,
				top: (top-$(mainCarouselTooltip).height()),
				left: (($(this).outerWidth()*scale) - mainCarouselTooltip.outerWidth())  / 2 + left + 'px'
			}).animate({
				top: "-=30",
				opacity: 1
			}, 200);

			$(this).find(".thumb-info-action-icon-center").addClass("appear-animation bounceIn");

			setTimeout(function() {
				$el.addClass("hover");
			}, 500);

		}, function() {

			mainCarouselTooltip.stop().animate({
				top: "+=30",
				opacity: 0
			}, 200);

			$(this).find(".thumb-info-action-icon-center").removeClass("appear-animation bounceIn");

			$(this).removeClass("hover");

		});

		if(touchEnable) {
			carouselElements.click(function(e) {
				e.preventDefault;
				return false;
			});

			carouselElements.find(".thumb-info-touch-hover").click(function(e) {
				self.location = $(this).parent().attr("href");
			});
		}

	}

}).apply(this, [jQuery]);