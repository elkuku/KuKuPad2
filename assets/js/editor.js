import SimpleMDE from 'simplemde'

import $ from 'jquery'

// const hljs = require('highlight.js')
// require('highlight.js/styles/a11y-dark.css')

require('simplemde/dist/simplemde.min.css')

// import { marked } from 'marked'

function renderPreview(text) {

    const previewUrl = '/markdown/preview'

    const result = $.ajax(
        {
            type: 'POST',
            url: previewUrl,
            async: false,
            data: {text: text}
        }
    )

    // let out = $('code')
    // console.log(out)

    // hljs.highlightBlock(out)
    // document.querySelectorAll('pre code').forEach((block) => {
    //     console.log(block)
    //     hljs.highlightBlock(block)
    // })

    return result.responseJSON.data
}

const simplemde = new SimpleMDE({
    forceSync: true,
    spellChecker: false,
    previewRender: renderPreview,
})

// const $ = require('jquery')
// const hljs = require('highlight.js')
//
// require('../css/editor.css')
// require('highlight.js/styles/a11y-dark.css')

// const jsData = $('#js-data')
//
// const previewUrl = jsData.data('preview-url')
// const editorField = jsData.data('editor-field')
// const previewField = jsData.data('preview-field')
//
// $('a[data-toggle="tab"]').on('click', function (e) {
//     if (previewField === $(e.target).attr('href')) {
//         let out = $(previewField)
//         out.empty().addClass('loading')
//         $.post(
//             previewUrl,
//             {text: $(editorField).val()},
//             function (r) {
//                 out.html(r.data).removeClass('loading')
//                 hljs.highlightBlock(out)
// document.querySelectorAll('pre code').forEach((block) => {
//     hljs.highlightBlock(block);
// });
// }
// )
// }
// })

