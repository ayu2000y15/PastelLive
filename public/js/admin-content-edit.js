document.addEventListener("DOMContentLoaded", () => {
    // ファイルアップロード機能の初期化
    initFileUpload();

    // スキーマページネーション
    initSchemaPagination();
});

// スキーマページネーション機能
function initSchemaPagination() {
    const pageBtns = document.querySelectorAll(".schema-page-btn");
    if (!pageBtns.length) return;

    pageBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            const pageNum = this.dataset.page;
            const pages = document.querySelectorAll(".schema-page");

            // すべてのページを非表示
            pages.forEach((page) => {
                page.style.display = "none";
            });

            // 選択したページを表示
            document.getElementById(`schema-page-${pageNum}`).style.display =
                "block";

            // ボタンのアクティブ状態を更新
            pageBtns.forEach((b) => {
                b.classList.remove("active");
            });
            this.classList.add("active");
        });
    });

    // 最初のページを選択状態に
    pageBtns[0].classList.add("active");
}

// ファイルアップロード処理（最適化版）
function initFileUpload() {
    const fileInputs = document.querySelectorAll(".file-upload-input");
    if (!fileInputs.length) return;

    fileInputs.forEach((input) => {
        const fieldName = input.id;
        const uploadArea = document.getElementById("upload-area-" + fieldName);
        const previewContainer = document.getElementById(
            "preview-" + fieldName
        );
        const isMultiple = input.hasAttribute("multiple");

        // required属性がある場合、データ属性に保存
        if (input.hasAttribute("required")) {
            input.setAttribute("data-required", "true");
        }

        // アップロードエリアをクリックしたらファイル選択ダイアログを開く
        if (uploadArea) {
            uploadArea.addEventListener("click", (e) => {
                e.stopPropagation();
                input.click();
            });

            // ドラッグ&ドロップイベント
            uploadArea.addEventListener("dragenter", preventDefaults, false);
            uploadArea.addEventListener("dragover", preventDefaults, false);
            uploadArea.addEventListener("dragleave", preventDefaults, false);
            uploadArea.addEventListener("drop", handleDrop, false);

            function handleDrop(e) {
                preventDefaults(e);
                uploadArea.classList.remove("drag-over");

                const dt = e.dataTransfer;
                const files = dt.files;

                if (isMultiple) {
                    input.files = dt.files;
                    handleFiles(files);
                } else if (files.length > 0) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    input.files = dataTransfer.files;
                    handleFiles([files[0]]);
                }
            }
        }

        // ファイル選択処理
        input.addEventListener("change", function () {
            if (!this.files.length) return;

            if (isMultiple) {
                handleFiles(this.files);
            } else {
                handleFiles([this.files[0]]);
            }

            // ファイルが選択されたらrequired属性を削除
            if (this.hasAttribute("data-required")) {
                this.removeAttribute("required");
            }
        });

        function handleFiles(files) {
            if (!files.length) return;

            // メモリ使用量を削減するため、一度に処理するファイル数を制限
            const maxFilesToProcess = isMultiple
                ? Math.min(files.length, 3)
                : 1;

            if (!isMultiple) {
                // 単一ファイルの場合は新しいプレビューをクリア
                const newPreviews = previewContainer.querySelectorAll(
                    ".file-preview-item.new-file"
                );
                newPreviews.forEach((preview) => preview.remove());
            }

            // ファイルを順次処理
            for (let i = 0; i < maxFilesToProcess; i++) {
                const file = files[i];
                if (!file.type.match("image.*")) continue;

                // ファイルサイズの制限（5MB以上の場合は圧縮処理）
                if (file.size > 5 * 1024 * 1024) {
                    compressImage(file, function (compressedFile) {
                        createPreviewElement(compressedFile);
                    });
                } else {
                    createPreviewElement(file);
                }
            }

            // 残りのファイルがある場合は通知
            if (files.length > maxFilesToProcess) {
                alert(
                    `一度に${maxFilesToProcess}個までのファイルを処理します。残りは選択し直してください。`
                );
            }
        }

        function createPreviewElement(file) {
            // DOMフラグメントを使用
            const fragment = document.createDocumentFragment();
            const preview = document.createElement("div");
            preview.className = "file-preview-item new-file";
            preview.dataset.filename = file.name;

            // 画像のプレビューはURLオブジェクトを使用して最適化
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file); // FileReaderの代わりにURLオブジェクトを使用
            img.className = "file-preview-image";
            img.onload = function () {
                // 画像が読み込まれた後にURLを解放
                URL.revokeObjectURL(this.src);
            };

            const info = document.createElement("div");
            info.className = "file-preview-info";
            info.textContent = file.name;

            const size = document.createElement("div");
            size.className = "file-preview-size";
            size.textContent = formatFileSize(file.size);

            const removeBtn = document.createElement("button");
            removeBtn.className = "btn btn-sm btn-danger file-preview-remove";
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.type = "button";

            // イベントリスナーを追加
            removeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                preview.remove();

                if (!isMultiple) {
                    input.value = "";
                    if (input.hasAttribute("data-required")) {
                        input.setAttribute("required", "");
                    }
                } else {
                    const fileName = preview.dataset.filename;
                    const dt = new DataTransfer();
                    for (let i = 0; i < input.files.length; i++) {
                        const file = input.files[i];
                        if (file.name !== fileName) {
                            dt.items.add(file);
                        }
                    }
                    input.files = dt.files;
                    if (
                        input.files.length === 0 &&
                        input.hasAttribute("data-required")
                    ) {
                        input.setAttribute("required", "");
                    }
                }
            });

            // 要素を追加
            preview.appendChild(img);
            preview.appendChild(info);
            preview.appendChild(size);
            preview.appendChild(removeBtn);

            fragment.appendChild(preview);
            previewContainer.appendChild(fragment);
        }
    });

    // 既存ファイル削除ボタンの処理
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".file-delete-btn")) return;

        const button = e.target.closest(".file-delete-btn");
        e.preventDefault();

        const fieldName = button.dataset.field;
        const dataId = button.dataset.dataId;
        const index = button.dataset.index;
        const previewItem = button.closest(".file-preview-item");

        // 削除確認
        if (
            !confirm(
                "ファイルを削除してもよろしいですか？この操作は元に戻せません。"
            )
        ) {
            return;
        }

        if (!dataId) {
            // データIDがない場合は単純にプレビュー要素を削除
            previewItem.remove();
            return;
        }

        let path = location.pathname;
        path = path.substr(0, path.indexOf("/admin"));
        let url = path ? path : "";

        // APIエンドポイントを構築
        url += `/admin/content-data/delete-file/${dataId}/${fieldName}`;
        if (index !== undefined) {
            url += `/${index}`;
        }

        // ファイル削除APIを呼び出す
        fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    previewItem.remove();
                    showNotification(data.message);

                    const input = document.getElementById(fieldName);
                    if (input && input.hasAttribute("data-required")) {
                        input.setAttribute("required", "");
                    }
                } else {
                    showNotification(data.message, true);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showNotification(
                    "ファイル削除中にエラーが発生しました。",
                    true
                );
            });
    });
}

// 画像圧縮関数
function compressImage(file, callback) {
    const reader = new FileReader();
    reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {
            const canvas = document.createElement("canvas");
            let width = img.width;
            let height = img.height;

            // 最大サイズを設定
            const MAX_WIDTH = 1200;
            const MAX_HEIGHT = 1200;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, width, height);

            // 圧縮した画像をBlobとして取得
            canvas.toBlob(
                function (blob) {
                    // ファイル名を維持したBlobをFileオブジェクトに変換
                    const compressedFile = new File([blob], file.name, {
                        type: "image/jpeg",
                        lastModified: Date.now(),
                    });
                    callback(compressedFile);
                },
                "image/jpeg",
                0.7
            ); // 品質を0.7に設定
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// 通知表示
function showNotification(message, isError = false) {
    if (!message) return;

    const notificationModalElement =
        document.getElementById("notificationModal");
    if (notificationModalElement && window.bootstrap) {
        const bootstrapModal = new window.bootstrap.Modal(
            notificationModalElement
        );
        const notificationModalBody = document.getElementById(
            "notificationModalBody"
        );

        if (notificationModalBody) {
            notificationModalBody.innerHTML = message;
            notificationModalBody.className = isError
                ? "text-danger"
                : "text-success";
            bootstrapModal.show();
        }
    } else {
        // モーダルが利用できない場合はアラートを使用
        alert(message);
    }
}

// ユーティリティ関数
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return (
        Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
    );
}
