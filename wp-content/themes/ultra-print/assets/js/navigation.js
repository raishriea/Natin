/**
 * Theme functions file.
 *
 * Contains handlers for navigation.
 */

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu-navigation > ul').superfish({
		delay:       500,
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'
   	});
});

function ultra_print_open() {
	window.ultra_print_mobileMenu=true;
	jQuery(".sidenav").addClass('show');
}
function ultra_print_close() {
	window.ultra_print_mobileMenu=false;
	jQuery(".sidenav").removeClass('show');
}

window.ultra_print_currentfocus=null;
ultra_print_checkfocusdElement();
var ultra_print_body = document.querySelector('body');
ultra_print_body.addEventListener('keyup', ultra_print_check_tab_press);
var ultra_print_gotoHome = false;
var ultra_print_gotoClose = false;
window.ultra_print_mobileMenu=false;
function ultra_print_checkfocusdElement(){
 	if(window.ultra_print_currentfocus=document.activeElement.className){
	 	window.ultra_print_currentfocus=document.activeElement.className;
 	}
}
function ultra_print_check_tab_press(e) {
	"use strict";
	// pick passed event or global event object if passed one is empty
	e = e || event;
	var activeElement;

	if(window.innerWidth < 999){
		if (e.keyCode == 9) {
			if(window.ultra_print_mobileMenu){
				if (!e.shiftKey) {
					if(ultra_print_gotoHome) {
						jQuery( ".main-menu-navigation ul:first li:first a:first-child" ).focus();
					}
				}
				if (jQuery("a.closebtn.responsive-menu").is(":focus")) {
					ultra_print_gotoHome = true;
				} else {
					ultra_print_gotoHome = false;
				}
			}else{
				if(window.ultra_print_currentfocus=="mobiletoggle"){
					jQuery( "" ).focus();
				}
			}
		}
	}
	if (e.shiftKey && e.keyCode == 9) {
		if(window.innerWidth < 999){
			if(window.ultra_print_currentfocus=="header-search"){
				jQuery(".mobiletoggle").focus();
			}else{
				if(window.ultra_print_mobileMenu){
					if(ultra_print_gotoClose){
						jQuery("a.closebtn.responsive-menu").focus();
					}
					if (jQuery( ".main-menu-navigation ul:first li:first a:first-child" ).is(":focus")) {
						ultra_print_gotoClose = true;
					} else {
						ultra_print_gotoClose = false;
					}
				
				}else{
					if(window.ultra_print_mobileMenu){
					}
				}
			}
		}
	}
 	ultra_print_checkfocusdElement();
}