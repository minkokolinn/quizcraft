<template>
    <div class="main-container">
        <div
            class="editor-container editor-container_document-editor editor-container_include-style editor-container_include-fullscreen"
            ref="editorContainerElement"
        >
            <div
                class="editor-container__menu-bar"
                ref="editorMenuBarElement"
            ></div>
            <div
                class="editor-container__toolbar"
                ref="editorToolbarElement"
            ></div>
            <div class="editor-container__editor-wrapper">
                <div class="editor-container__editor">
                    <div ref="editorElement">
                        <ckeditor
                            v-if="editor && config"
                            :modelValue="config.initialData"
                            :editor="editor"
                            :config="config"
                            @ready="onReady"
                        />
                    </div>
                </div>
            </div>
        </div>
        <!-- Add below the main editor container -->
        <div class="editor-floating-buttons">
            <button @click="printEditor">🖨️ Print</button>
            <!-- <button @click="exportEditorToPDF">📄 Export to PDF</button> -->
        </div>
    </div>
</template>

<script setup>
/**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/#installation/NoDgNARATAdArDADBSBGA7HKITqngNikQIIE4CBmEnVAgFjnTKcvTrgJClXopQgBTAHYpKYYKjBTE0uYgC6kAGa4ARnGVwICoA==
 */

import { computed, ref, onMounted, useTemplateRef } from "vue";
import { Ckeditor } from "@ckeditor/ckeditor5-vue";

import { LineHeight } from "@rickx/ckeditor5-line-height";
import lineHeightTranslations from "@rickx/ckeditor5-line-height/translations/zh-cn.js";

import html2pdf from "html2pdf.js";
import CryptoJS from "crypto-js";

import {
    DecoupledEditor,
    Alignment,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    Bold,
    Code,
    Emoji,
    Essentials,
    FindAndReplace,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Fullscreen,
    GeneralHtmlSupport,
    Heading,
    Highlight,
    HorizontalLine,
    ImageBlock,
    ImageCaption,
    ImageEditing,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    ImageUtils,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    Mention,
    PageBreak,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    SimpleUploadAdapter,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Style,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
} from "ckeditor5";

import "ckeditor5/ckeditor5.css";
import { router } from "@inertiajs/vue3";
/**
 * Create a free account with a trial: https://portal.ckeditor.com/checkout?plan=free
 */
const LICENSE_KEY = "GPL"; // or <YOUR_LICENSE_KEY>.

const editorToolbar = useTemplateRef("editorToolbarElement");
const editorMenuBar = useTemplateRef("editorMenuBarElement");

const isLayoutReady = ref(false);

const editor = DecoupledEditor;

const props = defineProps({
    user: Object,
    info: Object,
    sections: Array,
});
const info = props.info;
const sections = props.sections;
console.log("step 3");
console.log(info);
console.log(sections);

const config = computed(() => {
    if (!isLayoutReady.value) {
        return null;
    }

    return {
        toolbar: {
            items: [
                "fullscreen",
                "|",
                "heading",
                "style",
                "|",
                "fontSize",
                "lineHeight",
                "fontFamily",
                "fontColor",
                "fontBackgroundColor",
                "|",
                "bold",
                "italic",
                "underline",
                "|",
                "link",
                "insertImage",
                "insertTable",
                "highlight",
                "blockQuote",
                "|",
                "alignment",
                "|",
                "bulletedList",
                "numberedList",
                "todoList",
                "outdent",
                "indent",
                "|",
                "pageBreak",
            ],
            shouldNotGroupWhenFull: false,
        },
        plugins: [
            Alignment,
            Autoformat,
            AutoImage,
            AutoLink,
            Autosave,
            BalloonToolbar,
            BlockQuote,
            Bold,
            Code,
            Emoji,
            Essentials,
            FindAndReplace,
            FontBackgroundColor,
            FontColor,
            FontFamily,
            FontSize,
            Fullscreen,
            GeneralHtmlSupport,
            Heading,
            Highlight,
            HorizontalLine,
            ImageBlock,
            ImageCaption,
            ImageEditing,
            ImageInline,
            ImageInsert,
            ImageInsertViaUrl,
            ImageResize,
            ImageStyle,
            ImageTextAlternative,
            ImageToolbar,
            ImageUpload,
            ImageUtils,
            Indent,
            IndentBlock,
            Italic,
            Link,
            LinkImage,
            List,
            ListProperties,
            LineHeight, // new one
            Mention,
            PageBreak,
            Paragraph,
            PasteFromOffice,
            RemoveFormat,
            SimpleUploadAdapter,
            SpecialCharacters,
            SpecialCharactersArrows,
            SpecialCharactersCurrency,
            SpecialCharactersEssentials,
            SpecialCharactersLatin,
            SpecialCharactersMathematical,
            SpecialCharactersText,
            Strikethrough,
            Style,
            Subscript,
            Superscript,
            Table,
            TableCaption,
            TableCellProperties,
            TableColumnResize,
            TableProperties,
            TableToolbar,
            TextTransformation,
            TodoList,
            Underline,
        ],
        balloonToolbar: [
            "bold",
            "italic",
            "|",
            "link",
            "insertImage",
            "|",
            "bulletedList",
            "numberedList",
        ],
        fontFamily: {
            supportAllValues: true,
        },
        fontSize: {
            options: [
                "10px",
                "12px",
                "14px",
                "16px",
                "18px",
                "20px",
                "24px",
                "32px",
                "48px",
            ],
            supportAllValues: true,
        },
        fullscreen: {
            onEnterCallback: (container) =>
                container.classList.add(
                    "editor-container",
                    "editor-container_document-editor",
                    "editor-container_include-style",
                    "editor-container_include-fullscreen",
                    "main-container"
                ),
        },
        heading: {
            options: [
                {
                    model: "paragraph",
                    title: "Paragraph",
                    class: "ck-heading_paragraph",
                },
                {
                    model: "heading1",
                    view: "h1",
                    title: "Heading 1",
                    class: "ck-heading_heading1",
                },
                {
                    model: "heading2",
                    view: "h2",
                    title: "Heading 2",
                    class: "ck-heading_heading2",
                },
                {
                    model: "heading3",
                    view: "h3",
                    title: "Heading 3",
                    class: "ck-heading_heading3",
                },
                {
                    model: "heading4",
                    view: "h4",
                    title: "Heading 4",
                    class: "ck-heading_heading4",
                },
                {
                    model: "heading5",
                    view: "h5",
                    title: "Heading 5",
                    class: "ck-heading_heading5",
                },
                {
                    model: "heading6",
                    view: "h6",
                    title: "Heading 6",
                    class: "ck-heading_heading6",
                },
            ],
        },
        htmlSupport: {
            allow: [
                {
                    name: /^.*$/,
                    styles: true,
                    attributes: true,
                    classes: true,
                    element: "style",
                },
            ],
        },
        image: {
            toolbar: [
                "toggleImageCaption",
                "imageTextAlternative",
                "|",
                "imageStyle:inline",
                "imageStyle:wrapText",
                "imageStyle:breakText",
                "|",
                "resizeImage",
            ],
        },
        initialData: ``,
        licenseKey: LICENSE_KEY,
        link: {
            addTargetToExternalLinks: true,
            defaultProtocol: "https://",
            decorators: {
                toggleDownloadable: {
                    mode: "manual",
                    label: "Downloadable",
                    attributes: {
                        download: "file",
                    },
                },
            },
        },
        list: {
            properties: {
                styles: true,
                startIndex: true,
                reversed: true,
            },
        },
        mention: {
            feeds: [
                {
                    marker: "@",
                    feed: [
                        /* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
                    ],
                },
            ],
        },
        placeholder: "Type or paste your content here!",
        style: {
            definitions: [
                {
                    name: "Article category",
                    element: "h3",
                    classes: ["category"],
                },
                {
                    name: "Title",
                    element: "h2",
                    classes: ["document-title"],
                },
                {
                    name: "Subtitle",
                    element: "h3",
                    classes: ["document-subtitle"],
                },
                {
                    name: "Info box",
                    element: "p",
                    classes: ["info-box"],
                },
                {
                    name: "CTA Link Primary",
                    element: "a",
                    classes: ["button", "button--green"],
                },
                {
                    name: "CTA Link Secondary",
                    element: "a",
                    classes: ["button", "button--black"],
                },
                {
                    name: "Marker",
                    element: "span",
                    classes: ["marker"],
                },
                {
                    name: "Spoiler",
                    element: "span",
                    classes: ["spoiler"],
                },
            ],
        },
        table: {
            contentToolbar: [
                "tableColumn",
                "tableRow",
                "mergeTableCells",
                "tableProperties",
                "tableCellProperties",
            ],
        },
        transitions: [
            lineHeightTranslations, // line-height translations
        ],
        lineHeight: {
            options: [
                "0.0",
                "0.2",
                "0.4",
                "0.6",
                "0.8", // Custom line height values
                "1.0", // 1.0x
                "1.2", // 1.2x
                "1.5", // 1.5x
                "1.8", // 1.8x
                "2.0", // 2.0x
            ],
        },
    };
});

onMounted(() => {
    isLayoutReady.value = true;

    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            for (const node of mutation.addedNodes) {
                if (
                    node.nodeType === 1 &&
                    node.classList.contains("ck-fullscreen__main-wrapper")
                ) {
                    const fullscreenWrapper = document.querySelector(
                        ".ck-fullscreen__editable-wrapper"
                    ); // adjust if needed
                    // if (fullscreenWrapper && !fullscreenWrapper.querySelector('.my-custom-buttons')) {
                    const buttonContainer = document.createElement("div");
                    buttonContainer.style.position = "fixed";
                    buttonContainer.style.bottom = "20px";
                    buttonContainer.style.right = "20px";
                    buttonContainer.style.display = "flex";
                    buttonContainer.style.flexDirection = "column";

                    const printBtn = document.createElement("button");
                    printBtn.innerHTML = `<i class="bi bi-printer-fill"></i> Print`;
                    printBtn.style.cssText = `background-color: #1d4ed8; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background 0.2s ease; margin-bottom:20px;`;
                    printBtn.addEventListener("click", printEditor);

                    // const exportBtn = document.createElement("button");
                    // exportBtn.textContent = "📄 Export to PDF";
                    // exportBtn.style.cssText = printBtn.style.cssText;
                    // exportBtn.style.marginLeft = "10px";
                    // exportBtn.addEventListener("click", exportEditorToPDF);

                    const backBtn = document.createElement("button");
                    backBtn.innerHTML = `<i class="bi bi-arrow-return-left"></i> Back`;
                    backBtn.style.cssText = `background-color: #1d4ed8; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background 0.2s ease; margin-bottom:20px;`;
                    backBtn.addEventListener("click", goBack);

                    const exportProjectBtn = document.createElement("button");
                    exportProjectBtn.innerHTML = `<i class="bi bi-box-arrow-up-right"></i> Export Project`;
                    exportProjectBtn.style.cssText = `background-color: #1d4ed8; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background 0.2s ease;`;
                    exportProjectBtn.addEventListener("click", handleExport);

                    buttonContainer.appendChild(printBtn);
                    buttonContainer.appendChild(backBtn);
                    buttonContainer.appendChild(exportProjectBtn);

                    fullscreenWrapper.appendChild(buttonContainer);
                }
            }
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});

const editorInstance = ref(null);
function onReady(editor) {
    [...editorToolbar.value.children].forEach((child) => child.remove());
    [...editorMenuBar.value.children].forEach((child) => child.remove());

    editorToolbar.value.appendChild(editor.ui.view.toolbar.element);
    editorMenuBar.value.appendChild(editor.ui.view.menuBarView.element);

    // Wait until DOM is rendered, then simulate click on the fullscreen button

    const toolbarEl = editor.ui.view.toolbar.element;
    const fullscreenBtn = toolbarEl.querySelector(
        'button[data-cke-tooltip-text="Enter fullscreen mode"]'
    );
    if (fullscreenBtn) {
        fullscreenBtn.click(); // ✅ trigger fullscreen mode
    }

    editorInstance.value = editor;

    var initialContent = "<div>";
    info.headers.forEach((header) => {
        initialContent += `<h1 style="text-align: center; font-weight: bold; font-family: 'Minion Pro'; font-size: 25px;">
            ${header}
        </h1>`;
    });
    initialContent += `
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h1 style="flex: 1; text-align: left; font-weight: bold; font-family: 'Minion Pro'; font-size: 20px; margin: 0; padding: 5px;">
                Grade - ${info.grade}
            </h1>
            <h1 style="flex: 1; text-align: center; font-weight: bold; font-family: 'Minion Pro'; font-size: 20px; margin: 0; padding: 5px;">
                ${info.subject}
            </h1>
            <h1 style="flex: 1; text-align: right; font-weight: bold; font-family: 'Minion Pro'; font-size: 20px; margin: 0; padding: 5px;">
                Time Allowed : ${info.timeAllowed}
            </h1>
        </div>
    `;
    sections.forEach((section, index) => {
        initialContent += `
            <div style="display: flex; justify-content: space-between;">
                <p style="flex:7; text-align:left; font-weight: bold; font-family: 'Minion Pro'; font-size: 16px;">
                    ${index + 1} . ${section.section_type.header}
                </p>
                <p style="text-align:right; flex:1; font-weight: bold; font-family: 'Minion Pro'; font-size: 16px;">
                    (${
                        section.section_type.mark *
                        section.section_questions.length
                    }-marks)
                </p>
            </div>
        `;
        initialContent += `<ol style="list-style-type: upper-roman; font-family: 'Minion Pro'; font-size: 16px; line-height: 0.4; margin-bottom:30px;">`;
        section.section_questions.forEach((question) => {
            initialContent += `<li>
                <p style="margin-bottom:10px;">${question.body}</p>`;
            if (question.options.length > 0) {
                // Determine max content length
                // Determine max content length
                const maxLength = Math.max(
                    ...question.options.map((o) => o.content.length)
                );

                // Set layout based on longest option
                let groupSize = 4;
                if (maxLength > 40) {
                    groupSize = 1; // four lines
                } else if (maxLength > 20) {
                    groupSize = 2; // two lines
                }

                // Start layout container
                initialContent += `<div style="display: flex; flex-direction: column; gap: 6px;">`;

                for (let i = 0; i < question.options.length; i += groupSize) {
                    initialContent += `<div style="display: flex; gap: 20px;">`;

                    for (
                        let j = 0;
                        j < groupSize && i + j < question.options.length;
                        j++
                    ) {
                        const option = question.options[i + j];
                        initialContent += `
            <div style="display: flex; align-items: flex-start; flex: 1; min-width: 0;">
                <p style="margin: 0;"><span style="width: 20px; font-weight: bold;">${option.label}.</span></p>
                <p style="margin: 0; word-break: break-word;">${option.content}</p>
            </div>`;
                    }

                    initialContent += `</div>`;
                }

                initialContent += `</div>`; // end option group
            }
            if (question.image) {
                initialContent += `
                    <figure class="image image-style-align-center">
                        <img src="${question.image}" style="width: 50%;" />
                    </figure>
                `;
            }
            initialContent += `</li>`;
        });
        initialContent += `</ol>`;
    });
    initialContent += "</div>";

    editor.setData(initialContent);
}

// function printEditor() {
//     if (editorInstance.value) {
//         const printWindow = window.open("", "_blank");
//         const content = editorInstance.value.getData();

//         printWindow.document.write(`
//             <html>
//             <head>
//                 <title>Print</title>
//             </head>
//             <body>${content}</body>
//             </html>
//         `);
//         printWindow.document.close();
//         printWindow.focus();
//         printWindow.print();
//         printWindow.close();
//     } else {
//         alert("Editor is not ready.");
//     }
// }

function printEditor() {
    if (!editorInstance.value) {
        return alert("Editor is not ready.");
    }

    const content = editorInstance.value.getData();
    const printWindow = window.open("", "_blank");

    printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Print</title>
            <style>
                @media print {
                    img {
                        max-width: 100%;
                        height: auto;
                    }
                    figure.image.image-style-align-center {
                        display: block;
                        text-align: center;
                        margin: 1em auto;
                    }
                    figure.image.image-style-align-center img {
                        display: inline-block;
                        margin: 0 auto;
                    }
                }
                /* These should also apply outside print mode for consistency */
                figure.image.image-style-align-center {
                    display: block;
                    text-align: center;
                    margin: 1em auto;
                }
                figure.image.image-style-align-center img {
                    display: inline-block;
                    margin: 0 auto;
                }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);
    printWindow.document.close();

    // Ensure DOM is ready before checking images
    printWindow.onload = () => {
        const images = printWindow.document.images;

        if (images.length === 0) {
            // No images: print immediately
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        } else {
            // Wait for all images to load
            let loadedCount = 0;
            const totalImages = images.length;

            for (let img of images) {
                if (img.complete) {
                    loadedCount++;
                } else {
                    img.onload = img.onerror = () => {
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            printWindow.focus();
                            printWindow.print();
                            printWindow.close();
                        }
                    };
                }
            }

            // If all were already complete
            if (loadedCount === totalImages) {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        }
    };
}

function goBack() {
    router.post("/paper/create/step/2", {
        info,
        sections,
    });
}

const handleExport = () => {
    const secretKey = "babymonster";
    // 1. Gather data
    const exportData = { info, sections };

    // 2. Convert to JSON string
    const jsonString = JSON.stringify(exportData);
    const encrypted = CryptoJS.AES.encrypt(jsonString, secretKey).toString();

    // 3. Create a Blob from the string
    const blob = new Blob([encrypted], { type: "text/plain" });

    // 4. Create a temporary link and trigger download
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "project.txt";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};


</script>
<style scoped>
.editor-floating-buttons {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    gap: 10px;
    z-index: 10000;
}

.editor-floating-buttons button {
    background-color: #1d4ed8;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s ease;
    margin-right: 5px;
}

.editor-floating-buttons button:hover {
    background-color: #2563eb;
}
/* @media print {
    .ck-page-break {
        page-break-before: always;
        break-before: always;
        display: block !important;
        height: 0 !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
} */
@media print {
    .image.image-style-align-center {
        display: block;
        text-align: center;
        margin: 1em auto;
    }

    .image.image-style-align-center img {
        display: inline-block;
        max-width: 100%;
        height: auto;
    }
}
</style>
