<div class="panel panel-default">
    <div class="panel-body">
        <a href="{{ route('topics.create') }}" class="btn btn-success btn-block" aria-label="Left Align">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 新建帖子
        </a>
    </div>
</div>
@if (count($links))
    <div class="panel panel-default b-tags">
        <div class="panel-body active-users">

            <div class="text-center">标签</div>
            <hr>
            <ul class="b-all-tname">
            <?php $tag_i = 0; ?>
            @foreach ($tags as $tag)
                <?php $tag_i++; ?>
                <?php $tag_i=$tag_i==5?1:$tag_i; ?>
                <li class="b-tname">
                <a class="tstyle-{{ $tag_i }}" href="{{ route('tags.show', $tag->id)  }}">
                       {{ $tag->name }}
                </a>
                </li>
            @endforeach
            </ul>

        </div>
    </div>
@endif
@if (count($active_users))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">活跃用户</div>
            <hr>
            @foreach ($active_users as $active_user)
                <a class="media" href="{{ route('users.show', $active_user->id) }}">
                    <div class="media-left media-middle">
                        <img src="{{ $active_user->avatar }}" width="24px" height="24px" class="img-circle media-object">
                    </div>

                    <div class="media-body">
                        <span class="media-heading">{{ $active_user->name }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endif

@if (count($links))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">资源推荐</div>
            <hr>
            @foreach ($links as $link)
                <a class="media" href="{{ $link->link }}">
                    <div class="media-body">
                        <span class="media-heading">{{ $link->title }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endif