<x-layout>
    <div class="container py-md-5 container--narrow">
      <form action="create-post" method="POST">
        @csrf
        <div class="form-group">
          <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
          <input value="{{old('title')}}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
          @error('title')
          <p class="m-0 alert alert-danger shadow-sm">{{$message}}</p>
          @enderror
        </div>

        <div class="form-group">
          <label for="post-body" class="text-muted mb-1">
            <small>Body Content (Supports Markdown)</small>
            <button type="button" id="markdown-help" class="btn btn-sm btn-outline-secondary ml-2">Markdown Help</button>
            <button type="button" id="toggle-preview" class="btn btn-sm btn-outline-primary ml-2">Preview</button>
          </label>
          <textarea name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{old('body')}}</textarea>
          <div id="markdown-preview" class="card p-3 mt-2 d-none">
            <h5 class="card-title">Preview</h5>
            <div id="preview-content" class="card-body"></div>
          </div>
          @error('body')
          <p class="m-0 alert alert-danger shadow-sm">{{$message}}</p>
          @enderror
        </div>

        <!-- Markdown Help Modal -->
        <div class="modal fade" id="markdownHelpModal" tabindex="-1" aria-labelledby="markdownHelpModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="markdownHelpModalLabel">Markdown Formatting Guide</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <h6>Headings</h6>
                    <pre>
### Heading</pre>

                    <h6>Emphasis</h6>
                    <pre>*italic* or _italic_
**bold** or __bold__
~~strikethrough~~</pre>

                    <h6>Lists</h6>
                    <pre>- Unordered item
- Another item

1. First item
2. Second item</pre>
                  </div>
                  <div class="col-md-6">
                    <h6>Links</h6>
                    <pre>[Link text](https://example.com)</pre>

                    <h6>Blockquotes</h6>
                    <pre>> This is a blockquote</pre>

                    <h6>Code</h6>
                    <pre>`inline code`

```
code block
```</pre>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <button class="btn btn-primary">Save New Post</button>
      </form>
    </div>

    <!-- Add the marked.js library from CDN for markdown parsing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.0.2/marked.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const postBody = document.getElementById('post-body');
        const markdownPreview = document.getElementById('markdown-preview');
        const previewContent = document.getElementById('preview-content');
        const togglePreview = document.getElementById('toggle-preview');
        const markdownHelp = document.getElementById('markdown-help');
        
        // Initialize with Bootstrap's modal
        $('#markdownHelpModal').modal({
          show: false
        });
        
        // Show markdown help modal
        markdownHelp.addEventListener('click', function() {
          $('#markdownHelpModal').modal('show');
        });
        
        // Toggle preview
        togglePreview.addEventListener('click', function() {
          if (markdownPreview.classList.contains('d-none')) {
            markdownPreview.classList.remove('d-none');
            previewContent.innerHTML = marked.parse(postBody.value);
            togglePreview.textContent = 'Hide Preview';
          } else {
            markdownPreview.classList.add('d-none');
            togglePreview.textContent = 'Preview';
          }
        });
        
        // Update preview on input
        postBody.addEventListener('input', function() {
          if (!markdownPreview.classList.contains('d-none')) {
            previewContent.innerHTML = marked.parse(postBody.value);
          }
        });
        
        // Add toolbar buttons for common markdown formatting
        const toolbar = document.createElement('div');
        toolbar.className = 'btn-toolbar mb-2';
        toolbar.setAttribute('role', 'toolbar');
        toolbar.innerHTML = `
          <div class="btn-group mr-2" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="bold">Bold</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="italic">Italic</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="heading">Heading</button>
          </div>
          <div class="btn-group mr-2" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="link">Link</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="code">Code</button>
          </div>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="quote">Quote</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-format="ul">List</button>
          </div>
        `;
        
        postBody.parentNode.insertBefore(toolbar, postBody);
        
        // Format functions
        const formats = {
          bold: function(text) {
            return `**${text || 'bold text'}**`;
          },
          italic: function(text) {
            return `*${text || 'italic text'}*`;
          },
          heading: function(text) {
            return `### ${text || 'Heading'}`;
          },
          link: function(text) {
            return `[${text || 'link text'}](https://example.com)`;
          },

          code: function(text) {
            return text ? `\`${text}\`` : "```\ncode block\n```";
          },
          quote: function(text) {
            return `> ${text || 'blockquote'}`;
          },
          ul: function(text) {
            return `- ${text || 'list item'}`;
          }
        };
        
        // Apply formatting to selected text or insert template
        toolbar.addEventListener('click', function(e) {
          const button = e.target.closest('button[data-format]');
          if (!button) return;
          
          const format = button.getAttribute('data-format');
          const start = postBody.selectionStart;
          const end = postBody.selectionEnd;
          const selectedText = postBody.value.substring(start, end);
          const formattedText = formats[format](selectedText);
          
          postBody.focus();
          document.execCommand('insertText', false, formattedText);
          
          // Update preview if visible
          if (!markdownPreview.classList.contains('d-none')) {
            previewContent.innerHTML = marked.parse(postBody.value);
          }
        });
      });
    </script>
</x-layout>