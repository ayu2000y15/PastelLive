document.addEventListener("DOMContentLoaded", function () {
    const dataFormContainer = document.getElementById("dataForm");
    const newEntryBtn = document.getElementById("newEntryBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    //キャンセルボタンのイベントリスナー
    cancelBtn.addEventListener("click", function () {
        hideForm();
    });

    // 新規登録ボタンのイベントリスナー
    newEntryBtn.addEventListener("click", function () {
        showForm();
    });

    function showForm() {
        dataFormContainer.style.display = "block";
        dataFormContainer.scrollIntoView({ behavior: "smooth" });
    }

    function hideForm() {
        dataFormContainer.style.display = "none";
    }
});
