
function msgModal(msgTitle=null, msgText, btnText, btnFunc, btnColor, cancelFunc=null, canCancel=true, cancelBtn=true, cancelBtnText="Cancel") {
    var modalId = "modal_" + (Math.random() * 1000) + 100;
    var modal = $("<div></div>").addClass('modal');
    modal.attr('id', modalId);
    if (msgTitle) {
        var modalTitle = $("<div></div>").addClass('modalTitle');
        modalTitle.append($("<div></div>").innerText(msgTitle));
        modal.append(modalTitle);
    }
    var modalContent = $("<div></div>").addClass('modalContent');
    modalContent.append($("<div></div>").innerHTML(msgText));
    modal.append(modalContent);
    $("#modalHost").append(modal);
}
