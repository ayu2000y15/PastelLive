/**
 * 管理画面共通JavaScript
 *
 * ファイルアップロード、プレビュー表示、モーダル操作などの機能を提供します
 */
document.addEventListener("DOMContentLoaded", () => {
    // DOM要素の取得
    const elements = {
        // 新規登録フォーム関連
        fileInput: document.getElementById("IMAGE"),
        dropArea: document.getElementById("dropArea"),
        fileSelectButton: document.getElementById("fileSelectButton"),
        filePreviewContainer: document.getElementById("filePreviewContainer"),
        newEntryBtn: document.getElementById("newEntryBtn"),
        dataForm: document.getElementById("dataForm"),
        cancelBtn: document.getElementById("cancelBtn"),

        // 編集フォーム関連
        editFileInput: document.getElementById("edit_IMAGE"),
        editDropArea: document.getElementById("edit_dropArea"),
        editFileSelectButton: document.getElementById("edit_fileSelectButton"),
        editFilePreviewContainer: document.getElementById(
            "edit_filePreviewContainer"
        ),

        // モーダル関連
        modal: document.getElementById("editModal"),
        closeBtn: document.querySelector(".close-modal"),

        // 編集ボタン
        editButtons: document.querySelectorAll(".edit-btn"),
    };

    // 新規登録フォームの表示/非表示切り替え
    if (elements.newEntryBtn && elements.dataForm && elements.cancelBtn) {
        elements.newEntryBtn.addEventListener("click", () => {
            elements.dataForm.style.display = "block";
        });

        elements.cancelBtn.addEventListener("click", () => {
            elements.dataForm.style.display = "none";
        });
    }

    // ファイル選択ボタンのクリックイベント
    if (elements.fileSelectButton && elements.fileInput) {
        elements.fileSelectButton.addEventListener("click", () => {
            elements.fileInput.click();
        });
    }

    if (elements.editFileSelectButton && elements.editFileInput) {
        elements.editFileSelectButton.addEventListener("click", () => {
            elements.editFileInput.click();
        });
    }

    // ファイル選択時のイベント
    if (elements.fileInput && elements.filePreviewContainer) {
        elements.fileInput.addEventListener("change", function () {
            handleFiles(
                this.files,
                elements.filePreviewContainer,
                elements.fileInput
            );
        });
    }

    if (elements.editFileInput && elements.editFilePreviewContainer) {
        elements.editFileInput.addEventListener("change", function () {
            handleFiles(
                this.files,
                elements.editFilePreviewContainer,
                elements.editFileInput
            );
        });
    }

    // ドラッグ&ドロップイベント設定
    setupDragAndDrop(
        elements.dropArea,
        elements.fileInput,
        elements.filePreviewContainer
    );
    if (
        elements.editDropArea &&
        elements.editFileInput &&
        elements.editFilePreviewContainer
    ) {
        setupDragAndDrop(
            elements.editDropArea,
            elements.editFileInput,
            elements.editFilePreviewContainer
        );
    }

    // 編集ボタンのイベントリスナーを設定
    setupEditButtons(elements.editButtons, elements.modal);

    // モーダルを閉じる処理
    if (elements.closeBtn && elements.modal) {
        elements.closeBtn.addEventListener("click", () => {
            elements.modal.style.display = "none";
        });

        // モーダル外クリックで閉じる
        window.addEventListener("click", (event) => {
            if (event.target == elements.modal) {
                elements.modal.style.display = "none";
            }
        });
    }
});

/**
 * ドラッグ&ドロップの設定
 * @param {HTMLElement} dropArea - ドロップエリア要素
 * @param {HTMLElement} fileInput - ファイル入力要素
 * @param {HTMLElement} previewContainer - プレビュー表示コンテナ
 */
function setupDragAndDrop(dropArea, fileInput, previewContainer) {
    if (!dropArea || !fileInput || !previewContainer) return;
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    ["dragenter", "dragover"].forEach((eventName) => {
        dropArea.addEventListener(
            eventName,
            () => {
                dropArea.classList.add("dragover");
            },
            false
        );
    });
    ["dragleave", "drop"].forEach((eventName) => {
        dropArea.addEventListener(
            eventName,
            () => {
                dropArea.classList.remove("dragover");
            },
            false
        );
    });

    dropArea.addEventListener("drop", (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files, previewContainer, fileInput);
    });
}

/**
 * ファイル処理関数
 * @param {FileList} files - 処理するファイルリスト
 * @param {HTMLElement} previewContainer - プレビュー表示コンテナ
 * @param {HTMLElement} fileInput - ファイル入力要素
 */
function handleFiles(files, previewContainer, fileInput) {
    if (!previewContainer || !fileInput) return;

    previewContainer.innerHTML = "";

    if (files.length === 0) {
        fileInput.setCustomValidity(
            "少なくとも1つのファイルを選択してください"
        );
    } else {
        fileInput.setCustomValidity("");
    }

    Array.from(files).forEach((file) => {
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const previewItem = document.createElement("div");
                previewItem.className = "file-preview-item";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.className = "file-preview-image";
                img.alt = file.name;

                const info = document.createElement("div");
                info.className = "file-preview-info";
                info.textContent = file.name;

                const size = document.createElement("div");
                size.className = "file-preview-size";
                size.textContent = formatFileSize(file.size);

                const removeButton = document.createElement("div");
                removeButton.className = "file-preview-remove";
                removeButton.textContent = "×";
                removeButton.addEventListener("click", () => {
                    // 注意: 実際のファイル削除はできないため、プレビューのみ削除
                    previewItem.remove();
                });

                previewItem.appendChild(img);
                previewItem.appendChild(info);
                previewItem.appendChild(size);
                previewItem.appendChild(removeButton);

                previewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }
    });
}

/**
 * ファイルサイズのフォーマット関数
 * @param {number} bytes - バイト数
 * @returns {string} フォーマットされたサイズ文字列
 */
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return (
        Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
    );
}

/**
 * 編集ボタンの設定
 * @param {NodeList} editButtons - 編集ボタン要素のリスト
 * @param {HTMLElement} modal - モーダル要素
 */
function setupEditButtons(editButtons, modal) {
    if (!editButtons || !modal) return;

    // グローバル変数からフィールド定義を取得（PHP側で定義）
    const fields = window.photoFields || window.newsFields || [];
    const radioFieldsConfig = window.radioFields || [];
    const imagePreviewConfig = window.imagePreviewFields || [];

    editButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            // JSONデータを取得して解析
            const infoData = JSON.parse(this.getAttribute("data-info"));

            // デバッグ情報をコンソールに出力
            console.log("編集ボタンがクリックされました");
            console.log("データ:", infoData);

            // フォームに値を設定
            setFormValues(infoData, fields);

            // ラジオボタンの設定（存在する場合）
            if (radioFieldsConfig.length > 0) {
                setRadioValues(infoData, radioFieldsConfig);
            }

            // 画像プレビューの設定
            setImagePreviews(infoData, imagePreviewConfig);

            // モーダルを表示
            modal.style.display = "block";
        });
    });
}

/**
 * フォームに値を設定する
 * @param {Object} data - フォームに設定するデータ
 * @param {Array} fields - フィールド定義の配列 [{id: 'element_id', key: 'data_key'}, ...]
 */
function setFormValues(data, fields) {
    if (!data || !fields || !Array.isArray(fields)) return;

    // 各フィールドに値を設定
    fields.forEach((field) => {
        const element = document.getElementById(field.id);
        if (element && data[field.key] !== undefined) {
            element.value = data[field.key];
        }
    });
}

/**
 * ラジオボタンに値を設定する
 * @param {Object} data - フォームに設定するデータ
 * @param {Array} radioFields - ラジオボタン定義の配列
 */
function setRadioValues(data, radioFields) {
    if (!data || !radioFields || !Array.isArray(radioFields)) return;

    radioFields.forEach((fieldGroup) => {
        const fieldName = fieldGroup.name;
        const fieldValue = data[fieldName];

        if (fieldValue !== undefined && Array.isArray(fieldGroup.values)) {
            fieldGroup.values.forEach((option) => {
                const radioElement = document.getElementById(option.id);
                if (radioElement) {
                    radioElement.checked = fieldValue == option.value;
                }
            });
        }
    });
}

/**
 * 画像プレビューを設定する
 * @param {Object} data - フォームに設定するデータ
 * @param {Array} previewFields - プレビュー定義の配列
 */
function setImagePreviews(data, previewFields) {
    if (!data || !previewFields || !Array.isArray(previewFields)) return;

    previewFields.forEach((field) => {
        const element = document.getElementById(field.id);
        if (element && data[field.key]) {
            element.src = data[field.key];
        }
    });
}
