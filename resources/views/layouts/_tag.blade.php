<div class="form-group">
        @foreach ($tags as $t)
      {{ $t->name }}<input class="checkbox-inline" type="checkbox" name="tag_ids[]" value="{{ $t['id'] }}" @if(in_array($t->id, old('tag_ids',$topic->tag_ids))) checked="checked" @endif> &emsp;
        @endforeach

</div>