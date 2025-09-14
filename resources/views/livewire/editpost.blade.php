<div>
    <form wire:submit="save" action="/post/{{$post->id}}" method="POST">
        <p><small><strong><a href="/post/{{$post->id}}">&laquo; Back to post</a></strong></small></p>
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
          <input wire:model="title" value="{{old('title', $post->title)}}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
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
          <textarea wire:model="body" name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{old('body', $post->body)}}</textarea>
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

        <button class="btn btn-primary">Save Changes</button>
      </form>
</div>
