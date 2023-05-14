
var modalOpenedId, modalCanCancel = true, modalCancelFunc;
var msgModalActive = false, msgModalButtons = 0, msgModalBtn1Func = null, msgModalBtn2Func = null;

function modalInit() {
    $('#modalBack').on('click', modalBackClick);
    modalMsgInit();
}

function modalMsgInit() {
    $('#msgModalBtn1').on('click', modalMsgBtn1Click);
    $('#msgModalBtn2').on('click', modalMsgBtn2Click);
    modalMsgReset();
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
        if (modalCancelFunc)
            modalCancelFunc();
        else modalClose();
    }
}

function modalMsgBtn1Click() {
    console.log(msgModalBtn1Func);
    if (msgModalBtn1Func)
        msgModalBtn1Func();
    modalClose();
    modalMsgReset();
}

function modalMsgBtn2Click() {
    if (msgModalBtn2Func)
        msgModalBtn2Func();
    modalClose();
    modalMsgReset();
}

function modalMsgReset() {
    modalCanCancel = true;
    msgModalActive = false;
    msgModalBtn1Func = null;
    msgModalBtn2Func = null;
    msgModalButtons = 0;
    $('#msgModalBtn1').show();
    $('#msgModalBtn1').removeClass().addClass('btn');
    $('#msgModalBtn2').show();
    $('#msgModalBtn2').removeClass().addClass('btn');
    $('#msgModalIcon').hide();
}

function modalMsg(modalTitle, modalText, btnText, btnFunc=null, btnColor=null, modalIcon=null, canCancel=true) {
    modalMsgReset();
    $('#msgModalBtn1').show();
    $('#msgModalBtn1').text(btnText);
    if (btnColor)
        $('#msgModalBtn1').addClass(btnColor);
    $('#msgModalBtn2').hide();
    $('#msgModalTitle').html(modalTitle);
    $('#msgModalText').html(modalText);
    if (modalIcon) {
        $('#msgModalIcon').show();
        $('#msgModalIcon').attr('icon', modalIcon);
    }
    msgModalButtons = 1;
    msgModalBtn1Func = btnFunc;
    modalCanCancel = canCancel;
    msgModalActive = true;
    modalOpen('msgModal');
}

function modalMsg2Btn(modalTitle, modalText, modalIcon=null, btn1Text, btn1Func=null, btn1Color=null, btn2Text, btn2Func=null, btn2Color=null, canCancel=false, cancelFunc=null) {
    msgModalButtons = 2;
    $('#msgModalBtn1').show();
    $('#msgModalBtn1').text(btn1Text);
    if (btn1Color)
        $('#msgModalBtn1').addClass(btn1Color);
    msgModalBtn1Func = btn1Func;
    $('#msgModalBtn2').show();
    $('#msgModalBtn2').text(btn2Text);
    if (btn2Color)
        $('#msgModalBtn2').addClass(btn2Color);
    msgModalBtn2Func = btn2Func;
    $('#msgModalTitle').html(modalTitle);
    $('#msgModalText').html(modalText);
    if (modalIcon) {
        $('#msgModalIcon').show();
        $('#msgModalIcon').attr('icon', modalIcon);
    }
    msgModalActive = true;
    modalCanCancel = canCancel;
    modalCancelFunc = cancelFunc;
    modalMsgInit();
    modalOpen('msgModal');
}