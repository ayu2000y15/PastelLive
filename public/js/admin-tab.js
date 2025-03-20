document.addEventListener("DOMContentLoaded", function () {
    // タブ切り替えの処理
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const tabId = button.getAttribute("data-tab");

            tabButtons.forEach((btn) => btn.classList.remove("active"));
            tabContents.forEach((content) =>
                content.classList.remove("active")
            );

            button.classList.add("active");
            document.getElementById(`tab-${tabId}`).classList.add("active");
        });
    });

    // 初期表示時に最初のタブをアクティブにする
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }
});
