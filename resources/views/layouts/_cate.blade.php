<div class="form-group">
    <select class="form-control" name="category_id" required>
        <option value="" hidden disabled  {{ $topic->id ? '' : 'selected' }}>请选择分类</option>
        @foreach ($categories as $value)
            <option value="{{ $value->id }}" {{ $topic->category_id == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
        @endforeach
    </select>
</div>