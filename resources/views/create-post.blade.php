<x-layout doctitle="Create New Post">
    <div class="container py-md-5 container--narrow">
      <livewire:createpost />
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