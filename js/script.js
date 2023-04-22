function sidebarToggle() {
	$("#navSidebar").toggleClass("mobile-show");
	$("#sidebarDim").toggleClass("mobile-show");
	$("#navSidebarToggleBtn .m-deschide").toggleClass("mobile-hide");
	$("#navSidebarToggleBtn .m-inchide").toggleClass("mobile-hide");
}

function sidebarClose() {
	$("#navSidebar").removeClass("mobile-show");
	$("#sidebarDim").removeClass("mobile-show");
	$("#navSidebarToggleBtn .m-deschide").removeClass("mobile-hide");
	$("#navSidebarToggleBtn .m-inchide").addClass("mobile-hide");
}

function searchBoxToggle() {
	$("#navTop").toggleClass("searchActive");
	$("#inputSearch").toggleClass("textbox");
}

function toastHide(toastId) {
	$("#" + toastId).removeClass("show");
	setTimeout(function () {
		$("#" + toastId).remove();
	}, 200);
}

function toastShow(text, duration = 5000, icon = null) {
	var toastId = "toastid_" + Math.floor(Math.random() * 10000 + 1);
	var toast = $("<div><div>").addClass("toast");
	toast.attr("id", toastId);
	if (icon) {
		toast.append($("<iconify-icon></iconify-icon>").addClass("toastIcon").attr("icon", icon));
	}
	toast.append($("<div></div>").html(text).addClass("toastMsg"));
	$("#toastHost").prepend(toast);
	setTimeout(function () {
		$("#" + toastId).addClass("show");
	}, 200);
	setTimeout(function () {
		toastHide(toastId);
	}, duration);
	console.log(duration);
}

function btnEventsInit() {
	$("#navSidebarToggleBtn").click(sidebarToggle);
	$("#sidebarDim").click(sidebarClose);

	$("#btnShowSearch").click(searchBoxToggle);
	$("#btnHideSearch").click(searchBoxToggle);
}

function bodyOnLoad() {
	btnEventsInit();
}
