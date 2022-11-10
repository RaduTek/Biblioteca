

function sidebarToggle() {
    $('#navSidebar').toggleClass('mobile-show');
    $('#sidebarDim').toggleClass('mobile-show');
    $('#navSidebarToggleBtn .m-deschide').toggleClass('mobile-hide');
    $('#navSidebarToggleBtn .m-inchide').toggleClass('mobile-hide');
}

function sidebarClose() {
    $('#navSidebar').removeClass('mobile-show');
    $('#sidebarDim').removeClass('mobile-show');
    $('#navSidebarToggleBtn .m-deschide').removeClass('mobile-hide');
    $('#navSidebarToggleBtn .m-inchide').addClass('mobile-hide');
}

function searchBoxToggle() {
    $('#navTop').toggleClass('searchActive');
    $('#inputSearch').toggleClass('textbox');
}

function toastHide(toastId) {
    $('#' + toastId).removeClass('show');
    $('#' + toastId).remove();
}

function toastShow(text, duration=5000, icon=null) {
    var toastId = 'toastid_' + Math.floor(Math.random() * 1000 + 1);
    var toast = $('<div><div>').addClass('toast show');
    toast.attr('id', toastId)
    toast.append($('<div></div>').html(text).addClass('toastMsg'));
    $("#toastHost").append(toast);
    setTimeout(function() {
        toastHide(toastId)
    }, duration);
}

var modalOpenedId, modalCanCancel = true;
var msgModalActive = false, msgModalButtons = 0, msgModalBtn1Func, msgModalBtn2Func;

function modalInit() {
    $('#modalBack').on('click', modalBackClick);
    modalMsgInit();
}

function modalOpen(modalId) {
    modalOpenedId = modalId;
    $('#modalHost').addClass('open');
    $('#modalHost *').removeClass('open');
    modal = $('#' + modalId);
    modal.addClass('open');
    $('#' + modalId + ' .modal-foot *').eq(0).focus();
}

function modalClose() {
    $('#modalHost').removeClass('open');
    $('#modalHost *').removeClass('open');
}

function modalBackClick() {
    if (modalCanCancel == true) {
        if (msgModalButtons == 2 && msgModalBtn2Func)
            msgModalBtn2Click();
        else if (msgModalButtons == 1 && msgModalBtn1Func)
            msgModalBtn1Click();
        else modalClose();
    }
}

function modalMsgInit() {
    $('#msgModalBtn1').on('click', msgModalBtn1Click);
    $('#msgModalBtn2').on('click', msgModalBtn2Click);
    modalMsgReset();
}

function modalMsgReset() {
    modalCanCancel = true;
    msgModalActive = false;
    msgModalButtons = 0;
    $('#msgModalBtn1').show();
    $('#msgModalBtn1').removeClass().addClass('btn');
    $('#msgModalBtn2').show();
    $('#msgModalBtn2').removeClass().addClass('btn');
    $('#msgModalIcon').hide();
}

function modalMsg(modalTitle, modalText, btnText, confirmFunction=null, btnColor=null, modalIcon=null, _modalCanCancel=false) {
    msgModalButtons = 1;
    $('#msgModalBtn1').show();
    $('#msgModalBtn1').text(btnText);
    if (btnColor)
        $('#msgModalBtn1').addClass(btnColor);
    msgModalBtn1Func = confirmFunction;
    $('#msgModalBtn2').hide();
    $('#msgModalTitle').html(modalTitle);
    $('#msgModalText').html(modalText);
    if (modalIcon) {
        $('#msgModalIcon').show();
        $('#msgModalIcon').attr('icon', modalIcon);
    }
    msgModalActive = true;
    modalCanCancel = _modalCanCancel;
    modalOpen('msgModal');
}

function msgModalBtn1Click() {
    if (msgModalBtn1Func)
        msgModalBtn1Func();
    modalClose();
    modalMsgReset();
}

function msgModalBtn2Click() {
    if (msgModalBtn2Func)
        msgModalBtn2Func();
    modalClose();
    modalMsgReset();
}

function btnEventsInit() {
    $('#navSidebarToggleBtn').click(sidebarToggle);
    $('#sidebarDim').click(sidebarClose);

    $('#btnShowSearch').click(searchBoxToggle);
    $('#btnHideSearch').click(searchBoxToggle);
}

function bodyOnLoad() {
    modalInit();
    btnEventsInit();
}