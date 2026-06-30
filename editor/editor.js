// ===== MS Academy – Editor WYSIWYG =====

function fmt(cmd, val) {
    document.getElementById('editorArea').focus();
    document.execCommand(cmd, false, val || null);
}

function insertHeading(tag) {
    document.getElementById('editorArea').focus();
    document.execCommand('formatBlock', false, tag);
}

function insertLink() {
    const url = prompt('URL do link:');
    if (url) {
        const txt = prompt('Texto do link:', url);
        document.getElementById('editorArea').focus();
        document.execCommand('insertHTML', false,
            `<a href="${url}" target="_blank">${txt || url}</a>`);
    }
}

function insertImg() {
    const url = prompt('URL da imagem:');
    if (url) {
        const alt = prompt('Texto alternativo (acessibilidade):', '');
        document.getElementById('editorArea').focus();
        document.execCommand('insertHTML', false,
            `<img src="${url}" alt="${alt || ''}" style="max-width:100%;border-radius:8px;margin:8px 0;"/>`);
    }
}

function insertTable() {
    const rows = parseInt(prompt('Número de linhas:', '3')) || 3;
    const cols = parseInt(prompt('Número de colunas:', '3')) || 3;

    let html = '<table style="width:100%;border-collapse:collapse;margin:12px 0;">';
    html += '<thead><tr>';
    for (let c = 0; c < cols; c++) {
        html += `<th style="background:#1A56DB;color:#fff;padding:8px 12px;border:1px solid #D1D5DB;">Cabeçalho ${c + 1}</th>`;
    }
    html += '</tr></thead><tbody>';
    for (let r = 0; r < rows - 1; r++) {
        html += '<tr>';
        for (let c = 0; c < cols; c++) {
            html += `<td style="border:1px solid #D1D5DB;padding:8px 12px;">Célula</td>`;
        }
        html += '</tr>';
    }
    html += '</tbody></table>';

    document.getElementById('editorArea').focus();
    document.execCommand('insertHTML', false, html);
}

// Copia o innerHTML do editor para o input hidden
function syncEditor() {
    const area = document.getElementById('editorArea');
    const input = document.getElementById('html_content');
    if (area && input) {
        input.value = area.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const editor = document.getElementById('editorArea');

    // CORREÇÃO PRINCIPAL: sincronizar no evento submit do form,
    // não no onclick do botão (que pode disparar depois do envio)
    if (form) {
        form.addEventListener('submit', function (e) {
            syncEditor();
            // Valida se o editor não está vazio
            const conteudo = document.getElementById('editorArea').innerText.trim();
            if (!conteudo) {
                e.preventDefault();
                alert('O campo de conteúdo não pode estar vazio.');
                return false;
            }
        });
    }

    // Impede Enter de criar <div> em vez de <br>
    if (editor) {
        editor.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                document.execCommand('insertLineBreak');
                e.preventDefault();
            }
        });
    }
});